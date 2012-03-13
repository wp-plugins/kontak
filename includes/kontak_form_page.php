<?php
$stroutput .= '<form action="" method="post" id="kontak_form" class="kontakformatform">
      <div class="divider">
          <label for="kf_name">Name *:</label>
          <input type="text" name="kf_name" id="kf_name" value = "'.(!empty($postVars['kf_name'])?$postVars['kf_name']:"").'" required />
      <div class="br">&nbsp;</div></div>
      <div class="divider">
          <label for="kf_email_address">Email Address *:</label>
          <input type="email" name="kf_email_address" id="kf_email_address" value = "'.(!empty($postVars['kf_email_address'])?$postVars['kf_email_address']:"").'" required />
      <div class="br">&nbsp;</div></div>
      <div class="divider">
          <label for="kf_website">Website:</label>
          <input type="url" name="kf_website" id="kf_website" value = "'.(!empty($postVars['kf_website'])?$postVars['kf_website']:"").'" />
      <div class="br">&nbsp;</div></div>
      <div class="divider">
          <label for="kf_message">Message *:</label><textarea cols="60" rows="6" name="kf_message" id="kf_message" required>'.(!empty($postVars['kf_message'])?$postVars['kf_message']:"").'</textarea>
      <div class="br">&nbsp;</div></div>';
      if(!empty($usecaptcha)){
          $stroutput .= '<div class="divider">
        <label for="kf_captcha">Type the code below *:</label>
        <input type="text" name="kf_captcha" id="kf_captcha" required /><div class="br">&nbsp;</div>
      </div>
      <div class="divider kontak_center">
        <img src="'.$src.'" id="kf_captcha" alt="img_captcha" />
        <div class="br">&nbsp;</div>
      </div>';
      }
      $stroutput .= '<p class="submit">
        <input type="submit" name="kf_submit" value="Submit" />
      </p>
      '.wp_nonce_field('kf-submit',"kontak-nonce")
      .'<input type="hidden" name="kf_pargum" value="'.$key.'" />'
      .'</form>';
