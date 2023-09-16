<?php

    class Controller{

        public function loadModel($model){
            // Require model file
            require_once MODEL_PATH . APP_SECTION . $model . '.php';

            // Instantiate model
            $model = $model.'Model';
            return new $model();
        }

        // Lets us load view from controllers
        public function view($view, $data = []){
            // Check for view file
            if(file_exists(VIEW_PATH . APP_SECTION .$view.'.php')){
                // Require view file
                require_once VIEW_PATH . APP_SECTION .$view.'.php';
            } else {
                // No view exists
                require_once VIEW_PATH . '_common/404.php';
            }
        }

        public function Navigation(){

            require APP_PATH . 'config/navigation.php';
            echo 'Navigation';
        }

        public function setAlert($type, $msg){

            $_SESSION['alerts'][] = array('type'=>$type, 'msg'=>$msg);
        }

        public function getAlert(){

            if(isset($_SESSION['alerts'])){

                foreach($_SESSION['alerts'] as $alert)
                {
                    $type = ($alert['type'] == 'error' ? 'danger' : $alert['type']);
                    echo '<div class="alert alert-dismissible alert-'.$type.'"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$alert['msg'].'</div>';
                }

                unset($_SESSION['alerts']);
            }


        }

    }