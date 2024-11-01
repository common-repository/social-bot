<?php

    function scb_register_meta_boxes() {
        add_meta_box( 'scb-1', __( 'Config ', 'social-bot' ), 'scb_display_callback', 'messenger_bot' );
    }
    add_action( 'add_meta_boxes', 'scb_register_meta_boxes' );

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function scb_display_callback( $post ) {
        include plugin_dir_path( __FILE__ ) . '../part/form.php';
    }

    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID
     */
    function scb_save_meta_box( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }
        $fields = [
            'scb_token',
            'scb_key',
            'scb_error_msg',
            'scb_delete_user_msg'
        ];
        foreach ( $fields as $field ) {
            if ( array_key_exists( $field, $_POST ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
            }
        }
    }
    add_action( 'save_post', 'scb_save_meta_box' );

    // Adding the metaboxes
    add_action( 'add_meta_boxes', 'scb_add_messengerbot_meta' );

    /* Saving the data */
    add_action( 'save_post', 'scb_messengerbot_meta_save' );

    /* Adding the main meta box container to the post editor screen */
    function scb_add_messengerbot_meta() {
        add_meta_box(
            'messengerbot-details',
            'Messengerbot Details',
            'scb_messengerbot_details_init',
            'messenger_bot');
    }

    function scb_getOptionType($edit = null, $type = null, $incremental = '%1$s') {
        $array_select = array(
            'output_text_sender' => __('text', 'social-bot'),
            'output_all_sender' => __('Latest post', 'social-bot'),
            'output_category_sender' => __('Latest post of category', 'social-bot'),
            'output_tag_sender' => __('Latest post of tag', 'social-bot')
        );
        $option = '<select name="messengerbotDetails['.$incremental.'][response_type]" id="messengerbotDetails" onchange="changetype(event)">';
        foreach ($array_select as $value => $text) {
            $selected = '';
            if($type == $value && $edit) $selected = 'selected';
            $option .= '<option value="'.$value.'" '.$selected.'>'.$text.'</option>';
        }
        $option .= '</select>';

        return $option;
    }

    function scb_getOptionCategory($edit = null, $type = null, $incremental = '%1$s') {
        $array_select = scb_utilities::scb_getCategory();
        $option = '<select name="messengerbotDetails['.$incremental.'][response_cat]" class="categoryBlock" id="messengerbotCategoryDetails">';
        foreach ($array_select as $value => $text) {
            $selected = '';
            if($type == $value && $edit) $selected = 'selected';
            $option .= '<option value="'.$value.'" '.$selected.'>'.$text.'</option>';
        }
        $option .= '</select>';

        return $option;
    }

    function scb_getOptionTag($edit = null, $type = null, $incremental = '%1$s') {
        $array_select = scb_utilities::scb_getTag();
        $option = '<select name="messengerbotDetails['.$incremental.'][response_tag]" class="tagBlock" id="messengerbotTagDetails">';
        foreach ($array_select as $value => $text) {
            $selected = '';
            if($type == $value && $edit) $selected = 'selected';
            $option .= '<option value="'.$value.'" '.$selected.'>'.$text.'</option>';
        }
        $option .= '</select>';

        return $option;
    }

    /*Printing the box content */
    function scb_messengerbot_details_init() {
        global $post;
        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), 'messengerbot_nonce' );
        include plugin_dir_path( __FILE__ ) . '../part/messengerbot_meta_item.php';

    }

    /* Save function for the entered data */
    function scb_messengerbot_meta_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
        // Verifying the nonce
        if ( !isset( $_POST['messengerbot_nonce'] ) )
            return;

        if ( !wp_verify_nonce( $_POST['messengerbot_nonce'], plugin_basename( __FILE__ ) ) )
            return;
        // Updating the messengerbotDetails meta data
        $messengerbotDetails = scb_utilities::sanitizePostInput($_POST['messengerbotDetails']) ;
        
        update_post_meta($post_id,'messengerbotDetails',$messengerbotDetails);
    }

    /**
     * Register meta boxes.
     */
    function scb_register_message_failure_list_boxes() {
        add_meta_box( 'scb-2', __( 'Error list', 'social-bot' ), 'scb_display_message_failure_list_callback', 'messenger_bot' );
    }
    add_action( 'add_meta_boxes', 'scb_register_message_failure_list_boxes' );

    function scb_register_manage_error_boxes() {
        add_meta_box( 'scb-4', __( 'Manage errors', 'social-bot'), 'scb_display_manage_error_callback', 'messenger_bot' );
    }
    add_action( 'add_meta_boxes', 'scb_register_manage_error_boxes' );

    function scb_register_user_list_boxes() {
        add_meta_box( 'scb-3', __( 'Users list', 'social-bot' ), 'scb_display_user_list_callback', 'messenger_bot' );
    }
    add_action( 'add_meta_boxes', 'scb_register_user_list_boxes' );

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function scb_display_message_failure_list_callback( $post ) {
        include plugin_dir_path( __FILE__ ) . '../part/form_message_failure_list.php';
    }

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function scb_display_user_list_callback( $post ) {
        include plugin_dir_path( __FILE__ ) . '../part/form_user_list.php';
    }

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function scb_display_manage_error_callback( $post ) {
        include plugin_dir_path( __FILE__ ) . '../part/form_manage_error.php';
    }

    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID
     */
    function scb_save_message_failure_list_box( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }
        $fields = [
            'scb_message_failure_list'
        ];
        foreach ( $fields as $field ) {
            if ( array_key_exists( $field, $_POST ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
            }
        }
    }
    add_action( 'save_post', 'scb_save_message_failure_list_box' );

    add_action( 'wp_ajax_add_command_script', 'scb_add_command_script' );

    function scb_add_command_script() {
        global $wpdb; 

        $c = intval( $_POST['counter'] );
        $command = sanitize_text_field( $_POST['command'] );
            echo '<div class="scb_box row-command output_text_sender">
            Command:<input type="text" name="messengerbotDetails['.esc_attr($c).'][command]" value="'.esc_attr($command).'" />
            Type:'.scb_getOptionType(false, false, esc_attr($c)).'<span class="textBlock">
            Response:</span> <textarea name="messengerbotDetails['.esc_attr($c).'][response]" rows="4" cols="50"  class="textBlock" ></textarea>
            <span class="categoryBlock">Categoty:</span>'.scb_getOptionCategory(false, false, $c).'
            <span class="tagBlock">Tag:</span>'.scb_getOptionTag(false, false, $c).'
            <p></p><a href="#" class="remove-package">Remove</a></div>';

        wp_die();
    }
?>
