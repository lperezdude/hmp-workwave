<div class="wrap-workwave">
	<h1 class="heading-workwave-settings">Workwave Settings </h1>
	<?php settings_errors(); ?>
	<div id="ajax_loader" >
		<img src="<?php echo $this->plugin_url ?>/assets/loader.gif" alt="Loading">
	</div>
	<div class="workwave-body">
		<div id="workwave_activator">
			<span class="plugin-activated-text">Plugin Disabled</span>
			<span class="on-off-icon">
				<img class="for_disabled" src="<?php echo $this->plugin_url; ?>assets/ic-disabled.svg">
				
				<img class="for_enabled" style="display: none;" src="<?php echo $this->plugin_url; ?>assets/ic-enabled.svg">
			</span>
			<div class="plugin-status-desc for_disabled">
				Orders are NOT being synced to your workwave account. 
			</div>
			<div class="plugin-status-desc for_enabled" style="display: none;">
				Orders are being synced to your workwave account. 
			</div>
		</div>
		<div class="workwave_settings_form">
			<form id="workwave_settings_form" method="post">
				<div id="id_for_checkbox_enabled">
					<input type="checkbox" name="WorkWavePluginStatus">
				</div>
				<div>
					<label for="workwave_key">Workwave Key</label>
					<?php $workwave_key =  get_option( "WorkWave_workwave_key"); ?>
					<input type="text" name="workwave_key" value="<?php echo $workwave_key; ?>">
				</div>

				<div>
					<label for="teritory_id">Teritory ID</label>
					<?php $teritory_id =  get_option( "WorkWave_teritory_id"); ?>
					<input type="text" name="teritory_id" value="<?php echo($teritory_id) ?>">
				</div>
				
				<div class="">	
					<label for="order_status">Order Status</label>
					<select id="order_status_select" class="order-status-selectbox" name="order_status[]" multiple="multiple" disabled>
						<?php GetAllOrderStatuses(); ?>
						
					</select>
				</div>	
				<div class="responce-message-row">
					<div class="alert alert-success" role="alert" id="responce_success_div" style="display: none;">
					  <img  src="<?php echo $this->plugin_url; ?>assets/success.svg"> Settings Saved!
					</div>
					<div class="alert alert-danger" role="alert" id="responce_error_div"  style="display: none;"></div>
				</div>
				<div class="btns-container row">
					
						<div class="goBackBTNContainer">
							<a href="<?php echo get_site_url(); ?>/wp-admin/index.php">
								<button type="button" class="btn go-back" name="button">CANCEL</button>
							</a>
						</div>
			

					<div class="saveBtnContainer">
						<input type="submit" value="SAVE CHANGES">
					</div>

			    </div>

			</form>
		</div>
	</div>
	<div class="div-responce"></div>
</div>

<?php 
// GETTING ALL ORDER STATUSES
function GetAllOrderStatuses(){
	$order_status_arr =  get_option( "WorkWave_order_status_arr");
	$order_statuses = wc_get_order_statuses();
	foreach ($order_statuses as $order_status) {
		$order_status_val = strtolower(str_replace(" ", "-", $order_status));
		echo '<option value="'.$order_status_val.'"';
		if (in_array($order_status_val, $order_status_arr)) {
			echo " SELECTED ";
		}
		echo '>'.$order_status.'</option>';
	}
}
?>


<style type="text/css">
	.notice{
		display: none;
	}
	h1.heading-workwave-settings {
    padding: 50px 0px 0px;
    margin-bottom: 20px;
    font-size: 50px;
    font-weight: bold;
    color: #2e2e2e;
    margin-top: 0;
}
.responce-message-row {
    width: 50%;
}
input[type="submit"] {
    background-color: #ea2913;
    max-width: 30%;
    border: 0;
    font-weight: bold;
    color: #fff;
    font-size: 12px;
    border-color: #e63017;
    transition: all 0.15s ease-in-out;
     float: right;
    margin: 0;
}
.responce-message-row img {
        width: 8%;
    margin-top: -6px;
    padding-right: 15px;
    display: inline-block;
}

button.btn.go-back {
    height: 40px;
    border: none;
    font-size: 12px;
    cursor: pointer;
    display: inline-block;
    text-transform: inherit;
    font-weight: bold;
    padding: 0px 18px;
    border-radius: 2px;
    border-color: #c9ced6;
    background-color: #c9ced6;
    color: #fff;
    transition: all 0.15s ease-in-out;
    width: 60%;
    max-width: 60%;
}


.saveBtnContainer {
    width: 84.5%;
}

.btns-container.row {
    width: 50%;
    margin-left: 0px;
    margin-top: 40px;
}
.goBackBTNContainer {
    display: inline-block;
    width: 50%;
    float: left;
}

.saveBtnContainer {
    display: inline-block;
    width: 50%;
    float: right;
}
select#order_status_select {
    width: 50%;
    min-height: 200px;
    overflow: hidden;
}

select#order_status_select option {
    color: grey;
    padding: 5px;
}
span.plugin-activated-text {
    font-weight: bold;
    padding-right: 15px;
    padding-bottom: 5px;
    font-size: 16px;
    display: inline-block;
}

span.on-off-icon {
    display: inline-block;
    vertical-align: top;
}

.plugin-status-desc {
    font-size: 16px;
    color: grey;
    padding-bottom: 30px;
}

form#workwave_settings_form label,form#workwave_settings_form input {
    display: block;
}

form#workwave_settings_form div {
    margin-bottom: 15px;
}

