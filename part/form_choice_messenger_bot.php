<?php 
        $postID = $post->ID;
        $loop = new WP_Query( array( 'post_type' => 'messenger_bot') ); ?>


<select name="messenger_post" id="messenger_post">
<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
<?php 
	$postmetas = get_post_meta(get_the_ID());
        $post = get_post();
        $select = "";
        if($post->ID==get_post_meta($postID, 'scb_messenger_post', true)) $select ="selected";
?>
        <option value="<?php the_ID(); ?>" <?php echo esc_attr($select); ?>><?php echo the_title_attribute( 'echo=0' ); ?></option>
<?php endwhile; ?>
</select>
    
        