<?php

    class Session{

        public static function start() {

            @session_start();

            if(!isset($_SESSION['login'])){

                if(isset($_COOKIE['AUTHKEY']) && isset($_COOKIE['_signature'])){

                    $db = new Database;

                    $find_key = $db->query('SELECT * FROM user WHERE login_key = ?', $_COOKIE['AUTHKEY']);

                    if($find_key->numRows() > 0){

                        if( crypt($_SERVER['HTTP_USER_AGENT'], $_COOKIE['_signature']) == $_COOKIE['_signature'] ){

                            $user_info = $find_key->fetchArray();

                            $user_group_query = $db->query("SELECT g.id, g.group_name FROM user_group AS g JOIN user_group_relation AS r ON g.id=r.group_id WHERE r.user_id = ?", $user_info['id']);

                            if($user_group_query->numRows() > 0){

                                $user_group_info = $user_group_query->fetchArray();

                                $_SESSION['login'] = $user_info['email'];
                                $_SESSION['userid'] = $user_info['id'];
                                $_SESSION['groupid'] = $user_group_info['id'];
                                $_SESSION['group'] = $user_group_info['group_name'];
                                $_SESSION['name'] = $user_info['name'];

                                $signature = @crypt($_SERVER['HTTP_USER_AGENT']);
                                
                                $authkey = md5($_SESSION['userid'] . Util::generateRandomString(32));

                                $db->query('UPDATE user SET login_key = ? WHERE id = ?', $authkey, $user_info['id']);

                                setcookie('AUTHKEY', $authkey, time() + (86400 * 30), "/");
                                setcookie('_signature', $_COOKIE['_signature'], time() + (86400 * 30), "/");

                            }

                        }
                    }

                    $db->close();
                }

            }

        }

        public static function destroy() {

            setcookie("AUTHKEY", "", time() - 3600, "/");
            setcookie("_signature", "", time() - 3600, "/");

            session_unset();
            session_destroy();

        }
    }