form#workwave_settings_form input:disabled,form#workwave_settings_form select:disabled {
    background: #c8c8c833;
    cursor: not-allowed;
}
form#workwave_settings_form .saveBtnContainer input:disabled {
    background: #c9ced6;
}
button.btn.go-back:hover {
    background: grey;
}	
div#id_for_checkbox_enabled {
    display: none;
}
form#workwave_settings_form label {
    font-weight: bold;
    color: #505050;
    font-size: 15px;
}

form#workwave_settings_form input {
    border-radius: 2px;
    padding: 10px;
    max-width: 50%;
    width: 50%;
}
span.select2.select2-container.select2-container--default{
	width: 50% !important;
}
span.select2-selection.select2-selection--multiple{
	height: 100px;
}
.toplevel_page_hmp_workwave div#wpcontent {
    margin-left: 200px;
}

/*loader styling*/
div#ajax_loader {position: absolute;width: 111%;height: 100%;z-index: 15;top: 50%;left: 50%;margin: -100px 0 0 -150px;background: transparent;margin-left: -60px;}

.wrap-workwave {
    position: relative;
}

div#ajax_loader img {
    position: absolute;
    z-index: 15;
    top: 50%;
    left: 50%;
    margin: -100px 0 0 -150px;
    margin-left: -50px;
}
/*loader styling*/
</style>

<script type="text/javascript">

	// ONLOAD LOAD SAVED SETTINGS AND PLACE THEM
	<?php $pluginStatusEnabled =  get_option( "hmp_WorkWavePluginEnabledStatus"); ?>
	jQuery( document ).ready(function() {
	    var pluginStatusEnabled =  "<?php echo $pluginStatusEnabled; ?>"
	    if (pluginStatusEnabled == "true") {
	    	console.log("Plugin is Enabled!");
	    	jQuery( "span.on-off-icon" ).addClass('enabled');
			if(jQuery( "span.on-off-icon.enabled" ).length > 0 ){
				jQuery('div#id_for_checkbox_enabled input').prop('checked', true);
			}
			else{
				jQuery('div#id_for_checkbox_enabled input').prop('checked', false);
			}
	    	jQuery('span.plugin-activated-text').text('Plugin Enabled');
			jQuery('.for_enabled').show();
			jQuery('.for_disabled').hide();
			jQuery('select#order_status_select').prop('disabled', false);
			jQuery('form#workwave_settings_form input').prop('disabled', false);
	    }
	    else if (pluginStatusEnabled == "false") {
	    	console.log("Plugin is Disabled!");
	    	jQuery('span.plugin-activated-text').text('Plugin Disabled');
			jQuery('.for_disabled').show();
			jQuery('.for_enabled').hide();
			jQuery('select#order_status_select').prop('disabled', 'disabled');
			jQuery('form#workwave_settings_form input').prop('disabled', 'disabled');
	    }
	});

	// AJAX CALL FOR ON/OFF BTN
	jQuery( "span.on-off-icon" ).click(function() {
		jQuery("#ajax_loader").show();
		jQuery( "span.on-off-icon" ).toggleClass('enabled');
		if(jQuery( "span.on-off-icon.enabled" ).length > 0 ){
			jQuery('div#id_for_checkbox_enabled input').prop('checked', true);
		}
		else{
			jQuery('div#id_for_checkbox_enabled input').prop('checked', false);
		}
		var pluginStatusEnabled = jQuery('div#id_for_checkbox_enabled input').prop('checked');
		data = 'pluginStatusEnabled='+pluginStatusEnabled;
		data += '&action='+'action_WorkWavePluginStatusOnOff';
		jQuery.post('<?php echo admin_url("admin-ajax.php"); ?>',
		data, function(response) {
			if (response == "true") {
				console.log("Plugin Is Enabled");
				jQuery('span.plugin-activated-text').text('Plugin Enabled');
				jQuery('.for_enabled').show();
				jQuery('.for_disabled').hide();
				jQuery("#ajax_loader").hide();
				jQuery('select#order_status_select').prop('disabled', false);
				jQuery('form#workwave_settings_form input').prop('disabled', false);
			}
			else if (response == "false") {
				console.log("Plugin Is Disabled");
				jQuery('span.plugin-activated-text').text('Plugin Disabled');
				jQuery('.for_disabled').show();
				jQuery('.for_enabled').hide();
				jQuery("#ajax_loader").hide();
				jQuery('select#order_status_select').prop('disabled', 'disabled');
				jQuery('form#workwave_settings_form input').prop('disabled', 'disabled');
			}
		});
		return false;
	});
	// AJAX CALL FOR ON/OFF BTN

	// AJAX CALL FOR FORM SUBMITION 
	jQuery('form#workwave_settings_form').bind('submit', function() {
		jQuery("#ajax_loader").show();
	    var form = jQuery('form#workwave_settings_form');
	    var data = form.serialize();
	    data += '&action='+'action_WorkWaveSettingsSubmission';
	    console.log(data);
	    jQuery.post('<?php echo admin_url("admin-ajax.php"); ?>', data, function(response) {
	        if (response == "OK") {
	        	jQuery("#responce_success_div").show();
	        	jQuery("#responce_error_div").hide();
	        	jQuery("#ajax_loader").hide();
	        }
	        else{
	        	var new_responce = "<img  src='" + "<?php echo $this->plugin_url ?>" + "assets/error.svg'>" + response;
	        	jQuery("#responce_error_div").html(new_responce);
	        	jQuery("#responce_error_div").show();	
	        	jQuery("#responce_success_div").hide();
	        	jQuery("#ajax_loader").hide();

	        }
	    });
	  return false;
	});
	// AJAX CALL FOR FORM SUBMITION 


</script>