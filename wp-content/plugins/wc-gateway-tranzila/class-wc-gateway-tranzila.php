<?php

if (class_exists('WC_Payment_Gateway')) :


/**
 * Tranzila Payment Gateway
 *
 * Provides a Tranzila Payment Gateway.
 *
 * @class 		WC_Gateway_Tranzila
 * @extends		WC_Payment_Gateway
 * @version		1.1.1
 * @author 		sgPlanwize
 * @link 		http://tranzila.com
 */

class WC_Gateway_Tranzila extends WC_Payment_Gateway
{
	
	const SUPERPLUGIN_URL_MANAGER = "http://superplug.in/remote-requests-manager/";
	
	const SLUG = "wc-gateway-tranzila";

	public function __construct(){
		
		//include the KLogger
		require_once 'Logger/KLogger.php';
		$this->log = new KLogger( plugin_dir_path(__FILE__) . 'Logger/tranzila-request-api.txt' , KLogger::INFO);
		
		$this->id 					= 'tranzila';
		$this->icon 				= plugins_url('images/Tranzila90x25.jpg', __FILE__);
		$this->method_title 		=  __('Tranzila', 'wc_gateway_tranzila');
		$this->method_description	= sprintf(__('If you have an %sTranzila account%s, this payment method allows you to collect payments via Tranzila.', 'wc_gateway_tranzila'), '<a href="http://www.Tranzila.com" title="Tranzila Site">', '</a>');
		
		$this->init_form_fields();
		$this->init_settings();
		
		$this->title 			= $this->get_option( 'title' );
		$this->description 		= $this->get_option( 'description' );
		
		
		add_action( 'woocommerce_update_options_payment_gateways_tranzila', array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_tranzila', array($this, 'sp_tranzila_form_page') );
		add_action( 'before_woocommerce_pay', array($this, 'tranzila_payment_failed_msg') );
		add_action( 'before_woocommerce_pay', array($this, 'sp_breakout_from_iframe') );
		add_action( 'woocommerce_thankyou_tranzila', array($this, 'sp_breakout_from_iframe'));
		add_action( 'woocommerce_admin_css', array($this, 'enqueue_admin_css'));
		
		// Payment listener/API hook
		add_action( 'woocommerce_api_wc_gateway_tranzila', array( $this, 'api' ) );
		
		//Plugin text domain
		load_plugin_textdomain('wc_gateway_tranzila' , false , '/wc-gateway-tranzila');
		
		if ( !$this->is_valid_for_use() ){
			$this->enabled = false;
		}
	
	}
	
	function api(){
		
		$string = $_SERVER['QUERY_STRING'];
		$query_string = urldecode($string);
		$query_string = html_entity_decode($query_string);
		$get = array();
		parse_str($query_string , $get );
		$query_string = remove_query_arg(array('wc-api', 'action'), $query_string);
		
		if ( isset($get['action']) && method_exists( $this , $get['action'] ) ){
			
			$this->{$get['action']}($query_string);
			
		}else{
			wp_die( "Tranzila Request Failure" );
		}
		
	}
	
	/**
	 * 
	 */
	function thankyou_page( $query_string ){
		
		$location = $this->get_return_url() . "?" . $query_string;
		wp_redirect($location);
		
	}
	
	function payment_failed_page( $query_string ){
		
		$location = get_permalink(woocommerce_get_page_id('pay')) . "?" . $query_string ;
		wp_redirect($location);
		
	}
	
	function is_valid_for_use(){
		
		//TODO check all use validation
		$tranzila_username = $this->get_option('tranzila_username');
		return !empty($tranzila_username) && self::is_valid_tranzila_username($tranzila_username);
		
	}
	
	/**
	 * Initialise Gateway Settings Form Fields
	 *
	 * @access public
	 * @return void
	 */
	function init_form_fields() {
		
		$this->form_fields = array(
				
				'enabled' => array(
						'title'	=> __( 'Enable/Disable', 'wc_gateway_tranzila' ),
						'type'	=> 'checkbox',
						'label'	=> __( 'Enable Tranzila Payment Gateway', 'wc_gateway_tranzila' ),
						'default' =>'no'
						),
				'title' =>array(
						'title'	=> __( 'Title', 'wc_gateway_tranzila' ),
						'type'	=> 'text',
						'description'	=> __( 'This controls the title which the user sees during checkout.', 'wc_gateway_tranzila' ),
						'default' =>__( 'Tranzila', 'wc_gateway_tranzila' ),
						'desc_tip' => true
						),
				'description' => array(
						'title'	=> __( 'Description', 'wc_gateway_tranzila' ),
						'type'	=> 'textarea',
						'description'	=>  __( 'This controls the description which the user sees during checkout.', 'wc_gateway_tranzila' ),
						'default' =>__( 'Pay via Tranzila', 'wc_gateway_tranzila' ),
						'desc_tip' => true
						),
				'tranzila_username' => array(
						'title' => __('Tranzia Username', 'wc_gateway_tranzila'),
						'type' => 'text',
						'description' => __('The username you have received from Tranzila', 'wc_gateway_tranzila'),
						'desc_tip' => true
						),
				'iframe' => array(
						'title' => __('iFrame Mode' , 'wc_gateway_tranzila'),
						'type' => 'checkbox',
						'label' => __( 'Enable Tranzila iFrame Checkout' , 'wc_gateway_tranzila' ),
						'default' => 'no',
						'class' => 'sp-iframe-checkbox',
						'description' => __('An iFrame module gives you the option of embedding a payment window inside your site without redirecting to another site' , 'wc_gateway_tranzila'),
						'desc_tip' => true
						),
				'nologo' => array(
						'title' => __('Hide security logos' , 'wc_gateway_tranzila'),
						'type' => 'checkbox',
						'label' => __('Hide security logos' , 'wc_gateway_tranzila'),
						'default' => 'no',
						'class' => 'sp-iframe-setting',
						'description' => __('This is important for your clients knowing they give credit card information in safe place' , 'wc_gateway_tranzila'),
						'desc_tip' => true
						),
				/*'trBgColor' => array(
						'title' => __('iFrame backgroung color' , 'wc_gateway_tranzila'),
						'type' => 'text',
						'label' => __('iFrame backgroung color' , 'wc_gateway_tranzila'),
						'class' => 'sp-iframe-setting'
						),
				'trTextColor' => array(
						'title' => __('iFrame text color' , 'wc_gateway_tranzila'),
						'type' => 'text',
						'label' => __('iFrame text color' , 'wc_gateway_tranzila'),
						'class' => 'sp-iframe-setting'
						),
				'trButtonColor' => array(
						'title' => __('iFrame button color' , 'wc_gateway_tranzila'),
						'type' => 'text',
						'label' => __('iFrame button color' , 'wc_gateway_tranzila'),
						'class' => 'sp-iframe-setting'
						),*/ //TODO Choose color box
				'lang' => array(
						'title' => __('iFrame language' , 'wc_gateway_tranzila'),
						'type' => 'select',
						'options' => array(
								'il'=> __('Hebrew' , 'wc_gateway_tranzila') ,
								'us'=>__('English' , 'wc_gateway_tranzila') ,
								'ru'=>__('Russian' , 'wc_gateway_tranzila') ,
								'es'=>__('Spanish' , 'wc_gateway_tranzila') ,
								'de'=>__('German' , 'wc_gateway_tranzila') ,
								'fr'=>__('French' , 'wc_gateway_tranzila') ,
								'jp'=>__('Japanese' , 'wc_gateway_tranzila') 
								),
						'label' => __('iFrame lang' , 'wc_gateway_tranzila'),
						'class' => 'sp-iframe-setting',
						'default' => 'heb'
						),
				'buttonLabel' => array(
						'title' => __('Button Text' , 'wc_gateway_tranzila'),
						'type' => 'text',
						'label' => __('Button Text' , 'wc_gateway_tranzila'),
						'class' => 'sp-iframe-setting',
						)
				
				);
		
		
	}
	
	/**
	 * Admin Options
	 *
	 * Setup the gateway settings screen for tranzila
	 *
	 * @access public
	 * @return void
	 */
	public function admin_options() { 
		
		global $woocommerce, $wcgt_software_license;
		
		$tip = __('This url has been randomly generated for your site. Do not share it with anybody except Tranzila!', 'wc_gateway_tranzila');
		$tip = '<img class="help_tip" data-tip="' . esc_attr( $tip ) . '" src="' . $woocommerce->plugin_url() . '/assets/images/help.png" height="16" width="16" />';
		$license_tip = __('An activated license key keeps your plugin up to date and opens up our priority support.', 'wc_gateway_tranzila');
		$license_tip = '<img class="help_tip" data-tip="' . esc_attr( $license_tip ) . '" src="' . $woocommerce->plugin_url() . '/assets/images/help.png" height="16" width="16" />';
		$images_url = plugins_url('images' , __FILE__);
		
		?>
		<div style="width: 50%; display: inline-block; vertical-align: top;" >
		
		<h3><img alt="Tranzila" src="<?php echo plugins_url( 'images/Tranzila.jpg' , __FILE__ )?>" style="width: 30%"></h3>
	
		<?php echo ( ! empty( $this->method_description ) ) ? wpautop( $this->method_description ) : ''; ?>
		
		<?php 
		$tranzila_username = $this->get_option('tranzila_username', '');
		if ( !$this->is_valid_tranzila_username($tranzila_username) ){		
			echo '<div class="error fade"><p><strong>Tranzila username is not set yet or invalid.<br/>Tranzila gateway would be unavailable unless you fix this issue.</strong></p></div>';		
		}
		
		?>
		
		<h3><?php _e('Settings', 'wc_gateway_tranzila')?></h3>
		
		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table>
		
		</div>
		
		<div style="width:40%; display:inline-block; margin: 4em; vertical-align: top;">
		
		
		<?php 
		//license
		ob_start();
		$wcgt_software_license->activation_form( 'wc-gateway-tranzila' ) ;
		$content = ob_get_clean();
		/*$content = '<table class="form-table">
		<tr valign="top">
		<th scope="row" style="width:30%"><label for="w2t_tranzila_license_key">' . __('License Key' , 'wc_gateway_tranzila' ) . ' </label>' . $license_tip . '</th>
		<td class="forminp">
		<fieldset><legend class="screen-reader-text"><span> ' . __('License Key' , 'wc_gateway_tranzila' ) . ' </span></legend>
		<input  type="password" name="w2t_tranzila_license_key" id="w2t_tranzila_license_key" style="" value="'. $current_license_key .'">
		<img id="w2t_state_img" src="' .$state_img_src. '" title="'. $state_title.'"></img>
		<input type="button" class="button-primary" value="' . __('Activate' , 'wc_gateway_tranzila') . '" style="float: right"></fieldset></td>
		</tr><tr><td></td><td id="w2t_license_response_msg" style="color: red"></td></tr>
		</table>';*/
		
		$this->print_meta_box('w2t_license_key_meta_box', __('Plugin License Key' , 'wc_gateway_tranzila'), $content);
		
		//Instructions
		$content = "<p>" . __('Before you start to use Tranzila, make sure you followed the instructions below:', 'wc_gateway_tranzila') . "</p>
			<ol style='overflow-x: scroll;'>
			<li>" . __(sprintf('Go to your %sTranzila account.%s', '<a href="https://direct.tranzila.com/merchant" title="Tranzila Account" target="_blank">', '</a>'), 'wc_gateway_tranzila'). "</li>
			<li>" . __('Fill in the appropriate fields as follows:', 'wc_gateway_tranzila') . "
			<table style='border-spacing: 10px;'>
				<tr><td>Success URL</td><td><input type='text' value='".$this->get_tranzila_success_url()."' disabled='disabled' class='disabled'></td></tr>
				<tr><td>Fail URL</td><td><input type='text' value='".$this->get_tranzila_failed_url() ."' disabled='disabled' class='disabled'></td></tr>
				<tr><td>Notify URL " .  $tip . "</td><td><input type='text' value='".$this->get_notify_url() ."' disabled='disabled' class='disabled'></td></tr>
				<tr><td>Return Method</td><td><strong>GET</strong><br/></td></tr>
			</table>
			</li>
			<li>" . __('Make sure to enter your Tranzila username in the field setting.', 'wc_gateway_tranzila') . "</li>
			<li>" . __('Enable tranzila payment gateway and you are ready to collect payments.', 'wc_gateway_tranzila') . "</li>
			</ol>";
		
		$this->print_meta_box('important_instructions_meta_box', 'Important Instructions', $content) ;
		
		?>
		
		<!-- <div id="sp_news_from_superplugin" class="scrollable-box" >
			<div class="scrollable-box-header"><div class="header"><?php //_e('More From SuperPlugin', 'wc_gateway_tranzila')?></div>
			<div class="scrollable-box-button content-hide"><a href="#sp_news_from_superplugin"></a></div></div>
			<div class="scrollable-box-content">
			<img alt="" src="<?php //echo plugins_url("images/nlw-promo.jpg", __FILE__)?>">
			</div>
		</div> -->
		
		</div>
		
		<?php 
		
		
		$code = "jQuery(document).ready(function($){
	
			var meta_box = $('#w2t_license_key_meta_box');
			
			meta_box.find('input[type=\"button\"]').click(function(){
				var img_url = '" . $images_url . "/loading.gif';
				var title = 'Loading';
				$('#w2t_license_response_msg').html('');
				$('#w2t_state_img').attr('src' , img_url);
				var security = '". wp_create_nonce('verify_license_key') ."';
				var license_key = meta_box.find('#w2t_tranzila_license_key').val();
				var data = { action:'w2t_verify_license_key' , security: security , license_key: license_key };
				$.post(ajaxurl, data, function(data, textStatus, jqXHR){
					var json;
					try{
						json = $.parseJSON( data );
					}catch(e){
						$('#w2t_license_response_msg').html('Could not connect to server. Please try again later.');
						return;
					}
					
					if (json.hasOwnProperty('is_valid') && json.is_valid){
						document.location.reload(true);
					}else{
						img_url = '" . $images_url . "/cross.png';
						title = 'Invalid';
						if ( !json.hasOwnProperty('is_valid') ){
							$('#w2t_license_response_msg').html('Invalid response message. Please try again later.');
							return;
						}
						$('#w2t_license_response_msg').html(json.msg);
					}
				} ).always(function(){
					$('#w2t_state_img').attr('src' , img_url).attr('title' , title);
				});
			});
			
			
			//Tranzila iframe checkbox
			var switch_iframe_settings =  function ( ){
				if ( $('.sp-iframe-checkbox:checked').length == 0 ){
					$('.sp-iframe-setting').parents('tr').hide();
				}else{
					$('.sp-iframe-setting').parents('tr').show();
				}
			};
			//Tranzila iframe checkbox
			switch_iframe_settings();
			$('.sp-iframe-checkbox').on('click' , switch_iframe_settings );
			
		});";
	
