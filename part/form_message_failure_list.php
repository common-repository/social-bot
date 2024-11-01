<style scoped>
    .container-error {
        display: block;
        float: left;
        width: 100%;
    }
    .container-error .single_error {
        width: 33%;
        float: left;
    }

</style>

<p class="meta-options scb_field">
        <label for="scb_message_failure_list"></label>
        <input id="scb_message_failure_list"
            type="text"
            name="scb_message_failure_list"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'scb_message_failure_list', true ) ); ?>">
    </p>
