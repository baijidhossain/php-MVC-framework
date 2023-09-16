<?php

    class Framework
    {

        private static $currentController = 'Home';
        private static $currentMethod = 'Index';
        private static $currentParams = [];

        public static function run()
        {
            self::init();

            self::autoload();

            self::dispatch();
        }

        private static function init()
        {
            // Define path constants

            define("DS", DIRECTORY_SEPARATOR);

            define("ROOT", getcwd() . DS);

            define("APP_PATH", ROOT . 'application' . DS);

            define("FRAMEWORK_PATH", ROOT . "framework" . DS);

            define("PUBLIC_PATH", ROOT . "public" . DS);

            define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);

            define("MODEL_PATH", APP_PATH . "models" . DS);

            define("VIEW_PATH", APP_PATH . "views" . DS);

            //Require config file
            require APP_PATH . 'config/config.php';

            if (FORCE_HTTPS) {
                if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
                    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $redirect);
                    die();
                }
            }

            if (APP_MODE == "Debug") {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
            } else {
                ini_set('display_errors', 0);
                ini_set('display_startup_errors', 0);
                error_reporting(0);
            }


            //Set TimeZone
            date_default_timezone_set(TIMEZONE);

            define("TIMESTAMP", date("Y-m-d H:i:s"));
        }

        private static function autoload()
        {
            require FRAMEWORK_PATH . 'core/Authorize.php';
            require FRAMEWORK_PATH . 'core/Controller.php';
            require FRAMEWORK_PATH . 'core/Database.php';
            require FRAMEWORK_PATH . 'core/Notification.php';
            require FRAMEWORK_PATH . 'core/Session.php';
            require FRAMEWORK_PATH . 'core/Util.php';
            require FRAMEWORK_PATH . 'core/fpdf/fpdf.php';
          
        }

        private static function dispatch()
        {
            Session::start();


            $URL = ltrim($_SERVER['REQUEST_URI'], "index.php");
            $URL = explode('/', rtrim(ltrim($URL, "/"), "/"));


            //Define Section
            $app_section = json_decode(file_get_contents(APP_PATH . "config/section.json"), true);

            if (in_array($URL[0], $app_section)) {
                define("APP_SECTION", ucfirst($URL[0]) . DS);

                unset($URL[0]);

                $actual_url = implode("/", $URL);

                $URL = explode('/', $actual_url);
            } else {
                define("APP_SECTION", "");
            }


            //Define Controller
            if ( ! empty($URL[0])) {
                if (file_exists(CONTROLLER_PATH . APP_SECTION . ucfirst($URL[0]) . '.php')) {
                    self::$currentController = ucfirst($URL[0]);
                    unset($URL[0]);
                } else {
                    include(VIEW_PATH . '_common/404.php');
                    exit();
                }
            }


            //Default Controller Check
            if ( ! file_exists(CONTROLLER_PATH . APP_SECTION . self::$currentController . '.php')) {
                include(VIEW_PATH . '_common/404.php');
                exit();
            }

            require CONTROLLER_PATH . APP_SECTION . self::$currentController . '.php';

            $current_controller_name = self::$currentController;

            self::$currentController = new self::$currentController;


            //Define Method
            if (isset($URL[1]) && method_exists(self::$currentController, $URL[1])) {
                self::$currentMethod = ucfirst($URL[1]);
                unset($URL[1]);
            }


            //Check Permission
            if ( ! AUTH::checkPermission(APP_SECTION, $current_controller_name,
                self::$currentMethod)) {
                include(VIEW_PATH . '_common/403.php');
                exit();
            }

            //define Current URL Path for active menu
            define("CUR_REQUEST_PATH",
                APP_SECTION . $current_controller_name . '/' . self::$currentMethod);


            //Define Parameters
            self::$currentParams = $URL ? array_values($URL) : [];


            call_user_func_array([self::$currentController, self::$currentMethod],
                self::$currentParams);
        }
    }