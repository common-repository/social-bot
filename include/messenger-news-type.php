<?php

    add_filter( "gettext", "change_publish_button", 10, 2 );

    function change_publish_button( $translation, $text ) {
        if ( "messenger_bot_news" == get_post_type()) {            
            if ( $text == "Publish" )
                return __('Send', 'social-bot');
            if ( $text == "Update" )
                return __('Resend', 'social-bot');
        }
            
        return $translation;
    }

    function on_all_status_transitions( $new_status, $old_status, $post ) {
        if ( $new_status == 'publish' ) {
            if ( "messenger_bot_news" == $post->post_type) {

                $messenger_post = get_post_meta($post->ID, 'scb_messenger_post', true);
                $messenger_message =  get_post_meta($post->ID, 'scb_news_message', true);
                $users =  explode("|",get_post_meta($messenger_post, 'scb_user_list', true));
                
                foreach ($users as $user) {
                    $bot = new scb_messenger_class($messenger_post);


                    $bot->sendResponse($user, $messenger_message, true);
                }
            }
        }
    }
    add_action(  'transition_post_status',  'on_all_status_transitions', 10, 3 );
   
    function scb_choice_messenger_bot( $post ) {
        include plugin_dir_path( __FILE__ ) . '../part/form_choice_messenger_bot.php';
    }

    function scb_register_choice_messenger_bot() {
        add_meta_box( 'scb-link', __( 'Messenger Bot', 'social-bot' ), 'scb_choice_messenger_bot', 'messenger_bot_news' );
    }
    add_action( 'add_meta_boxes', 'scb_register_choice_messenger_bot' );

    function scb_news_messenger_bot( $post ) {
        include plugin_dir_path( __FILE__ ) . '../part/form_news_messenger_bot.php';
    }

    function scb_register_news_messenger_bot() {
        add_meta_box( 'scb-news', __( 'Messenger Bot', 'social-bot' ), 'scb_news_messenger_bot', 'messenger_bot_news' );
    }
    add_action( 'add_meta_boxes', 'scb_register_news_messenger_bot' );


    add_action( 'save_post', 'scb_messengerbotnews_meta_save' );

    function scb_messengerbotnews_meta_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! isset( $_POST['scb_messengerbotnews_nonce'] ) || ! wp_verify_nonce( $_POST['scb_messengerbotnews_nonce'], 'scb_messengerbotnews_save' ) ) {
            return;
        }
    
        if ( isset( $_POST['messenger_post'] ) && isset( $_POST['scb_news_message'] ) ) {
            $list_post = scb_utilities::sanitizePostInput( $_POST['messenger_post'] );
            $messenge = scb_utilities::sanitizePostInput( $_POST['scb_news_message'] );
    
            update_post_meta( $post_id, 'scb_messenger_post', $list_post );
            update_post_meta( $post_id, 'scb_news_message', $messenge );
        }
    }
?>