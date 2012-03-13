<div class="wrap">
   <?php screen_icon("kontak");?>
   <h2>Kontak Administration</h2>    
   <?php if(isset($msg)) echo $msg; ?>
   <form action="" method="post" id="kontak_form">
      <h3><label for="shortcode_name">Shortcode Name</label></h3>
      <p>
      Define the shortcode that will be used to trigger the contact form. Example: [get-kontak-form]<br/>
          <input type="text" id="shortcode_name" name="shortcode_name" 
          value="<?php echo $shortcode_name ?>" />
      </p>
      
      <h3>
        <label for="kontak_from_email">FROM E-mail address</label>
      </h3>
      <p>
        Set the value of the email-address to be used for FROM header. Some shared hostings require that you use the same domain name.
          <br/>
          <input type="text" id="kontak_from_email" name="kontak_from_email" 
          value="<?php echo $kontak_from_email ?>" size="40" />
      </p>
      
      <h3>
        <label for="kontak_recipient">Recipient's name</label>
      </h3>
      <p>
          <input type="text" id="kontak_recipient" name="kontak_recipient" 
          value="<?php echo $kontak_recipient ?>" size="40" />
      </p>
      
      <h3>
        <label for="kontak_email">Recipient's E-mail address</label>
      </h3>
      <p>
        Set the value of the email-address wherein messages would be sent.
          <br/>
          <input type="text" id="kontak_email" name="kontak_email" 
          value="<?php echo $kontak_email ?>" size="40" />
      </p>
      
      <h3>
        <label for="kontak_subject">Subject</label>
      </h3>
      <p>
          <input type="text" id="kontak_subject" name="kontak_subject" 
          value="<?php echo $kontak_subject ?>" size="40" />
      </p>
      
      <h3>
        <label for="kontak_use_captcha">CAPTCHA</label>
      </h3>
      <p>
          <label><input type="checkbox" id="kontak_use_captcha" name="kontak_use_captcha" 
          value="1"
          <?php echo (isset($kontak_use_captcha) && !empty($kontak_use_captcha)?' checked="checked"':'');?> />
          &nbsp; Enable</label>
      </p>
      
      <p class="submit">
        <input type="submit" name="submit" value="Update" />
      </p>
      <?php wp_nonce_field('kontak_options_update','kontak_admin_nonce'); ?>       
   </form>   
</div>