		//$woocommerce->add_inline_js( $code );
		
	}
	
	private function get_tranzila_success_url(){

		return add_query_arg( array( 'wc-api' => 'WC_Gateway_Tranzila' , 'action' => 'thankyou_page' ) ,  home_url( '/' ) );
		
	}
	private function get_tranzila_failed_url(){
	
		return add_query_arg( array( 'wc-api' => 'WC_Gateway_Tranzila' , 'action' => 'payment_failed_page' ) ,  home_url( '/' ) );
	
	}
	
	/**
	 * 
	 * @return boolean | array . False if connetction to server feild. Array response from server
	 */
	
	private static function verify_plugin_license($license){
	
	
		//return response array if success retrieve data and false otherwise.
		
		$slug = self::SLUG;
		$request = wp_remote_post(self::SUPERPLUGIN_URL_MANAGER."manager.php", array('body'=>array('action'=>'update_license_usage', 'slug'=>$slug, 'license'=>$license, 'site_url'=>get_bloginfo('url'), 'format'=>'serialize')));
		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){
			return maybe_unserialize($request['body']);
		}
		return false;
	}
	
	
	
	/**
	 * This function returns the notify url used to handle transaction response 
	 * 
	 * @return string The genarated url.
	 */
	function get_notify_url(){
		
		$signature = get_option('sp_tranzila_notify_url_signature');
		if ( empty( $signature ) ){
			$signature = wp_generate_password(12, false);
			update_option('sp_tranzila_notify_url_signature', $signature);
		}
		
		return add_query_arg( array( 'wc-api' => 'WC_Gateway_Tranzila' , 'action' => 'check_tranzila_response' , 'trnz_signature' => $signature ) ,  home_url( '/' ) );
		
	}	
	 
	/**
	 * Check if given username is valid tranzila username.
	 * @param string $username . Tranzila username to be checked
	 * @return boolean true | false . true if is valid username and false otherwise.
	 */
	private static function is_valid_tranzila_username($username){
		if (empty($username)){
			return false;
		}
	
		$response = wp_remote_head("https://direct.tranzila.com/" . $username ."/" , array('sslverify'=>false));
		if (!is_wp_error($response) && $response['response']['code']!=404 ){
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Process the payment and return the result
	 *
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	function process_payment( $order_id ){
		
		$order = new WC_Order( $order_id );
		
		//$order->update_status('processing', __('Awaiting tranzila payment', 'wc_gateway_tranzila'));
		
		$redirect_url = add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(woocommerce_get_page_id('pay'))));
		
		return array(
				'result' 	=> 'success',
				'redirect'	=> $redirect_url
				);
		
	}
	
	/**
	 * If this thankyou page came from tranzila iframe then breakout to top.
	 * 
	 * @return void
	 */
	function sp_breakout_from_iframe(){
		
		echo '<script type="text/javascript">
					if (top.location!= self.location) {
						top.location = self.location.href ;
					}
				</script>';	
		
	}
	
	/**
	 * 
	 */
	function tranzila_payment_failed_msg(){
		global $woocommerce;
		//Check if the session came from trannzial failed payment
		$order_key            = urldecode( $_GET[ 'order' ] );
		$order_id             = absint( $_GET[ 'order_id' ] );
		$order                = new WC_Order( $order_id );
		if ( $order->payment_method == 'tranzila' && $_GET['Response']!=000){
			$woocommerce->add_error( __('Tranzila payment failed. Please try again.', 'wc_gateway_tranzila') );
		}
		
	}
	
	/**
	 * Display the transition page to tranzila or tranzila iframe
	 * 
	 * @param int $order_id
	 * @return void
	 */
	function sp_tranzila_form_page( $order_id ){
		
		global $woocommerce;
	
		echo '<p>'.__( 'Thank you for your order, please click the button below to pay with Tranzila.', 'wc_gateway_tranzila' ).'</p>';
		
		echo $this->sp_generate_tranzila_form( $order_id );
		
		$iframe = $this->get_option('iframe', 'no') ;
		if ( $iframe == 'yes' ){
			
			echo $this->sp_generate_iframe();
			
		}else{
		
			if ( !isset($_GET['Response']) )	$this->add_autoredirect_to_tranzila();
			
		}
		
	}
	
	
	/**
	 * Add inline javascript which handle autoredirect to tranzila with appropriate message
	 * 
	 * @return void
	 */
	private function add_autoredirect_to_tranzila(){
		
		global $woocommerce;

		$woocommerce->add_inline_js( '
			jQuery("body").block({
					message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to Tranzila to make payment.', 'wc_gateway_tranzila' ) ) . '",
					baseZ: 99999,
					overlayCSS:
					{
					background: "#fff",
					opacity: 0.6
				},
				css: {
					padding:        "20px",
					zindex:         "9999999",
					textAlign:      "center",
					color:          "#555",
					border:         "3px solid #aaa",
					backgroundColor:"#fff",
					cursor:         "wait",
					lineHeight:		"24px",
					}
			});
				jQuery("#sp_submit_tranzila_payment_form").click();
		' );
		
	}
	
	/**
	 * Generate the tranzila form
	 *
	 * @param mixed $order_id
	 * @return string
	 */
	private function sp_generate_tranzila_form( $order_id ){
		global $woocommerce;
	
		$order = new WC_Order( $order_id );
	
		$tranzila_args = $this->sp_get_tranzila_args( $order );
	
		$tranzila_args_array = array();
	
		foreach ($tranzila_args as $key => $value){
			if (!empty($value))
				$tranzila_args_array[] = '<input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr($value).'" />';
		}
	
		$iframe = $this->get_option('iframe', 'no') ;
		$target = $iframe == 'yes' ? 'tranzila_iframe' : '_top' ;
		return '<form action="'.esc_url( $this->sp_get_tranizla_direct_url( $iframe=='yes' ) ).'" method="post" id="sp_tranzila_payment_form" target="'.$target.'">
		' . implode( '', $tranzila_args_array) . '
		<input type="submit" class="button alt" id="sp_submit_tranzila_payment_form" value="' . __( 'Pay via Tranzila', 'wc_gateway_tranzila' ) . '" />
		<a class="button cancel" href="'.esc_url( $order->get_cancel_order_url() ).'">'.__( 'Cancel order &amp; restore cart', 'wc_gateway_tranzila' ).'</a>
		</form>';
	
	}
	
	/**
	 * Get tranzila Args for passing
	 *
	 * @param mixed $order
	 * @return array
	 */
	private function sp_get_tranzila_args( $order ){
	
		global $woocommerce;
	
		$order_id = $order->id;
	
		$tranzila_args = array();
	
		$tranzila_args['sum'] = strval($order->get_order_total());
		$tranzila_args['company'] = $order->billing_company;
		$tranzila_args['contact'] = $order->billing_first_name ." ". $order->billing_last_name;
		$tranzila_args['email'] = $order->billing_email;
		$tranzila_args['phone'] = $order->billing_phone;
		$tranzila_args['address'] = $order->billing_address_1 . " " . $order->billing_address_2 ;
		$tranzila_args['city'] = $order->billing_city;
		$tranzila_args['remarks'] = $order->customer_note;
		$tranzila_args['key'] = $order->order_key;
		$tranzila_args['order'] = $order->id;
		$tranzila_args['order_id'] = $order->id;
	
		switch ( get_woocommerce_currency() ){
	
		case 'GBP':
			$currency = '826';
			break;
		case 'USD':
			$currency = '2';
			break;
		case 'EUR':
			$currency = '978';
			break;
		case 'ILS':
		default:
			$currency = '1';
			break;
	
		}
	
		$tranzila_args['currency'] = $currency;
		
		// Cart Contents
		$tranzila_args['pdesk'] = "";
		if ( sizeof( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( $item['qty'] ) {
		
					$item_name 	= $item['name'];
						
					$tranzila_args['pdesk'] .=  $item_name . "x" . $item['qty'] . "  " . $order->get_formatted_line_subtotal( $item, false ) . "<br>";
		
				}
			}
		}
		
		//args for iframe module
		$iframe = $this->get_option('iframe', 'no') ;
		if ( $iframe=='yes' ){
			if ( $this->get_option('nologo')=='yes' ) $tranzila_args['nologo'] = "1" ;
			$tranzila_args['trBgColor'] = $this->get_option('trBgColor');
			$tranzila_args['trTextColor'] = $this->get_option('trTextColor');
			$tranzila_args['trButtonColor'] = $this->get_option('trButtonColor');
			$tranzila_args['lang'] = $this->get_option('lang');
			$tranzila_args['buttonLabel'] = $this->get_option('buttonLabel');
		}
	
	
		return $tranzila_args;
	}
	
	/**
	 * Generate the tranzila iframe
	 * 
	 * @return string The iframe string
	 */
	private function sp_generate_iframe(){
		
		return '<iframe name="tranzila_iframe" id="tranzila_iframe" width="370" height="455" scrolling="no" style="border:none; "></iframe>';
		
	}
	
	/**
	 * Get Tranzila direct url (https://direct.tranzila.com/USERNAME)
	 *
	 * @param $iframe boolean Wheather to get the iframe url. Default to false
	 * @return string The direct url
	 */
	
	private function sp_get_tranizla_direct_url( $iframe=false ){
	
		$tranzila_username = $this->get_option('tranzila_username', '');
	
		$url = "https://direct.tranzila.com/" . $tranzila_username . "/";
		if ( $iframe ){
			$url .= "iframe.php";
		}
	
		return $url;
	}
	
	function enqueue_admin_css(){
		
		wp_enqueue_style('sp_admin_css', plugins_url('css/admin.css', __FILE__));
		
	}
	
	/**
	 * Check for Tranzila response
	 * 
	 * @return void
	 */
	function check_tranzila_response( $query_string ){
		
		@ob_clean();
		
		$this->log->LogInfo('Tranzila requset received.');
		
		$this->log->LogInfo('Server QUERY_STRING: ' .$query_string );
		$this->log->LogInfo('Server REMOTE_ADDR: ' . $_SERVER['REMOTE_ADDR'] );
		$this->log->LogInfo('Server REMOTE_PORT: ' . $_SERVER['REMOTE_PORT'] );
		$this->log->LogInfo('Server REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD'] );
		
		$trnz_variables = array();
		parse_str($query_string, $trnz_variables );
		
		if ( $this->check_tranzila_request_is_valid( $trnz_variables ) ) {
			
			header( 'HTTP/1.1 200 OK' );
			
			$this->log->LogInfo('Valid Tranzila Request.');
			
			if ( isset( $_POST['order_id'] ) ){
				$order = new WC_Order($_POST['order_id']);
				if (!empty($order->id)){
					$this->log->LogInfo("Appropriate order was located. Order ID: " . $_POST['order_id']);
					switch ( $_POST['Response'] ) {
						
						case '000': //Transaction was approved
							// Check order not already completed
							if ( $order->status == 'completed' ) {
								$this->log->LogInfo( 'Aborting, Order #' . $order->id . ' is already complete.' );
							}
							// Validate Amount
							if ( $order->get_total() != $_POST['sum'] ) {

								$this->log->LogInfo( 'Payment error: Amounts do not match (gross ' . $_POST['sum'] . ')' );
							
								// Put this order on-hold for manual checking
								$order->update_status( 'on-hold', sprintf( __( 'Validation error: Tranzila amounts do not match (sum %s).', 'wc_gateway_tranzila' ), $_POST['sum'] ) );
							
								exit;
							}
							// Store Tranzila Details
							if ( ! empty( $_POST['ConfirmationCode'] ) )
								update_post_meta( $order->id, __( 'Credit card company approval number' , 'wc_gateway_tranzila' ), $_POST['ConfirmationCode'] );
							if ( ! empty( $_POST['Tempref'] ) )
								update_post_meta( $order->id, __( 'ABS approval number' , 'wc_gateway_tranzila' ), $_POST['Tempref'] );
							if ( ! empty( $_POST['index'] ) )
								update_post_meta( $order->id, __( 'Transaction ID' , 'wc_gateway_tranzila' ), $_POST['index'] );
							
							// Payment completed
							$order->add_order_note( __( 'Tranzila payment completed', 'wc_gateway_tranzila' ) );
							$order->payment_complete();
							$this->log->LogInfo('Tranzila Payment Completed');
							
							do_action( "tranzila_transaction_approved", $order , $_POST );
							
						break;
						
						default:
							// Order failed
							$order->update_status( 'failed', sprintf( __( 'Payment failed. Tranzila response code: %s . Check your tranzila account for transaction ID #%s', 'wc_gateway_tranzila' ),  $_POST['Response'] , $_POST['index'] ) );
							$this->log->LogInfo('Tranzila Payment failed. Response code: ' . $_POST['Response'] );
							break;
					}
				}else{
					$this->log->LogInfo("No order was found. Order ID: " . $_POST['order_id']);
					exit;
				}
			}
			
			$this->log->LogInfo( 'REQUEST Arguments: ' . var_export( $_POST , true) );
			
			do_action( "valid_tranzila_notify_url_request", $_POST );
		
		} else {
		
			$this->log->LogInfo( 'Invalid Tranzila Request.' );
			
			wp_die( "Tranzila Request Failure" );
		
		}
		
		die(0);
		
	}
	
	function check_tranzila_request_is_valid( $trnz_variables ){
		
		if (isset($trnz_variables['trnz_signature']) ){
			$signature = get_option('sp_tranzila_notify_url_signature');
			if ( $signature==$trnz_variables['trnz_signature'] ){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Print out meta box as default wp metabox template
	 * @param string $meta_box_id
	 * @param string $title
	 * @param string $content
	 * @param string $meta_box_custom_class default to none custom class
	 * 
	 * @return void
	 */
	private function print_meta_box( $meta_box_id , $title, $content , $meta_box_custom_class = "" ){
		
		?>
		<div class="metabox-holder">
		<div  id="<?php echo $meta_box_id ?>" class="postbox <?php echo $meta_box_custom_class ?>">
			<h3 class="hndle"><span><?php echo $title ?></span></h3>
			<div class="inside">
				<?php echo $content ?>
			</div>
		</div>
		</div>
		<?php
		
	}


}

endif;	//End if class WC_Payment_Gateway exists

?>