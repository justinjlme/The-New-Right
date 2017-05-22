<?php
/**
 * Licenses Handler Functions
 *
 * @package     etruel\licenses\Functions
 * @since       1.0.0
 */


// Exit if accessed directly
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * Begin licenses and updates functions
 */
function make_me_feed_plugin_updater() {
	// retrieve our license key from the DB
	$license_key = trim( get_option( 'make_me_feed_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( 'https://etruel.com', __FILE__, array(
			'version' 	=> MAKE_ME_FEED_VER, 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => MAKE_ME_FEED_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Esteban Truelsegaard'  // author of this plugin
		)
	);
}
add_action( 'admin_init', 'make_me_feed_plugin_updater', 0 );



add_action('wpempro_licenses_forms', 'make_me_feed_license_page' );
function make_me_feed_license_page() {
	$license 	= get_option( 'make_me_feed_license_key' );
	$args = array(
		'license' 	=> $license, 		// license key (used get_option above to retrieve from DB)
		'item_name' => urlencode( MAKE_ME_FEED_ITEM_NAME ),	// name of this plugin
		'url'       => home_url(),
		'version' 	=> MAKE_ME_FEED_VER, 	// current version number
		'author' 	=> 'Esteban Truelsegaard'			// author of this plugin
	);

	$license_data = make_me_feed_check_license ($args);
	$status = $license_data->license ;  // the real status on etruel.com		
	if ( $status === 'site_inactive' ) $status = 'inactive';
	if ( $status === 'item_name_mismatch' ) $status = 'invalid';

	//$status 	= get_option( 'make_me_feed_license_status' );
	?>
	<div class="postbox ">
		<div class="inside">
			<h2><span class="dashicons-before dashicons-admin-plugins"></span> <?php _e('Make Me Feed License', WPeMatico::TEXTDOMAIN); ?></h2>
		<form method="post" action="options.php">
		<?php settings_fields('make_me_feed_license'); ?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e('License Key'); ?>
					</th>
					<td>
						<input id="make_me_feed_license_key" name="make_me_feed_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" /><br />
						<label class="description" for="make_me_feed_license_key"><?php _e('Enter your license key'); ?></label>
					</td>
				</tr>
				<?php if( false !== $license ) { ?>
					<tr style="vertical-align: middle;">
						<th scope="row" style="vertical-align: middle;">
							<?php _e('Activated for updates', 'make_me_feed'); ?>
						</th>
						<td>
							<?php wp_nonce_field( 'make_me_feed_nonce', 'make_me_feed_nonce' ); ?>
							<style type="text/css">
								.validcheck:before { content: "\2714";color:green;font-size: 40px;}
								.renewcheck:before { content: "\26A0";color:red;font-size: 30px;}
								.warningcheck:before { content: "\26A0";color:orange;font-size: 30px;}
							</style>
							<?php if( $status !== false && $status == 'valid' ) { ?>
									<span class="validcheck"> </span>
							<?php } ?>
							<strong><?php _e('Status', 'make_me_feed'); ?>:</strong> <?php _e( ucfirst($status), 'make_me_feed' ); ?>
							<?php if ( $status === 'invalid' || $status === 'expired' ): ?>
								<i class="renewcheck"></i>
							<?php elseif( $status === 'inactive' ): ?>
								<i class="warningcheck"></i>
							<?php endif; ?>
								<br/>
							<?php
							if ( is_object( $license_data ) ) :
								$currentActivations = $license_data->site_count;
								$activationsLeft = $license_data->activations_left;
								$activationsLimit = $license_data->license_limit;
								$expires = $license_data->expires;
								$expires = substr( $expires, 0, strpos( $expires, " " ) );

								// If the license key is garbage, don't show any of the data.
								if ( !empty($license_data->payment_id) && !empty($license_data->license_limit ) ) :
								?>
								<small>
									<?php if ( $status !== 'valid' && $activationsLeft === 0 ) : ?>
										<?php $accountUrl = 'http://etruel.com/my-account/?action=manage_licenses&payment_id=' . $license_data->payment_id; ?>
										<a href="<?php echo $accountUrl; ?>"><?php _e("No activations left. Click here to manage the sites you've activated licenses on.", 'make_me_feed'); ?></a>
										<br/>
									<?php endif; ?>
									<?php if ( strtotime($expires) < strtotime("+2 weeks") ) : ?>
										<?php $renewalUrl = esc_attr( MAKE_ME_FEED_STORE_URL . '/checkout/?edd_license_key=' . $license); ?>
										<a href="<?php echo $renewalUrl; ?>"><?php _e('Renew your license to continue receiving updates and support.', 'make_me_feed'); ?></a>
										<br/>
									<?php endif; ?>

									<strong><?php _e('Activations', 'make_me_feed'); ?>:</strong>
										<?php echo $currentActivations.'/'.$activationsLimit; ?> (<?php echo $activationsLeft; ?> left)
									<br/>
									<strong><?php _e('Expires on', 'make_me_feed'); ?>:</strong>
										<code><?php echo $expires; ?></code>
									<br/>
									<strong><?php _e('Registered to', 'make_me_feed'); ?>:</strong>
										<?php echo $license_data->customer_name; ?> (<code><?php echo $license_data->customer_email; ?></code>)
								</small>
								<?php endif; ?>
							<?php else: ?>
								<small><?php _e('Failed to get license information. This is a temporary problem. Check your internet connection and try again later.', 'make_me_feed'); ?></small>
							<?php endif; ?>
							<p></p>	
							<p><?php if($status !== false && $status == 'valid') { ?>
								<input type="submit" class="button-secondary" name="make_me_feed_license_deactivate" value="<?php _e('Deactivate License', 'make_me_feed'); ?>" style="vertical-align: middle;"/>
							<?php }else {	?>
								<input type="submit" class="button-secondary" name="make_me_feed_license_activate" value="<?php _e('Activate License', 'make_me_feed'); ?>"/>
							<?php } ?>
							</p>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php submit_button(); ?>

		</form>
		</div>
	</div>
	<?php
}

function make_me_feed_register_option() {
	// creates our settings in the options table
	register_setting('make_me_feed_license', 'make_me_feed_license_key', 'make_me_feed_sanitize_license' );
}
add_action('admin_init', 'make_me_feed_register_option');

function make_me_feed_sanitize_license( $new ) {
	$old = get_option( 'make_me_feed_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'make_me_feed_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
* this illustrates how to activate
* a license key
*************************************/

function make_me_feed_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['make_me_feed_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'make_me_feed_nonce', 'make_me_feed_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'make_me_feed_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( MAKE_ME_FEED_ITEM_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
	 	$response = wp_remote_post( esc_url_raw( add_query_arg( $api_params, MAKE_ME_FEED_STORE_URL ) ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'make_me_feed_license_status', $license_data->license );

	}
}
add_action('admin_init', 'make_me_feed_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function make_me_feed_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['make_me_feed_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'make_me_feed_nonce', 'make_me_feed_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'make_me_feed_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( MAKE_ME_FEED_ITEM_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
	 	$response = wp_remote_post( esc_url_raw( add_query_arg( $api_params, MAKE_ME_FEED_STORE_URL ) ), array( 'timeout' => 15, 'sslverify' => false ) );
		//$response = wp_remote_post( esc_url_raw( MAKE_ME_FEED_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'make_me_feed_license_status' );

	}
}
add_action('admin_init', 'make_me_feed_deactivate_license');


/************************************
* this illustrates how to check if a license key is still valid the updater does this for you,
* so this is only needed if you want to do something custom
*************************************/
function make_me_feed_check_license( $args ) {
		global $wp_version;
		$args['edd_action'] = 'check_license';
		$api_params = $args;
		// Call the custom API.
		$response = wp_remote_post( esc_url_raw( add_query_arg( $api_params, MAKE_ME_FEED_STORE_URL ) ), array( 'timeout' => 7, 'sslverify' => false ) );

		if ( is_wp_error( $response ) )
			return false;
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		return $license_data;
}
