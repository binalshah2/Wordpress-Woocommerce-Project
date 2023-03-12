<?php
//echo get_the_ID();
if(get_the_ID()!="1435")
{
  if($_GET['username']=='')
  {

  }
  else
  {
    $readonly = 'readonly="true"';
  }
  
}
else
{
  //$onchange ="onchange='checkemail(this.value);'";
  $onchange ="";
}
?>
<?php
if(get_the_ID()=="1435")
{
  //print_r($_POST);
  if($_POST['t1number']!='' || $_POST['email']!='')
  {
    $style="style=display:block;";
    $checked = "checked";
  }
?>
    <div class="rcwrapper" <?php echo $style;?>>
      <div class="rc_moco_txt">Are you already a customer of Moco?</div>
          <div class="rc_moco_options">
            <label>Yes</label>
            <input type="radio" name="iscustomer" id="yes" onclick="chkradio(this.id);" checked>
            <label>No</label>
            <input type="radio" name="iscustomer" id="no" onclick="chkradio(this.id);">
      </div>
    </div>
    <div class="rform" id="rform" style="display: none;">
        <form name="register" id="register" method="post" action="" <?php do_action('wprp_register_form_tag');?>>
          <input type="hidden" name="option" value="wprp_user_register" />
          <input type="hidden" name="redirect" value="<?php echo sanitize_text_field( Register_Process::curPageURL() ); ?>" />
          <?php if($wprp_p->is_field_enabled('username_in_registration')){ ?>
              <?php
                  if($_REQUEST['username']=='')
                  {
                    $style="display:none";
                    $_REQUEST['username'] = "username";
                  }
              ?>
          <div class="reg-form-group" style="<?php echo $style;?>">
            <label for="username">
              <?php _e('Username','wp-register-profile-with-shortcode');?>
            </label>
            
            <input type="text" name="user_login" value="<?php  echo $_REQUEST['username'];?>" required title="<?php _e('Please enter username','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Username','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_login_field' );?> <?php echo $readonly; ?> />
          </div>
          <?php } ?>
          <div class="reg-form-group">
            <label for="useremail">
              <?php _e('User Email','wp-register-profile-with-shortcode');?>
            </label>
            <input type="email" name="user_email" value="<?php  echo $_REQUEST['email'];?>" required title="<?php _e('Please enter email','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('User Email','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_email_field' );?> <?php echo $readonly; ?> <?php echo $onchange;?> />
          </div>
          <?php if($wprp_p->is_field_enabled('displayname_in_registration')){ ?>
          <div class="reg-form-group">
            <label for="displayname">
              <?php _e('Company Name','wp-register-profile-with-shortcode');?>
            </label>
            <input type="text" name="display_name" value="<?php  echo $_REQUEST['company'];?>" <?php echo $wprp_p->is_field_required('is_displayname_required');?> title="<?php _e('Please enter display name','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Company Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_display_name_field' );?> <?php echo $readonly; ?> />
          </div>
          <?php } ?>
          <?php if($wprp_p->is_field_enabled('password_in_registration')){ ?>
          <div class="reg-form-group">
            <label for="password">
              <?php _e('Password','wp-register-profile-with-shortcode');?>
            </label>
            <input type="password" name="new_user_password" required placeholder="<?php _e('Password','wp-register-profile-with-shortcode');?>" title="<?php _e('Please enter password','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_new_user_password_field' );?>/>
          </div>
          <div class="reg-form-group">
            <label for="retypepassword">
              <?php _e('Retype Password','wp-register-profile-with-shortcode');?>
            </label>
            <input type="password" name="re_user_password" required title="<?php _e('Please re-enter password','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Retype Password','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_re_user_password_field' );?>/>
          </div>
          <?php } ?>
          <?php if($wprp_p->is_field_enabled('firstname_in_registration')){ ?>
          <div class="reg-form-group">
            <label for="firstname">
              <?php _e('First Name','wp-register-profile-with-shortcode');?>
            </label>
            <input type="text" name="first_name" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['first_name']);?>" <?php echo $wprp_p->is_field_required('is_firstname_required');?> title="<?php _e('Please enter first name','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('First Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_first_name_field' );?>/>
          </div>
          <?php } ?>
          <?php if($wprp_p->is_field_enabled('lastname_in_registration')){ ?>
          <div class="reg-form-group">
            <label for="lastname">
              <?php _e('Last Name','wp-register-profile-with-shortcode');?>
            </label>
            <input type="text" name="last_name" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['last_name']);?>" <?php echo $wprp_p->is_field_required('is_lastname_required');?> title="<?php _e('Please enter last name','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Last Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_last_name_field' );?>/>
          </div>
          <?php } ?>
          
          <?php if($wprp_p->is_field_enabled('userdescription_in_registration')){ ?>
          <div class="reg-form-group">
            <label for="aboutuser">
              <?php _e('About User','wp-register-profile-with-shortcode');?>
            </label>
            <textarea name="description" <?php echo $wprp_p->is_field_required('is_userdescription_required');?> title="<?php _e('Please enter about','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_description_field' );?>><?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['description']);?></textarea>
          </div>
          <?php } ?>
          <?php if($wprp_p->is_field_enabled('userurl_in_registration')){ ?>
          <div class="reg-form-group">
            <label for="website">
              <?php _e('Website','wp-register-profile-with-shortcode');?>
            </label>
            <input type="url" name="user_url" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['user_url']);?>" <?php echo $wprp_p->is_field_required('is_userurl_required');?> title="<?php _e('Please enter website url','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Website','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_url_field' );?>/>
          </div>
          <?php } ?>
          <?php do_action('wp_register_profile_subscription'); ?>
          <?php if($wprp_p->is_field_enabled('captcha_in_registration')){ ?>
          <div class="reg-form-group">
            <label for="captcha">
              <?php _e('Captcha','wp-register-profile-with-shortcode');?>
            </label>
            <?php $this->captcha_image();?>
            <input type="text" name="user_captcha" autocomplete="off" required title="<?php _e('Please enter captcha','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_captcha_field' );?>/>
          </div>
          <?php } ?>
          <?php $default_registration_form_hooks == 'Yes'?do_action('register_form'):'';?>
          <?php do_action('wp_register_profile_form');?>
          <div class="reg-form-group">
            <input name="register" type="submit" value="<?php _e('Register','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_register_form_submit_tag' );?>/>
          </div>
        </form>
    </div>
    

