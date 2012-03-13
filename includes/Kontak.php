<?php
/**
* Kontak
*
*/
class Kontak {
   const default_shortcode_name = '[kontak-web-form]';
   const option_key = 'kontak_form_shortcode';
   
   const default_from_emailaddress = 'name@yourdomain.com';
   const option_key_from_emailaddress = 'kontak_form_from_email';
   
   const default_emailaddress = 'name@yourdomain.com';
   const option_key_emailaddress = 'kontak_form_email';
   
   const default_recipient = 'Webmaster';
   const option_key_recipient = 'kontak_recipient';
   
   const default_subject = 'Message via Kontak form';
   const option_key_subject = 'kontak_subject';
   
   const default_use_captcha = 1;
   const option_key_use_captcha = 'kontak_use_captcha';
   
   const admin_menu_slug = 'kontak';
   
   const prepend= "captext_";
   private $captchafolder;
   
   function __construct(){
       $this->captchafolder = str_replace('includes/','',plugin_dir_path(__FILE__))."captchacodes";
   }
   
   public static function get_chunk($content=null)
   {
      $shortcode = get_option(self::option_key, self::default_shortcode_name);
      /* Run the input check. */
      if(false === strpos($content, $shortcode)) {
          return $content;
      }
      
      self::deletetxtfiles();
      
      $msg ="";
      $error = 0;
      $showform = 1;
      $usecaptcha = get_option(self::option_key_use_captcha, self::default_use_captcha);
      //check fields
      if(isset($_REQUEST['kontak-nonce'])){
          $nonce=$_REQUEST['kontak-nonce'];
          if (!wp_verify_nonce($nonce, 'kf-submit')){
              $msg = 'Verification failed, process timed out. Please try again.';
              $error++;
          }else{
              $postVars = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
              $required_fields = array('kf_name','kf_email_address','kf_message');
              if(!empty($usecaptcha)) array_push($required_fields,'kf_captcha');
              $errFields = array();
              foreach($required_fields as $fieldname){
                  if(empty($postVars[$fieldname])){
                      $error++;
                      $errFields[] = ucwords(str_replace(array('kf_','_'),'',$fieldname));
                  }
              }
              
              if(!filter_var($postVars['kf_email_address'],FILTER_VALIDATE_EMAIL)){
                  $error++;
                  $errFields[] = "E-mail address is invalid";
              }
              
              if(!empty($postVars['kf_website']) && !filter_var($postVars['kf_website'],FILTER_VALIDATE_URL)){
                  $error++;
                  $errFields[] = "URL for website is invalid";
              }
              
              if(!empty($usecaptcha)){
                  $key = self::get_captchakey($postVars['kf_pargum']);
                  if (empty($key) || ( isset($postVars['kf_captcha']) && $postVars['kf_captcha'] != $key)) {
                      $error++;
                      $errFields[] = "Invalid captcha code.";
                  }
              }
              
              if($error > 0){
                  $msg .= 'Required fields are blank or values are invalid.';
                  $msg .= '<ul>';
                  foreach($errFields as $val){
                      $msg .= '<li>'.$val.'</li>';
                  }
                  $msg .= '</ul>';
              }else{ // no errors; prepare email body
                  include_once('class.phpmailer.php');
                  $mail = new PHPMailer();
                  $sender = $postVars['kf_name'];
                  $body = "Sender: ".$sender." \n<br />";
                  $body .= "E-mail address: ".$postVars['kf_email_address']." \n<br />";
                  $body .= (!empty($postVars['kf_website'])?"Website: ".$postVars['kf_website']." \n<br />":'');
                  $body .= "Message: ".$postVars['kf_message']." \n<br />";
                  
                  $mail->AddReplyTo($postVars['kf_email_address'],$sender);
                  
                  $toaddress = get_option(self::option_key_emailaddress, self::default_emailaddress);
                  $recipient = get_option(self::option_key_recipient, self::default_recipient);
                  $mail->AddAddress($toaddress, $recipient);
                  
                  $fromaddress = get_option(self::option_key_from_emailaddress, self::default_from_emailaddress);
                  $mail->SetFrom($fromaddress,$sender);
                  
                  $mail->Subject = get_option(self::option_key_subject, self::default_subject);
                  $mail->MsgHTML($body);
                  if(!$mail->Send()) {
                      $error++;
                      $msg = "Your message was not sent. There has been a technical problem. Please try again later.";
                  } else {
                      $msg = "Your message was sent. Thank you!";
                      $showform = 0;
                  }
              }
          }
      }
      
      $stroutput = '<div class="kontak_msg">'.$msg.'</div>';
      if($showform){
          $key = self::set_captchakey();
          $src = plugins_url('captcha/captcha.php?p='.$key,dirname(__FILE__) );
          include('kontak_form_page.php');
      }
      return str_replace("$shortcode", $stroutput, $content);
   }
   
