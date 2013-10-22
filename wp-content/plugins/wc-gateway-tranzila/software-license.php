<?php
/**
 * Software License file.
 * This file contains a class that handle the license validation mechanism includes comunication to superplug.in server
 *
 *  @package superplugin_api
 *  @version 1.1.2
 */

if ( !class_exists('Sp_Software_License') )	:

class Sp_Software_License {
	
	
	const BASE_URL = 'http://superplug.in/superplugin-api/';
	
	public $base_url;
	public $site_url;
	public $plugin_path;
	public $software_id;
	
	public function __construct( $plugin_path , $software_id ) {
		
		$this->plugin_path = $plugin_path;
		$this->software_id = $software_id;
		
		$this->site_url = site_url();
		
		$this->base_url = add_query_arg( 'controller', 'sp_license', self::BASE_URL );
		
		//Activation ajax
		add_action('wp_ajax_sp_activate_license', array($this, 'ajax_activation') );
		
		register_activation_hook($this->plugin_path, array($this, 'register_license_validation_scheduler') );
		register_deactivation_hook($this->plugin_path, array($this, 'unregister_license_validation_scheduler') );
		
		add_action('license_validation', array($this, 'daily_license_validation') );
		
		//print plugin row
		add_action('after_plugin_row_'.$plugin_path , array($this, 'plugin_row') );
		
	}
	
	/**
	 * Output the activation form
	 * 
	 * @param string $software_id . The software key name as declared in superplugin shop
	 * @return void
	 */
	public function activation_form( $software_id ) {
		
		$current_license_key = get_option( $software_id . "_license" , '' );
		$current_activation_email = get_option( $software_id . "_activation_email" , '' );
		if ( !empty($current_license_key) &&  !empty($current_activation_email)){
			$tick_img_tag = '<img src="http://superplug.in/wp-content/uploads/2013/08/tick.png" title="Active" alt="Active">';
		}
		?>
		<style>
		#activation-form {
			max-width: 25em;
			padding: 4px;
		}
		
