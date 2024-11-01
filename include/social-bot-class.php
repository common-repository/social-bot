<?php

    require_once (dirname(__FILE__).'/utilities.php');
    require_once (dirname(__FILE__).'/settings-page.php');
    require_once (dirname(__FILE__).'/bot-class.php');
    require_once (dirname(__FILE__).'/post-type.php');
    require_once (dirname(__FILE__).'/messenger-bot-class.php');
    require_once (dirname(__FILE__).'/messenger-post-type.php');
    require_once (dirname(__FILE__).'/messenger-news-type.php');

    class scb_social_bot {

        public function __construct()
        {
            add_action( 'template_redirect', array($this,'scb_socialchatbot' ));

            if(get_option('social_bot_checkbox_messenger_enable')) {
                add_action( 'init', array($this,'scb_messenger_bot_post' ));
            }
            if(get_option('social_bot_checkbox_messengernews_enable')) {
                add_action( 'init', array($this,'scb_messenger_bot_news_post' ));
            }
        }

        public function scb_messenger_bot_post() {

            $messenger_bot = new scb_post_type('Messenger Bot','messenger_bot','dashicons-facebook-alt');
            $messenger_bot->createPostType(); 
        }

        public function scb_messenger_bot_news_post() {

            $messenger_bot = new scb_post_type('Messenger Bot News (BETA)','messenger_bot_news','dashicons-welcome-write-blog');
            $messenger_bot->createPostType(); 
        }
        
        public function scb_socialchatbot() {
            if(get_post_type()=='messenger_bot'){

                global $we;
                $we = new scb_messenger_class(get_post()->ID);
    
                $we->sendResponse();

            }
        }
    }



?>