   private static function get_captchakey($param=""){
        $textFileNameSize = -9;
        $objSelf = new Kontak;
        $myFile = $objSelf->captchafolder.'/'.self::prepend.substr($param,$textFileNameSize).".txt";
        $key ="";
        if(file_exists($myFile)) $key = trim(file_get_contents($myFile));
        if(is_file($myFile)) unlink($myFile);
        return $key;
   }
   
   private static function set_captchakey($param=""){
        $captchaTextSize = 7;
        $textFileNameSize = -9;
        $objSelf = new Kontak;
        if(!is_dir($objSelf->captchafolder)){
            if(!mkdir ($objSelf->captchafolder))
                exit('Unable to create the folder.');
        }
        
        do {
            $md5Hash = md5( microtime( ) * mktime( ) );
            $md5Key = str_ireplace( array("1","a","e","i","l","o","u","O","0"), "", $md5Hash );
        } while( strlen( $md5Key ) < $captchaTextSize );
        $key = substr( $md5Key, 0, $captchaTextSize );
        
        $myFile = $objSelf->captchafolder.'/'.self::prepend.substr($md5Hash,$textFileNameSize).".txt";
        $fh = fopen($myFile, 'w') or die("can't open file");
        $stringData = $key;
        fwrite($fh, $stringData);
        fclose($fh);
        return $md5Hash;
   }
   
   public static function create_admin_menu()
    {
       add_menu_page( 
          'Kontak',                // page title
          'Kontak',                // menu title
          'manage_options',                // capability
          self::admin_menu_slug,             // menu slug
          'Kontak::get_admin_page' // callback       
       );
   }
   
   /**
   * Prints the administration page for this plugin.
   */
   public static function get_admin_page()
   {
      if ( !empty($_POST) && check_admin_referer('kontak_options_update','kontak_admin_nonce') )
      {
         update_option( self::option_key, stripslashes($_POST['shortcode_name']) );
         update_option( self::option_key_from_emailaddress, stripslashes($_POST['kontak_from_email']) );
         update_option( self::option_key_emailaddress, stripslashes($_POST['kontak_email']) );
         update_option( self::option_key_recipient, stripslashes($_POST['kontak_recipient']) );
         update_option( self::option_key_subject, stripslashes($_POST['kontak_subject']) );
         if(!isset($_POST['kontak_use_captcha']) || empty($_POST['kontak_use_captcha'])){
             $captcha = "";
         }else{
             $captcha = $_POST['kontak_use_captcha'];
         }
         update_option( self::option_key_use_captcha, $captcha );
         $msg = '<div class="updated"><p>Your settings have been <strong>updated</strong></p></div>';
      }
      $shortcode_name = esc_attr( get_option(self::option_key, self::default_shortcode_name) );
      $kontak_from_email = esc_attr( get_option(self::option_key_from_emailaddress, self::default_from_emailaddress) );
      $kontak_email = esc_attr( get_option(self::option_key_emailaddress, self::default_emailaddress) );
      $kontak_recipient = esc_attr( get_option(self::option_key_recipient, self::default_recipient) );
      $kontak_subject = esc_attr( get_option(self::option_key_subject, self::default_subject) );
      $kontak_use_captcha = esc_attr( get_option(self::option_key_use_captcha, self::default_use_captcha) );
      include('admin_page.php');
   }
   
   /**
   * The inputs here come directly from WordPress:
   * @param   array   $links - a hash in theformat of name => translation e.g.
   *      array('deactivate' => 'Deactivate') that describes all links available to a plugin.
   * @param   string   $file    - the path to plugin's main file (the one with the info header), 
   *      relative to the plugins directory, e.g. 'content-chunks/index.php'
   * @return   array    The $links hash.
   */
   public static function add_plugin_settings_link($links, $file)
   {
       if($file == "kontak/index.php"){
          $kontak_settings_link = sprintf('<a href="%s">%s</a>'
             , admin_url( 'options-general.php?page='.self::admin_menu_slug )
             , 'Settings'
          );
          array_unshift( $links, $kontak_settings_link );
       }
      return $links;
   }
   
   public static function load_scripts(){
       $src = plugins_url('css/kontak.css',dirname(__FILE__) );
       wp_register_style('kontak', $src);
       wp_enqueue_style('kontak');
   }
   
   private static function deletetxtfiles(){
       $textFileNameSize = -9;
        $objSelf = new Kontak;
        $prepend= "captext_";
        if ($handle = opendir($objSelf->captchafolder)) {
           while (false !== ($entry = readdir($handle))) {
               $fl = $objSelf->captchafolder.'/'.$entry;
               if(is_file($fl)){
                   $gap = time() - filemtime($fl);
                   if($gap > 600 && strstr($entry,$prepend)) @unlink($fl);
               }
           }
       }
   }
}
/*EOF*/
