<?php

// https://codeshack.io/super-fast-php-mysql-database-class/

class Database{

    protected $connection;
    protected $query;
    public $query_count = 0;

    public function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }

    public function query($query) {
        if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
            if ($this->query->errno) {
                die('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_count++;
        } else {
            die('Unable to prepare statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }

    public function fetchAll() {
        $params = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            $result[] = $r;
        }
        $this->query->close();
        return $result;
    }

    public function fetchArray() {
        $params = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        return $result;
    }

    public function numRows() {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    public function close() {
        return $this->connection->close();
    }

    public function affectedRows() {
        return $this->query->affected_rows;
    }

    public function lastInsertID() {
        return $this->connection->insert_id;
    }

    public function changeDB($dbname){

    }

    public function beginTransaction(){
        $this->connection->query("BEGIN");
    }

    public function commit(){
        $this->connection->query("COMMIT");
    }

    public function rollback(){
        $this->connection->query("ROLLBACK");
    }

    private function _gettype($var) {
        if(is_string($var)) return 's';
        if(is_float($var)) return 'd';
        if(is_int($var)) return 'i';
        return 'b';
    }

    public function paginateQuery($query, array $values = []){

        $page = (!isset($_GET['page']) || $_GET['page']=="" ? 0 : $_GET['page']);

        $limit = (isset($_SESSION['PAGINATION_LIMIT']) ? $_SESSION['PAGINATION_LIMIT'] : PAGINATION_LIMIT);

        $start = ($page == 0 ? 0 : ($page - 1) * $limit);

        $page_num = ($page == 0 ? 1 : $page);

        if(empty($values)){

            $content = $this->query($query." LIMIT $start, $limit")->fetchAll();
            $totalCount = $this->query($query)->numRows();

        }else{

            $content = $this->query($query." LIMIT $start, $limit", ...$values)->fetchAll();
            $totalCount = $this->query($query, $values)->numRows();
        }
        $this->query->close();

        $totalPage = ceil($totalCount/$limit);


        $params = $_GET;
        unset($params['page']); $params['page'] = '';
        $cUrl = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?'.http_build_query($params);


        $pHTML = '<ul class="pagination pull-right" style="margin-top:0px;">';
        $pHTML .= '<li '.($page_num == 1 ? 'class="disabled"' : '').'><a href="'.$cUrl.($page_num > 1 ? ($page_num -1) : '#').'">Prev</a></li>';
        $pHTML .= '<li'.($page_num == 1 ? ' class="active"' : '').'><a href="'.$cUrl.'1">1</a></li>';
        $pHTML .= ($page_num > 4 ? '<li class="disabled"><a>...</a></li>' : '');
        $startLoop = ($page_num > 4 ? ($page_num-2) : 2);
        $endLoop = ($page_num < ($totalPage-3) ? ($page_num+2) : ($totalPage-1));
        for($i=$startLoop; $i<=$endLoop; $i++)
        {
            $pHTML .= '<li'.($i == $page_num ? ' class="active"' : '').'><a href="'.$cUrl.$i.'">'.$i.'</a></li>';
        }
        $pHTML .=  ($page_num < ($totalPage-3) ? '<li class="disabled"><a>...</a></li>' : '');
        $pHTML .= ($totalPage > 1 ? '<li'.($i == $page_num ? ' class="active"' : '').'><a href="'.$cUrl.$totalPage.'">'.$totalPage.'</a></li>' : '');
        $pHTML .= '<li '.($page_num == $totalPage ? 'class="disabled"' : '').'><a href="'.$cUrl.($page_num < $totalPage ? ($page_num + 1) : '#').'">Next</a></li>';
        $pHTML .= '</ul>';

        $info = 'Showing '.((($page_num -1) * $limit) + 1 ).' to '.(($page_num * $limit) > $totalCount ? $totalCount : ($page_num * $limit)).' of '.$totalCount;


        return array("paginateData"=>$content, "paginateNav"=>$pHTML, "paginateInfo"=>$info);
    }
}
?>
