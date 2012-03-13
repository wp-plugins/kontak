<?php
if ( defined('WP_UNINSTALL_PLUGIN'))
{
   include_once('includes/Kontak.php');
   delete_option( Kontak::option_key );
   delete_option( Kontak::option_key_emailaddress );
   delete_option( Kontak::option_key_recipient );
   delete_option( Kontak::option_key_from_emailaddress );
   delete_option( Kontak::option_key_use_captcha );
   global $wp_rewrite;
   $wp_rewrite->flush_rules();
}
/*EOF*/
