<?php
/**
 * Software Autoupdate file.
 * This file contains a class that handle the autoupdate mechanism includes comunication to superplug.in server
 * 
 *  @package superplugin_api
 *  @version 1.1
 */


if ( !class_exists('Sp_Software_Autoupdate') )	:

/**
 * Sp_Software_Autoupdate class
 * 
 * 
 * @access public
 *
 */
class Sp_Software_Autoupdate {
	
	const SUPERPLUGIN_API = "http://superplug.in/superplugin-api/";
	
	public $plugin_path;
	public $software_id;
	public $current_version;
	public $plugin_slug;
	public $slug;
	public $steady;
	public $checked ;
	public $license;
	public $plugin_url;
	public $email;
	
	
	function __construct($plugin_path , $software_id){
		
		$this->plugin_path = $plugin_path;
		$this->software_id = $software_id;
		$this->license = get_option( $software_id . "_license" , '' );
		$this->email = get_option( $software_id . "_activation_email" , '' );
		
		//Handle software autoupdate
		add_action('admin_init', array($this, 'software_autoupdate') );
		
		
	}
	
	public function software_autoupdate(){
		
		//Set the class public variables
		$plugin_data = get_plugin_data($this->plugin_path);
		$this->current_version = $plugin_data['Version'];
		$this->plugin_slug = plugin_basename($this->plugin_path);
		list ($t1, $t2) = explode('/', $this->plugin_slug);
		$this->slug = str_replace('.php', '', $t2);
		$this->steady = false;
		$this->plugin_url = $plugin_site_url;
		
		add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
		add_filter("plugins_api", array($this, "check_info"), 10, 3);
		
	}
	
	public function check_update($transient){
		if (!$this->steady){
			//$this->checked = isset($transient->checked[$this->plugin_slug]) ;
			$this->steady = true;
			return $transient;
		}
		if ( !isset($transient->response) ){
			return $transient;
		}
	
		$response = $this->get_remote_version();
		if ( $response && isset($response->data) ){
			$remote_version = $response->data;
		}
	
		if (!empty($remote_version) && version_compare($this->current_version, $remote_version, '<')){
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $remote_version;
			$obj->url = $this->plugin_url;
			$package = $this->get_download_link();
			if ( !empty($package) )
				$obj->package = $package;
			$transient->response[$this->plugin_slug] = $obj;
		}
		//var_dump($transient);
		return $transient;
	}
	
	function get_download_link(){
		$request = wp_remote_post(self::SUPERPLUGIN_API, array('body'=>array('controller'=>'Amazons3',  'action'=>'get_plugin_download_link', 'slug'=>$this->slug, 'license'=>$this->license, 'email'=>$this->email)));
		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){
			$body = json_decode($request['body']);
			if ($body && isset($body->success) ){
				return $body->data->download_link;
			}
		}
		return "";
	}
	
	function is_license_verify(){
		$request = wp_remote_post(self::SUPERPLUGIN_API, array('body'=>array('controller'=>'sp_license' , 'action'=>'check', 'software_id'=>$this->slug, 'license_key'=>$this->license, 'activation_email'=>$this->email)));
		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){
			$body = json_decode($request['body']);
			if ($body && isset($body->data) ){
				return $body->data;
			}
		}
		return false;
	}
	
	function get_remote_version(){
	
		$request = wp_remote_post(self::SUPERPLUGIN_API, array('body'=>array( 'controller' => 'Sp_plugin' , 'action'=>'get_version' , 'slug'=>$this->slug)));
		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){
			return json_decode($request['body']);
		}
		return false;
	}
	
	public function check_info( $false, $action, $arg ){
		
		if ($arg->slug === $this->slug) {
			$response = $this->getRemote_information();
			if ( $response && $response->success ){
				$obj = $response->data ;
				$sections = array();
				foreach( (array) $obj->sections as $index=>$value){
					$sections[$index] = base64_decode($value);
				}
				$obj->sections = $sections ;
				return $obj;
			}
		}
		return $false;
		
	}
	
	public function getRemote_information(){
		
		$request = wp_remote_post(self::SUPERPLUGIN_API, array('body'=>array('controller'=>'Sp_plugin' , 'action'=>'get_plugin_info', 'slug'=>$this->slug, 'license'=>$this->license, 'email'=>$this->email )));
		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){
			return json_decode($request['body']);
		}
		return false;
		
	}
	
	
}


endif;