<div class="rcsearch" id="rcsearch">
    <div>Please input your Moco Account Number or Registered email address to retrieve your account details:</div>
    <form name="rcform" id="rcform" method="post">
      <div class="">
        <input type="text" name="t1number" id="t1number" placeholder="Enter Account Number" onkeypress="return validateNumber(event)"> OR 
        <input type="text" name="email" id="email" placeholder="Enter Email" value="<?php echo $_POST['email']; ?>">
        <input type="submit" name="search" value="Verify" onclick="return emailvalidation();">
        <input type="hidden" name="rvalue" id="rvalue">
        
      </div>
    </form>
</div>
<?php
    if($_POST['search']=='Verify')
    {
    ?>
        <?php
              
              global $wpdb;
              //print_r($_POST);
              if($_POST['email']!='')
              {
                $search = $_POST['email'];
                $query = "SELECT * FROM crn_moco_customers where email='".$search."'";
              }
              else
              {
                $search = $_POST['t1number'];
                $query = "SELECT * FROM crn_moco_customers where  T1Customer='".$search."'";
              }
              
              $result = $wpdb->get_results( $query );
              //print_r($result);
          ?>
          <?php
              if(empty($result))
              {
          ?>
                <div class="empty_rc_div">
                    Looks like you dont have account 
                    <a href="https://moco.bb/registration">Register</a>
                </div>
          <?php
              }
              else
              {
          ?>    <div class="rc_txt_msg" id="rc_txt_msg">
                  Thank you! Your account has been verified.
                  Please click link below to continue registration.
                </div>
                <div class="rc_click_register" id="rc_click_register">
                    <a href="https://moco.bb/registration?company=<?php echo $result[0]->ContactName; ?>&email=<?php echo $result[0]->Email; ?>&username=<?php echo $result[0]->MId; ?>">Click here to continue your web registration</a>
                </div>
          <?php
              }
          ?>
          
    <?php
    }
    ?>
