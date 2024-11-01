<div id="messengerbot_meta_item">
        <?php

        //Obtaining the linked messengerbotdetails meta values
        $messengerbotDetails = get_post_meta($post->ID,'messengerbotDetails',true);
        $c = 0;
        if(!$messengerbotDetails) {
            $messengerbotDetails = array();
        }
        if ( count( $messengerbotDetails ) > 0 && is_array($messengerbotDetails)) {
            foreach( $messengerbotDetails as $messengerbotDetail ) {

                $response_cat = '';
                $response_tag = '';
                if(array_key_exists('response_cat',$messengerbotDetail)) $response_cat = $messengerbotDetail['response_cat'];
                if(array_key_exists('response_tag',$messengerbotDetail)) $response_tag = $messengerbotDetail['response_tag'];

                if ( isset( $messengerbotDetail['command'] ) || isset( $messengerbotDetail['response'] )  || isset( $messengerbotDetail['response_type'] ) ) {
                    printf( '<div class="scb_box row-command %3$s">
                    Command:<input type="text" name="messengerbotDetails[%1$s][command]" value="%2$s" />
                    Type: '.scb_getOptionType(true, $messengerbotDetail['response_type']).'
                    <span class="textBlock">Response:</span><textarea name="messengerbotDetails[%1$s][response]" class="textBlock" rows="4" cols="50" >%4$s</textarea>
                    <span class="categoryBlock">'.esc_html__('Category', 'social-bot').':</span>'.scb_getOptionCategory(true, $response_cat).'
                    <span class="tagBlock">'.esc_html__('Tag', 'social-bot').':</span>'.scb_getOptionTag(true, $response_tag).'
                    <p></p> <a href="#" class="remove-package">%5$s</a></div>', esc_attr($c), esc_attr($messengerbotDetail['command']), esc_attr($messengerbotDetail['response_type']), esc_attr($messengerbotDetail['response']), 'Remove' );
                    $c = $c +1;
                }
            }
        }

        ?>
    <span id="output-package"></span>
    <a href="#" class="add_package button add_command"><?php echo esc_html__('Add Messenger Bot', 'social-bot'); ?></a>
    <script>
        var $ =jQuery.noConflict();
        window.counter = <?php echo esc_attr($c+1); ?>;
        $(document).ready(function() {
            $(".add_command").click(function() {
                add_command (window.counter, '')
            });
    
            $(document.body).on('click','.remove-package',function() {
                $(this).parent().remove();
            });
        });

        function add_command (counter, command) {
                    var data = {
                        'action': 'add_command_script',
                        'counter': window.counter,
                        'command': Boolean(command)?command:''
                    };
                    
                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                    jQuery.post(ajaxurl, data, function(response) {
                        $('#output-package').append(response);
                    });
                window.counter++; 

        }

        </script>
    </div>    