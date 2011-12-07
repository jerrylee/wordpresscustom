<?php
// Activate plugins  I have already added to the plugins directory before uploading
activate_plugin( 'wp-seo.php', '', false, false ); activate_plugin( 'googleplusone.php', '', false, false ); activate_plugin( 'tweetmeme.php', '', false, false );

//Delete Hello World and Example Page
wp_delete_post( 1, true ); wp_delete_post( 2, true );

// Update Some WP Options to our liking 
update_option( 'default_comment_status', 'closed' ); update_option( 'default_ping_status', 'closed' ); update_option( 'permalink_structure', '/%postname%/' ); update_option( 'gmt_offset', '-7' );  
// Plugin specific settings 
update_option( 'bat_header', '1' ); update_option( 'bat_loop', '1' ); update_option( 'bat_sidebar', '1' );
?>