<?php
}
else
{
?>
<form name="register" id="register" method="post" action="" <?php do_action('wprp_register_form_tag');?>>
  <input type="hidden" name="option" value="wprp_user_register" />
  <input type="hidden" name="redirect" value="<?php echo sanitize_text_field( Register_Process::curPageURL() ); ?>" />
  <?php if($wprp_p->is_field_enabled('username_in_registration')){ ?>
      <?php
                  if($_REQUEST['username']=='')
                  {
                    $style="display:none";
                    $_REQUEST['username'] = "username";
                  }
      ?>
  <div class="reg-form-group" style="<?php echo $style;?>">
    <label for="username">
      <?php _e('Username','wp-register-profile-with-shortcode');?>
    </label>
    
    <input type="text" name="user_login" value="<?php  echo $_REQUEST['username'];?>" required title="<?php _e('Please enter username','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Username','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_login_field' );?> <?php echo $readonly; ?> />
  </div>
  <?php } ?>
  <div class="reg-form-group">
    <label for="useremail">
      <?php _e('User Email','wp-register-profile-with-shortcode');?>
    </label>
    <input type="email" name="user_email" value="<?php  echo $_REQUEST['email'];?>" required title="<?php _e('Please enter email','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('User Email','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_email_field' );?> <?php echo $readonly; ?> <?php echo $onchange;?> />
  </div>
  <?php if($wprp_p->is_field_enabled('displayname_in_registration')){ ?>
  <div class="reg-form-group">
    <label for="displayname">
      <?php _e('Company Name','wp-register-profile-with-shortcode');?>
    </label>
    <input type="text" name="display_name" value="<?php  echo $_REQUEST['company'];?>" <?php echo $wprp_p->is_field_required('is_displayname_required');?> title="<?php _e('Please enter display name','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Company Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_display_name_field' );?> <?php echo $readonly; ?> />
  </div>
  <?php } ?>
  <?php if($wprp_p->is_field_enabled('password_in_registration')){ ?>
  <div class="reg-form-group">
    <label for="password">
      <?php _e('Password','wp-register-profile-with-shortcode');?>
    </label>
    <input type="password" name="new_user_password" required placeholder="<?php _e('Password','wp-register-profile-with-shortcode');?>" title="<?php _e('Please enter password','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_new_user_password_field' );?>/>
  </div>
  <div class="reg-form-group">
    <label for="retypepassword">
      <?php _e('Retype Password','wp-register-profile-with-shortcode');?>
    </label>
    <input type="password" name="re_user_password" required title="<?php _e('Please re-enter password','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Retype Password','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_re_user_password_field' );?>/>
  </div>
  <?php } ?>
  <?php if($wprp_p->is_field_enabled('firstname_in_registration')){ ?>
  <div class="reg-form-group">
    <label for="firstname">
      <?php _e('First Name','wp-register-profile-with-shortcode');?>
    </label>
    <input type="text" name="first_name" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['first_name']);?>" <?php echo $wprp_p->is_field_required('is_firstname_required');?> title="<?php _e('Please enter first name','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('First Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_first_name_field' );?>/>
  </div>
  <?php } ?>
  <?php if($wprp_p->is_field_enabled('lastname_in_registration')){ ?>
  <div class="reg-form-group">
    <label for="lastname">
      <?php _e('Last Name','wp-register-profile-with-shortcode');?>
    </label>
    <input type="text" name="last_name" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['last_name']);?>" <?php echo $wprp_p->is_field_required('is_lastname_required');?> title="<?php _e('Please enter last name','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Last Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_last_name_field' );?>/>
  </div>
  <?php } ?>
  
  <?php if($wprp_p->is_field_enabled('userdescription_in_registration')){ ?>
  <div class="reg-form-group">
    <label for="aboutuser">
      <?php _e('About User','wp-register-profile-with-shortcode');?>
    </label>
    <textarea name="description" <?php echo $wprp_p->is_field_required('is_userdescription_required');?> title="<?php _e('Please enter about','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_description_field' );?>><?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['description']);?></textarea>
  </div>
  <?php } ?>
  <?php if($wprp_p->is_field_enabled('userurl_in_registration')){ ?>
  <div class="reg-form-group">
    <label for="website">
      <?php _e('Website','wp-register-profile-with-shortcode');?>
    </label>
    <input type="url" name="user_url" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['user_url']);?>" <?php echo $wprp_p->is_field_required('is_userurl_required');?> title="<?php _e('Please enter website url','wp-register-profile-with-shortcode');?>" placeholder="<?php _e('Website','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_url_field' );?>/>
  </div>
  <?php } ?>
  <?php do_action('wp_register_profile_subscription'); ?>
  <?php if($wprp_p->is_field_enabled('captcha_in_registration')){ ?>
  <div class="reg-form-group">
    <label for="captcha">
      <?php _e('Captcha','wp-register-profile-with-shortcode');?>
    </label>
    <?php $this->captcha_image();?>
    <input type="text" name="user_captcha" autocomplete="off" required title="<?php _e('Please enter captcha','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_captcha_field' );?>/>
  </div>
  <?php } ?>
  <?php $default_registration_form_hooks == 'Yes'?do_action('register_form'):'';?>
  <?php do_action('wp_register_profile_form');?>
  <div class="reg-form-group">
    <input name="register" type="submit" value="<?php _e('Register','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_register_form_submit_tag' );?>/>
  </div>
