<?php 
    class scb_messenger_class extends scb_bot_class{

        public function __construct($id)
        {
            parent::__construct($id);
            $this->setMessengerbotDetails();
        }


        public function sendResponse($user =false,$message =false,$modal =false) {
            if($modal) $this->send($this->getToken(),$this->getResponseForceString($user,$message));
            else $this->send($this->getToken(),$this->getResponseString());
        }

        public function send($token, $message) {
            $url = 'https://graph.facebook.com/v2.6/me/messages';
            $args = array(
                'method'      => 'GET',
                'timeout'     => 5,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'cookies'     => array(),
                'body'        => array(
                    'access_token' => $token,
                ),
            );

            $response = wp_remote_request($url, $args);

            
            if(get_option('social_bot_checkbox_option_var_dump')) {
                var_dump($message);
                exit;
            }
            
        }

        static function sent (){
            
        }

    }

?>
