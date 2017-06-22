<?php
function pixflow_page_add_meta_box() {
    $screens = array( 'page' );

    foreach ( $screens as $screen ) {
        add_meta_box('customizer',esc_attr__('customizer Options','massive-dynamic'),'pixflow_page_in_customize_callback',$screen,'normal','high');
    }
}

function pixflow_page_save_meta_box_data($post_id ) {


    if ( ! isset( $_POST['page_meta_box_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['pixflow_page_meta_box_nonce'], 'pixflow_page_save_meta_box_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }


    if ( ! isset( $_POST['subtitle'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['subtitle'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'subtitle', $my_data );
}
add_action( 'save_post', 'pixflow_page_save_meta_box_data');
?>