		#activation-form input {
			width: 80%;
		}
		</style>
		<div id="activation-form">
			<h4>Software License</h4>
			<p id="license-activation-msg"></p>
			<p>
				<label for="license_key">License Key: </label><br>
				 <input
					id="license_key" type="password" name="license_key"
					value="<?php echo $current_license_key ?>"><?php if (isset($tick_img_tag))	echo $tick_img_tag?></p>
			<p>
				<label for="activation_email">Activation Email: </label><br> <input
					id="activation_email" type="email" name="activation_email"
					value="<?php echo $current_activation_email ?>"><?php if (isset($tick_img_tag))	echo $tick_img_tag?></p>
			<input type="hidden" id="product_id" value="<?php echo $software_id?>">
			<input type="button" id="submit-activation-license"
				class="button submit" name="activate" value="Activate"><img
				id="loading-gif"
				src="http://superplug.in/wp-content/uploads/2013/08/loading.gif"
				style="display: none;">
		</div>
		<script>
					jQuery(document).ready(function($){
						$('#submit-activation-license').click(function(){
							$('#loading-gif').show();
							var license_key = $('#activation-form #license_key').val();
							var activation_email = $('#activation-form #activation_email').val();
							var product_id = $('#activation-form #product_id').val();
							var params = {action: 'sp_activate_license', license_key: license_key, activation_email: activation_email, product_id: product_id};
							$.post("<?php echo admin_url("admin-ajax.php")?>", params, function(data){
								$('#license-activation-msg').html(data);
							}).always(function(){$('#loading-gif').hide();});	
						});
					});
		
				</script>
		<?php
		
	}
	
	public function ajax_activation( ){
		if ( !empty($_POST['license_key']) && !empty($_POST['activation_email']) ){
			$license_key = $_POST['license_key'];
			$activation_email = $_POST['activation_email'];
			$product_id = $_POST['product_id'];
		}
		
		
		$response = $this->activation($license_key, $activation_email, $product_id);
		if ( $response ){
			if ( $response->activated ){
				update_option( $product_id . "_license" , $license_key );
				update_option( $product_id . "_activation_email" , $activation_email );
				echo '<div class="updated"><p>License Activated.<br>'.$response->message.'</p></div>';
			}else{
				echo '<div class="error updated"><p>'.$response->error.'</p></div>';
			}
			
		}else{
			echo "<p>There was an error while trying to activate the license. Please try again later.</p>";
		}
		
		die();
	}
	
	/**
	 * 
	 * @param string $license_key
	 * @param string $activation_email
	 * @param string $software_id
	 * @return stdClass | boolean . Return false if connection failed. Return stdClass with the following properties:
	 * 'activated' - true or false
	 * 'error' - exists just if activated == false and contain the error message
	 * 'message' - exists just if activated--true and contain message about activations remaining.
	 */
	public function activation($license_key, $activation_email, $software_id){
		
		$args = array(
				'action'     			=> 'activation',
				'activation_email'      => $activation_email,
				'license_key' 			=> $license_key,
				'software_id'  			=> $software_id,
				'instance' 	  			=> $this->site_url
		);
		
		$response = $this->execute_request( $args ) ;
		if ( $response ){
			$response = json_decode($response);
			return $response->success ? $response->data : false ;
		}
		return false;
	}
	
	/**
	 * Check if license is valid and active.
	 * 
	 * @param string $license_key. The license to be checked.
	 * @param string $activation_email. The email address associated with the license.
	 * @param string $product_id. The product id associated with the license.
	 * @return boolean. true on success or on failure comunication to the server and false on failure.
	 */
	public function check($license_key, $activation_email, $product_id){
		
		$args = array(
			'action'     		=> 'check',
			'activation_email'	=> $activation_email,
			'license_key' 		=> $license_key,
			'software_id'  		=> $product_id
		);
		
		$response = $this->execute_request( $args ) ;
		if ( $response ){
			$response = json_decode($response);
			return $response->success ? $response->data : true ;
		}
		return true;
		
	}
	
	/**
	 * Fire away the request
	 * 
	 * @param array $args . contain the arguments needed for the request
	 * @return json response object on success and false on failure
	 */
	private function execute_request( $args ) {
		$target_url = $this->base_url  ;
		
		$request = wp_remote_post( $target_url , array('timeout' => 20, 'body'=>$args ) );
		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){

			return $request['body'] ;

		}else{
			return false;
		}
	}
	
	/**
	 * 
	 * @param string $license_key
	 * @param string $activation_email
	 * @param string $instance
	 * @param string $product_id
	 * @return stdClass response. if succeed then $response->reset exsits otherwise request failed and  $reponse->error contains the error message
	 * return false if server connection error
	 */
	public function deactivation($license_key, $activation_email, $instance, $product_id){
		
		
		$args = array(
			'action'     		=> 'deactivation',
			'activation_email'	=> $activation_email,
			'license_key' 		=> $license_key,
			'instance'    		=> $instance,
			'software_id'  		=> $product_id
		);

		$response = $this->execute_request( $args ) ;
		if ( $response ){
			$response = json_decode($response);
			return $response->success ? $response->data : false ;
		}else{
			return false;
		}
		

	}
	
	/**
	 * 
	 * @param string $license_key
	 * @param string $activation_email
	 * @param string $product_id
	 * @return stdClass response. if succeed then $response->reset exsits otherwise request failed and  $reponse->error contains the error message
	 */
	public function activation_reset($license_key, $activation_email, $product_id){
		
		$args = array(
			'action'     		=> 'activation_reset',
			'activation_email'	=> $activation_email,
			'license_key' 		=> $license_key,
			'software_id'  		=> $product_id
		);

		$response = $this->execute_request( $args ) ;
		if ( $response ){
			$response = json_decode($response);
			return $response->success ? $response->data : false ;
			return $response ;
		}else{
			return false;
		}
		
	}
	
	public function daily_license_validation(){
		$current_license_key = get_option( $this->software_id . "_license" , '' );
		$current_activation_email = get_option( $this->software_id . "_activation_email" , '' );
		if ( !empty($current_license_key) &&  !empty($current_activation_email)){
			$response = $this->check($current_license_key, $current_activation_email, $this->software_id);
			if ( $response===false ){
				update_option( $this->software_id . "_license" , '' );
				update_option( $this->software_id . "_activation_email" , '' );
			}
		}
	}
	
	public function register_license_validation_scheduler(){
		wp_schedule_event(current_time('timestamp'), 'daily', 'license_validation');
	}
	
	public function unregister_license_validation_scheduler(){
		wp_clear_scheduled_hook('license_validation');
	}
	
	public function plugin_row(){
		
		$license = get_option( $this->software_id . "_license" );
		if ( !empty($license) ){
			$msg = "License Key is not set yet or invalid. Manage your license for automatic upgrades and supports." ;
			echo "<tr class='plugin-update-tr'><td colspan='3' class='plugin-update'><div class='update-message'>".$msg."</div></td></tr>";
		}
	}
	
}

endif;