</form>

<?php
}
?>


<link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
  <!-- Script -->
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' type='text/javascript'></script>

<div class="modal fade" id="empModal" role="dialog">
    <div class="modal-dialog">
 
     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">It looks like you are already registered with Moco by same E-mail Address. Please click on link below to continue your web registration</h4>
        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
      </div>
      <div class="modal-body">
 
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
     </div>
    </div>
</div>
<script type="text/javascript">
 
  function chkradio(rvalue)
  {
    //alert(rvalue);
    if(rvalue=="yes")
    {
      document.getElementById("rform").style.display="none";
      document.getElementById("rcsearch").style.display="block";
      document.getElementById("rvalue").value="yes";
      document.getElementById("rc_click_register").style.display="block";
      document.getElementById("rc_txt_msg").style.display="block";
      
    }
    else
    {
      document.getElementById("rform").style.display="block";
      document.getElementById("rcsearch").style.display="none";
      document.getElementById("rvalue").value="no";
      document.getElementById("rc_click_register").style.display="none";
      document.getElementById("rc_txt_msg").style.display="none";
    }
  }
  function validateNumber(e) 
  {
            const pattern = /^[0-9]$/;

            return pattern.test(e.key )
  }
  jQuery( "#email" ).change(function() {
  document.getElementById("t1number").value="";
  });
  jQuery( "#t1number" ).change(function() {
  document.getElementById("email").value="";
  });

  function emailvalidation()
  {
    var email = document.getElementById('email').value;
    var t1number = document.getElementById('t1number').value;
    if(email!='')
    {
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

      if (!filter.test(email)) 
      {
        alert('Please provide a valid email address');
        email.focus;
        return false;
      }
    }
    //alert(t1number.length);
    //alert(email.length);
    if(t1number.length == 0 &&  email.length == 0)
    {
      alert('Please enter either account number of email address');
      return false;
    }

    
  }
</script>
<script type="text/javascript">
  function checkemail(str)
  {
    jQuery.ajax({
    url: "<?php echo get_site_url();?>/wp-content/plugins/wp-register-profile-with-shortcode/view/frontend/check_availability.php",
    data:'email='+str,
    type: "POST",
    success:function(response){
            
                jQuery('.modal-body').html(response);

                jQuery('#empModal').modal('show'); 
           
      
          //jQuery("#user-id-availability-status").html(data);
    },
    error:function ()
      {
        
      }
    });
  }
</script>