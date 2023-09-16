<?php

    class Notification{

        public $event;

        public $user_id = 0;

        public $data;

        private $template_id;

        private $subject;

        private $html;

        private $text;

        private $db;


        public function __construct(){

            $this->db = new Database();

            $this->data = array('SITE_TITLE'=>SITE_TITLE, 'SITE_NAME'=>SITE_NAME, 'DOMAIN'=>parse_url(APP_URL, PHP_URL_HOST), 'YEAR'=>date('Y'));

        }


        public function Notify(){

            if(empty($this->user_id) && isset($_SESSION['userid'])) $this->user_id = $_SESSION['userid'];

            if(!empty($this->event)){

                $notificationQuery = $this->db->Query("SELECT n.group_id,n.user_id,n.type,t.subject,t.html,t.text,n.template_id FROM notification n JOIN notification_event AS e ON e.id=n.event_id JOIN template As t ON t.id=n.template_id WHERE e.status='1' AND e.event=? AND t.type=n.type AND t.status='1'", $this->event);

                if($notificationQuery->numRows() > 0){

                    $notifications = $notificationQuery->fetchAll();

                    $failed = $success = 0;

                    foreach($notifications as $notification){

                        $this->template_id = $notification['template_id'];
                        $this->subject = $notification['subject'];
                        $this->html = $notification['html'];
                        $this->text = $notification['text'];

                        $targetUsers = [];

                        if($notification['group_id'] != 0){

                            $targetUsers = $this->db->Query("SELECT u.id,u.name,u.phone,u.email FROM user u JOIN user_group_relation AS r ON r.user_id=u.id WHERE r.group_id=?", $notification['group_id'])->fetchAll();

                        }else{

                            $targetUid = ($notification['user_id'] != 0 ? $notification['user_id'] : $this->user_id);

                            $targetUsers = $this->db->Query("SELECT id,name,phone,email FROM user WHERE id=?", $targetUid)->fetchAll();
                        }


                        foreach($targetUsers as $user){

                            $this->{$notification['type']}($user) ? $success++ : $failed++;
                        }


                    }


                    if($success > 0){

                        return true;

                    }else{

                        return false;
                    }

                }
            }
        }


        public function setData($key, $value){

            $this->data[$key] = $value;

        }



        private function EMAIL($uinfo){

            require_once(FRAMEWORK_PATH . 'helpers/mailer.class.php');

            $this->setData("SUBJECT", $this->subject);

            $mail_html_body = $this->contentReady($this->html, $uinfo);

            $mail_text_body = $this->contentReady($this->text, $uinfo);

            if(!empty($this->subject) && $mail_html_body && $mail_text_body){

                $mail = new Mailer;
                $mail->Subject = $this->subject;
                $mail->Body = $mail_html_body;
                $mail->AltBody = $mail_text_body;
                $mail->addAddress($uinfo['email'], $uinfo['name']);

                if ($mail->send()){

                    $this->nofifyLog($uinfo['id'], 'EMAIL', 'Notification Email has been successfully sent.', $this->subject, $mail_html_body, $mail_text_body);

                    return true;

                }else{

                    $this->nofifyLog($uinfo['id'], 'EMAIL', 'Error: Failed to sent email.', $this->subject, $mail_html_body, $mail_text_body);
                }

            }else{

                $this->nofifyLog($uinfo['id'], 'EMAIL', 'Error: There is not enough data available to send this email.', $this->subject, $mail_html_body, $mail_text_body);
            }

            return false;
        }


        private function SMS($uinfo){

            require_once(FRAMEWORK_PATH . 'helpers/sms.class.php');

            $sms_body = $this->contentReady($this->text, $uinfo);

            if($sms_body){

                $sms = new SMS;

                if($sms->sendSMS($uinfo['phone'], $sms_body)){

                    $this->nofifyLog($uinfo['id'], 'SMS', 'Notification SMS has been successfully sent.', null, null, $sms_body);

                    return true;

                }else{

                    $this->nofifyLog($uinfo['id'], 'SMS', 'Error: Failed to sent sms.', null, null, $sms_body);
                }

            }else{

                $this->nofifyLog($uinfo['id'], 'SMS', 'Error: There is not enough data available to send this sms', null, null, $sms_body);
            }

            return false;
        }


        private function ALERT($uinfo){



        }


        private function contentReady($content, $uinfo){

            if(empty($content)){

                return false;
            }

            $data = $this->data;

            if(!array_key_exists("USER_NAME",$data)) $data["USER_NAME"] = $uinfo['name'];

            if(!array_key_exists("USER_EMAIL",$data)) $data["USER_EMAIL"] = $uinfo['email'];

            if(!array_key_exists("USER_PHONE",$data)) $data["USER_PHONE"] = $uinfo['phone'];


            foreach($data as $name => $value){

                $content = str_ireplace("{{DATA::".$name."}}", $value, $content);

            }

            if(preg_match('/{{DATA::([_A-Za-z0-9]+)}}/', $content, $regs)){
                print_r($regs);
                return false;
            }


            return $content;
        }


        private function nofifyLog($uid, $type, $desc, $subject, $html, $text){

            $this->db->Query("INSERT INTO notification_log (event, template_id, user_id, type, description, subject, html, text, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", $this->event, $this->template_id, $uid, $type, $desc, $subject, $html, $text, TIMESTAMP);
        }
    }