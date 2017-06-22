<?php
if ( in_array( 'mailchimp-for-wp/mailchimp-for-wp.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
    if(get_post_meta( get_the_ID(), 'subscribe-section', true)== 'show'){
       ?><div class="subscribe-section"> <?php
        $title = __('Newsletter','massive-dynamic');
        $subTitle = __('Sign up to receive news and updates. It only takes a click to unsubscribe.','massive-dynamic');
        echo do_shortcode('[md_subscribe subscribe_bgcolor="rgb(255, 255, 255)" subscribe_title="'.$title.'" subscribe_sub_title="'.$subTitle.'" subscribe_input_style="stroke" subscribe_input_skin="dark" subscribe_input_opacity="21" ]');
        ?></div><?php
    }
}

?>