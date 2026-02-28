<?php $company = $site->getCompanyDetails(); ?>

<?php

$contact_enabled = $site->getPageName('contact');

if ($contact_enabled == '404') {
    $site->sendTo($site->base);
}
?>

<?php 
	if ($_POST['rezgoAction'] == 'contact') {
		// NONCE CHECK
		check_admin_referer('rezgo-nonce');
	
		if ($_POST['hp_rezgo'] != '') {
			$bot_request = TRUE;
		} else {

      $site->cleanRequest();

      if ($site->exists(REZGO_CAPTCHA_PRIV_KEY)){
        
        // recaptcha v3 
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = REZGO_CAPTCHA_PRIV_KEY;
        $recaptcha_response = $_POST['recaptcha_response'];

        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        if ($recaptcha->score >= 0.75) {
          $recaptcha_fail = FALSE;
          $result = $site->sendContact();
        } else {
          $recaptcha_fail = TRUE;
        }

      } else {
          $result = $site->sendContact();
      }

		}
	} 
?>
<script type="text/javascript" src="<?php echo $site->path; ?>/js/jquery.selectboxes.js"></script><!-- .min not working -->
<script type="text/javascript" src="<?php echo $site->path; ?>/js/jquery.validate.min.js"></script>  
<?php if($site->exists(REZGO_CAPTCHA_PUB_KEY)) { ?>
	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo REZGO_CAPTCHA_PUB_KEY?>"></script>
<?php } ?>

<style>

  #agree_privacy_checkbox_wrap {
    position: relative;
    display:inline-block;
    background-color: #f9f9f9;
    border: 1px solid #d3d3d3;
    border-radius: 3px;
    box-shadow: 0px 0px 4px 1px rgba(0,0,0,0.08);
    padding: 10px 22px 10px 12px;
  }
  #agree_privacy_checkbox_wrap table td {
    vertical-align: middle;
  }
  #agree_privacy {
    position: relative;
    display: inline-block;
    padding: 0;
    margin: 0 10px 0 0;
  }
  #agree_privacy_checkbox_wrap label {
    position: relative;
    display: block;
    padding: 0;
    font-size: 13px;
  }

</style>


