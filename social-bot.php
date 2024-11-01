<?php
   /*
   Plugin Name: Social Bot
   description: A plugin to create response for Facebook Messenger bot
   Version: 1.9.1
   Author: Matteo Enna
   Author URI: https://matteoenna.it/it/wordpress-work/
   Text Domain: social-bot
   License: GPL2
   */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    require_once (dirname(__FILE__).'/include/social-bot-class.php');

    $scb = new scb_social_bot();
    
?>
