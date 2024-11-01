<div class="scb_box">
    <style scoped>
        .scb_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .scb_field{
            display: contents;
        }
        .row-command {
            margin-bottom: 20px;
        }


        .textBlock{
            display: none;
        }

        .categoryBlock{
            display: none;
        }

        .tagBlock{
            display: none;
        }

        .output_text_sender .textBlock{
            display: block;
        }

        .output_category_sender .categoryBlock{
            display: block;
        }

        .output_tag_sender .tagBlock{
            display: block;
        }
        .helper {
            margin: 30px 0 10px;
            display: block;
        }

    </style>
    <p class="meta-options scb_field">
        <label for="scb_token">Token</label>
        <input id="scb_token"
            type="text"
            name="scb_token"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'scb_token', true ) ); ?>">
    </p>
    <p class="meta-options scb_field">
        <label for="scb_key">Key</label>
        <input id="scb_key"
            type="text"
            name="scb_key"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'scb_key', true ) ); ?>">
    </p>
    <p class="meta-options scb_field">
        <label for="scb_error_msg"><?php echo esc_html__('Error message', 'social-bot'); ?></label>
        <input id="scb_error_msg"
            type="text"
            name="scb_error_msg"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'scb_error_msg', true ) ); ?>">
    </p>
    <p class="meta-options scb_field">
        <label for="scb_delete_user_msg"><?php echo esc_html__('Message that subscribers can use to unsubscribe', 'social-bot'); ?></label>
        <input id="scb_delete_user_msg"
            type="text"
            name="scb_delete_user_msg"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'scb_delete_user_msg', true ) ); ?>">
    </p>
</div>
<span class="helper"><?php echo esc_html__('To create your own Facebook Messenger Bot you can do it on <a href="https://developers.facebook.com/apps/create/" target="_blank">the official Developer Facebook page', 'social-bot'); ?></a></span>
<script>
    function changetype(event) {
        const parent= event.target.parentNode

        parent.classList.remove('output_text_sender');
        parent.classList.remove('output_category_sender');
        parent.classList.remove('output_tag_sender');
        parent.classList.add(event.target.value);
    }
</script>