<div class="container-fluid">
  <div class="rezgo-content-row">
    <h1 id="rezgo-contact-head">Contact Us</h1>
    <div id="rezgo-about-content"><?php echo $site->getPageContent('contact'); ?></div>
    <?php if ($result->status == 1 && $bot_request !== TRUE) { ?>
    <script type="text/javascript">
				jQuery(document).ready(function($){
          parent.scrollTo(0,0);
				});
			</script>
    <div class="row rezgo-form-group">
      <div id="contact_success" class="alert alert-success">Thank you for your message.</div>
    </div>
    <?php } else { ?>
    <div class="row rezgo-form-group" id="rezgo-contact-form">
      <form class="form-horizontal" id="contact_form" role="form" method="post" target="_self">
        <input type="hidden" name="rezgoAction" value="contact" />
        <div class="form-group">
          <label for="contact_fullname" class="col-sm-2 control-label">Name</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="contact_fullname" placeholder="Full Name" required name="full_name" value="<?php echo sanitize_text_field($_REQUEST['full_name']); ?>" />
          </div>
        </div>
        <div class="form-group">
          <div class="required-group">
            <label for="contact_email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-4">
              <input type="email" class="form-control" id="contact_email" placeholder="Email" required name="email" value="<?php echo sanitize_text_field($_REQUEST['email']); ?>" />
            </div>
          </div>
          <label for="contact_phone" class="col-sm-2 control-label">Phone</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" id="contact_phone" placeholder="Phone Number" name="phone" value="<?php echo sanitize_text_field($_REQUEST['phone']); ?>" />
          </div>
        </div>
        <div class="form-group">
          <label for="contact_address" class="col-sm-2 control-label">Address</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="contact_address" placeholder="123 My Street" name="address" value="<?php echo sanitize_text_field($_REQUEST['address']); ?>" />
          </div>
        </div>
        <div class="form-group">
          <label for="contact_city" class="col-sm-2 control-label">City</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="contact_city" placeholder="My City" name="city" value="<?php echo sanitize_text_field($_REQUEST['city']); ?>" />
          </div>
        </div>
        <div class="form-group">
          <label for="contact_state" class="col-sm-2 control-label">State</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" id="contact_state" placeholder="My State" name="state_prov" value="<?php echo sanitize_text_field($_REQUEST['state_prov']); ?>" />
          </div>
          <label for="" class="col-sm-2 control-label">Country</label>
          <div class="col-sm-4">
            <select class="form-control" id="contact_country" name="country">
              <?php 
								foreach ($site->getRegionList() as $iso => $country_name) { 
									echo '<option value="'.$iso.'"';

									if ($iso == $_REQUEST['country']) {
										echo ' selected';
									} elseif ($iso == $site->getCompanyCountry() && !$_REQUEST['country']) {
										echo ' selected';
									}

									echo '>'.ucwords($country_name).'</option>';
								}
								?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="contact_comment" class="col-sm-2 control-label">Comment</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="body" id="contact_comment" rows="8" wrap="on" required ><?php echo $_REQUEST['body']; ?></textarea>
            <input type="text" name="hp_rezgo" class="hp_rez" value="" />
          </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
              <div id="agree_privacy_checkbox_wrap" class="checkbox clearfix">
                <table>
                  <tr>
                    <td>
                      <input type="checkbox" class="checkbox" id="agree_privacy" name="agree_privacy" value="1" <?php echo (($_REQUEST['agree_privacy']) ? 'checked' : ''); ?> required />
                    </td>
                    <td>
                      <label for="agree_privacy" style="margin-bottom:0;">I have read and agree to the <?php echo $company->company_name; ?> <a href="javascript: void();" onclick="window.open('/<?php echo $_REQUEST['wp_slug']; ?>/privacy',null, 'height=576,width=1024,resizable=no,scrollbars=yes,status=no,toolbar=no,menubar=no,location=no')" title="Privacy Policy" id="privacy_link">Privacy Policy.</a></label>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>  

        <?php if($site->exists(REZGO_CAPTCHA_PUB_KEY)) { ?>	
					<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
				<?php } ?> 

        <?php wp_nonce_field('rezgo-nonce'); ?>
        <div id="contact-submit-container" class="col-sm-3 col-sm-offset-9 col-xs-12">
          <input type="submit" class="btn btn-primary btn-lg btn-block" value="Send Request" />
        </div>
      </form>
    </div>
    <?php } ?>
    <?php $company = $site->getCompanyDetails(); ?>
    <div class="rezgo-content-row" id="rezgo-contact-address">
      <div class="col-sm-12 col-md-3">
        <address>
        <h3><?php echo $company->company_name; ?></h3>
        <span><?php echo $company->address_1; ?> <?php echo $company->address_2; ?></span> <br />
        <span><?php echo $company->city; ?>, </span>
        <?php if ($site->exists($company->state_prov)) { ?>
        <span><?php echo $company->state_prov; ?>, </span>
        <?php } ?>
        <span><?php echo $site->countryName($company->country); ?></span> <br />
        <span><?php echo $company->postal_code; ?></span> <br />
        <br />
        <?php if ($site->exists($company->phone)) { ?>
        <span>Phone: <?php echo $company->phone; ?></span> <br />
        <?php } ?>
        <?php if ($site->exists($company->fax)) { ?>
        <span>Fax: <?php echo $company->fax; ?></span> <br />
        <?php } ?>
        <span>Email: <?php echo $company->email; ?></span>
        <?php if ($site->exists($company->tax_id)) { ?>
        <br />
        <span>Tax ID: <?php echo $company->tax_id; ?></span>
        <?php } ?>
        </address>
      </div>
      <div class="col-sm-12 col-md-9">
				<?php if (GOOGLE_API_KEY != '' && $company->map->lat != '' && $company->map->lon != '') { ?>
				<?php 
          if (!$site->exists($company->map->zoom)) { 
            $map_zoom = 6; 
          } else { 
            $map_zoom = $company->map->zoom; 
          }
        ?>
				<div class="rezgo-map" id="rezgo-company-map">
          <iframe width="100%" height="500" frameborder="0" style="border:0;margin-bottom:0;margin-top:-105px;" src="https://www.google.com/maps/embed/v1/place?key=<?php echo GOOGLE_API_KEY?>&maptype=roadmap&q=<?php echo $company->map->lat?>,<?php echo $company->map->lon?>&center=<?php echo $company->map->lat?>,<?php echo $company->map->lon?>&zoom=<?php echo $map_zoom?>"></iframe>
        </div>
				<?php } ?> 
      </div>
    </div>
    <script>
		
			jQuery(document).ready(function($) {

        <?php if ($recaptcha_fail == TRUE) { ?> 
           $('#contact-submit-container').append("<span class='help-block' style='color:#a94442'>Recaptcha failed, your message was not sent.</span>");
           console.log('failed recaptcha');
        <?php } ?> 

        <?php if($site->exists(REZGO_CAPTCHA_PUB_KEY)) { ?>
          grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo REZGO_CAPTCHA_PUB_KEY?>', {action: 'submit'}).then(function(token) {
              var recaptchaResponse = document.getElementById('recaptchaResponse');
              recaptchaResponse.value = token;
            });
          });
        <?php } ?>

				$.validator.setDefaults({
					highlight: function(element) {
						if ($(element).attr("name") == "email" ) {
							$(element).closest('.required-group').addClass('has-error'); // only highlight email
						} else {
							$(element).closest('.form-group').addClass('has-error');
						}
					},
					unhighlight: function(element) {
						if ($(element).attr("name") == "email" ) {
							$(element).closest('.required-group').removeClass('has-error'); // unhighlight email
						} else {
							$(element).closest('.form-group').removeClass('has-error');
						}
					},
					errorElement: 'span',
					errorClass: 'help-block',
					errorPlacement: function(error, element) {
						if (element.parent('.input-group').length) {
							error.insertAfter(element.parent());
						} else if (element.attr("name") == "agree_privacy") {
                error.insertAfter(element.parents('#agree_privacy_checkbox_wrap')); 
            }else {
							error.insertAfter(element);
						}
					}
				});

				$('#contact_form').validate({
					rules: {
						full_name: {
							required: true
						},
						email: {
							required: true,
							email: true
						},
						body: {
							required: true,
						},
            agree_privacy: {
              required: true,
            },
					},
					messages: {
						full_name: {
							required: "Please enter your full name"
						},
						email: {
							required: "Please enter a valid email address"
						},
						body: {
							required: "Please enter a comment"
						},
					}
				});
				
			});
		</script> 
  </div>
  <!-- // .rezgo-content-row --> 
</div>
<!-- // .rezgo-container --> 
