<?php 

    class scb_bot_class {

        private $id = "";
        private $token = "";
        private $key = "";
        private $sender = "";
        private $message = "";
        private $message_p = "";
        private $error_message = "";

        private $message_clear_user = "";

        private $command_map = array();

        public function __construct($id) {
            $this->setPostID($id);
            $this->setToken();
            $this->setKey();
            $input = json_decode(file_get_contents('php://input'), true);
            if($input) {
                $this->setSender($input['entry'][0]['messaging'][0]['sender']['id']);
                $this->setMessage($input['entry'][0]['messaging'][0]['message']['text']);
                if(!$this->getMessage()) $this->setMessage($input['entry'][0]['messaging'][0]['postback']['payload']);

                $this->message_clear_user = get_post_meta($id, 'scb_delete_user_msg', true);
                $this->setMessage(trim(strtolower($this->getMessage())));

                if($this->message_clear_user == $this->getMessage()) {
                    $this->removeSender($this->getSender());
                }

                $this->setErrorMessage();
            } else {
                wp_redirect(home_url());
                exit;
            }
            
            
        }

        public function elaborateResponse() {
            $map = $this->getMessengerbotDetails();
            $type = $map[$this->getMessage()]["response_type"];
            if($type === 'output_text_sender') {
                return array(
                    "text"=>$map[$this->getMessage()]["response"]
                );
            } elseif($type) {
                return array(
                    "attachment"=>$this->getMessageAttach($type, $map[$this->getMessage()]["response_cat"])
                );
            }
            return $this->errorResponse();
        }

        public function getMessageAttach($type = null, $cat = null){
            if($type == 'output_category_sender') {
                $args = array( 'posts_per_page' => 5, 'category' => $cat);
            } elseif($type == 'output_tag_sender') {
                $args = array( 'posts_per_page' => 5, 'tag' => $cat);
            } else {
                $args = array( 'posts_per_page' => 5);
            }
            $lista = array();
            $myposts = get_posts( $args ); 
            foreach ( $myposts as $post ) {
                setup_postdata( $post ); 
                $lista[] = $post->ID;
            }
            wp_reset_postdata();
            return array(
                "type" => "template",
                "payload" => array(
                    "template_type" => "generic",
                    "elements" => $this->adaptPostforMessender($lista)
                )
            );
            return implode(",", $lista);
        }

        public function adaptPostforMessender($lista_id) {
            $element = array();
            foreach ($lista_id as $single) {
                $s_post = array(
                    "title" => get_the_title($single),
                    "image_url" => wp_get_attachment_image($single),
                    "subtitle" => get_the_excerpt($single),
                    "default_action"  =>  array(
                      "type"  =>  "web_url",
                      "url" =>  get_permalink($single),
                      "webview_height_ratio"  => "tall",
                    ),
                    "buttons" =>  array(
                        array(
                            "type" => "web_url",
                            "url" => get_permalink($single),
                            "title" => "View Website"
                        )
                   )   
                );

                $element[] = $s_post;
            }
            return $element;
        }

        public function getPostID() {
            return $this->id;
        }

        public function setPostID($id) {
            $this->id = $id;
        }

        public function saveError () {
            $error_list = explode("|",get_post_meta($this->getPostID(), 'scb_message_failure_list', true));
            $error_list[] = $this->getMessage();
            $error_list = array_unique($error_list);
            update_post_meta($this->getPostID(), 'scb_message_failure_list', implode('|',array_filter($error_list)));

        }

        public function saveSender ($senderID) {
            $user_list = explode("|",get_post_meta($this->getPostID(), 'scb_user_list', true));
            $user_list[] = $senderID;
            $user_list = array_unique($user_list);
            update_post_meta($this->getPostID(), 'scb_user_list', implode('|',array_filter($user_list)));
        }

        public function removeSender ($senderID) {
            $user_list = explode("|",get_post_meta($this->getPostID(), 'scb_user_list', true));
            unset($user_list[array_search($senderID, $user_list)]);
            update_post_meta($this->getPostID(), 'scb_user_list', implode('|',array_filter($user_list)));
        }

        public function errorResponse() {
            $this->saveError();
            return array(
                "text"=>$this->getErrorMessage()
            );
        }

        public function getErrorMessage() {
            return $this->error_message;
        }

        public function setErrorMessage() {

            $error_message = get_post_meta($this->id,'scb_error_msg');
            $this->error_message = $error_message[0];
        }

        public function getToken() {
            return $this->token;
        }

        public function setToken() {
            $token = get_post_meta($this->id,'scb_token');
            $this->token = $token[0];
        }

        public function getkey() {
            return $this->key;
        }

        public function setKey() {
            $key = get_post_meta($this->id,'scb_key');
            $this->key = $key[0];
        }

        public function getSender() {
            return $this->sender;
        }

        public function setSender($sender) {
            $this->sender = $sender;
            $this->saveSender($sender);
        }

        public function getResponseString() {
            return wp_json_encode($this->getResponse());
        }

        public function getResponseForceString($user, $message) {
            $this->sender = $user;
            return wp_json_encode($this->getResponse($message));
        }

        public function getResponse($message = false) {
            if($message) {
                $elaborated = array(
                                "text"=>$message
                            );
            }
            else $elaborated = $this->elaborateResponse();

            return array(
                "recipient" => array(
                    "id" => $this->getSender()
                ),
                "message" => $elaborated
            );
        }

        public function getMessage() {
            return $this->message;           
        }
        
        public function setMessage($message) {
            $this->message = $message;
        }

        public function getMessageP() {
           return $this->message_p;
        }
        
        public function setMessageP($messageP) {
            $this->message_p = $messageP;
        }

        public function getMessengerbotDetails() {
            return $this->command_map;
        }

        public function setMessengerbotDetails() {
            $messengerbotDetails= get_post_meta($this->id,'messengerbotDetails');

            foreach($messengerbotDetails[0] as $one) {

                if(!array_key_exists('response_cat',$one)) $one["response_cat"] = '';
                if(!array_key_exists('response_tag',$one)) $one["response_tag"] = '';

                $this->command_map[strtolower($one["command"])]= array(
                    "response" => $one["response"],
                    "response_type" => $one["response_type"],
                    "response_cat" => $one["response_cat"],
                    "response_tag" => $one["response_tag"],
                );
            }
        }

        public function sendResponse() {
            
        }
    }

?>