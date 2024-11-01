<?php 
function social_bot_settings_page() {
    // Contenuto della pagina di configurazione
    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Social Bot settings', 'social-bot').'</h1>';

    // Output del form di configurazione
    echo '<form method="post" action="options.php">';
    settings_fields('social_bot_options');
    do_settings_sections('social_bot');

    // Pulsante di submit
    submit_button('Salva impostazioni');
    echo '</form>';
    echo '</div>';
}

function social_bot_add_settings_page() {
    add_options_page(__('Social Bot settings', 'social-bot'), __('Social Bot configuration', 'social-bot'), 'manage_options', 'social_bot', 'social_bot_settings_page');
}
add_action('admin_menu', 'social_bot_add_settings_page');


function getMessengerBotConf() {
    // Registrazione delle opzioni di configurazione
    register_setting('social_bot_options', 'social_bot_checkbox_messenger_enable');
    register_setting('social_bot_options', 'social_bot_checkbox_messengernews_enable');
    // Aggiunta della sezione di configurazione
    add_settings_section('social_bot_messenger_section', __('Facebook Messenger Bot options', 'social-bot'), 'social_bot_section_messenger_callback', 'social_bot');

    // Aggiunta dei campi di configurazione
    add_settings_field('social_bot_checkbox_messenger_enable', __('Enable Facebook Messenger Bot', 'social-bot'), 'social_bot_checkbox_messenger_enable_callback', 'social_bot', 'social_bot_messenger_section');
    if(get_option('social_bot_checkbox_messenger_enable')) add_settings_field('social_bot_checkbox_messengernews_enable', __('Enable Facebook Messenger Bot News', 'social-bot'), 'social_bot_checkbox_messengernews_enable_callback', 'social_bot', 'social_bot_messenger_section');

}

function getTelegramBotConf() {
    register_setting('social_bot_options', 'social_bot_checkbox_telegram_enable');
    register_setting('social_bot_options', 'social_bot_checkbox_telegramnews_enable');
    // Aggiunta della sezione di configurazione
    add_settings_section('social_bot_telegram_section', __('Facebook Telegram Bot options', 'social-bot'), 'social_bot_section_telegram_callback', 'social_bot');

    // Aggiunta dei campi di configurazione
    add_settings_field('social_bot_checkbox_telegram_enable', __('Enable Telegram Bot', 'social-bot'), 'social_bot_checkbox_telegram_enable_callback', 'social_bot', 'social_bot_telegram_section');
    if(get_option('social_bot_checkbox_telegram_enable')) add_settings_field('social_bot_checkbox_telegramnews_enable', __('Enable Telegram Bot News', 'social-bot'), 'social_bot_checkbox_telegramnews_enable_callback', 'social_bot', 'social_bot_telegram_section');

    
}

function getDevBotConf() {
    register_setting('social_bot_options', 'social_bot_checkbox_option_var_dump');
    // Aggiunta della sezione di configurazione
    add_settings_section('social_bot_dev_section', __('Development settings', 'social-bot'), 'social_bot_dev_section_callback', 'social_bot');

    // Aggiunta dei campi di configurazione
    add_settings_field('social_bot_checkbox_var_dump', __('Debug by var_dump', 'social-bot'), 'social_bot_checkbox_var_dump_callback', 'social_bot', 'social_bot_dev_section');
    
}

function social_bot_register_settings() {
    getMessengerBotConf();
//    getTelegramBotConf();
    getDevBotConf();
}

add_action('admin_init', 'social_bot_register_settings');

// Callback per la sezione di configurazione
function social_bot_section_messenger_callback() {
    echo esc_html__('By clicking on "Enable Facebook Messenger Bot", there will be additional options.', 'social-bot');
}

// Callback per la checkbox 1
function social_bot_checkbox_messenger_enable_callback() {
    $option = get_option('social_bot_checkbox_messenger_enable');
    echo '<input type="checkbox" name="social_bot_checkbox_messenger_enable" value="1" ' . checked(1, $option, false) . '>';
}

// Callback per la checkbox 2
function social_bot_checkbox_messengernews_enable_callback() {
    $option = get_option('social_bot_checkbox_messengernews_enable');
    echo '<input type="checkbox" name="social_bot_checkbox_messengernews_enable" value="1" ' . checked(1, $option, false) . '>';
}

// Callback per la sezione di configurazione
function social_bot_section_telegram_callback() {
    echo esc_html__('By clicking on "Enable Telegram Bot", there will be additional options.', 'social-bot');
}

// Callback per la checkbox 1
function social_bot_checkbox_telegram_enable_callback() {
    $option = get_option('social_bot_checkbox_telegram_enable');
    echo '<input type="checkbox" name="social_bot_checkbox_telegram_enable" value="1" ' . checked(1, $option, false) . '>';
}

// Callback per la checkbox 2
function social_bot_checkbox_telegramnews_enable_callback() {
    $option = get_option('social_bot_checkbox_telegramnews_enable');
    echo '<input type="checkbox" name="social_bot_checkbox_telegramnews_enable" value="1" ' . checked(1, $option, false) . '>';
}

// Callback per la sezione di configurazione
function social_bot_dev_section_callback() {
    echo esc_html__('Select this option if you want to perform tests using var_dump. Useful if you use tools like Postman.', 'social-bot');
}

function social_bot_checkbox_var_dump_callback() {
    $option = get_option('social_bot_checkbox_option_var_dump');
    echo '<input type="checkbox" name="social_bot_checkbox_option_var_dump" value="1" ' . checked(1, $option, false) . '>';
}