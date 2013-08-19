<?php
/*
Plugin Name: WooCommerce Product Filter
Plugin URI: http://codenegar.com/woocommerce-ajax-product-filter/
Description: Advanced WooCommerce product filtering by category, attribute, meta
Author: Farhad Ahmadi
Version: 1.9.7
Author URI: http://codenegar.com/
*/

class Codenegar_woocommerce_product_filter {
	
	public $version = '20130801';
	public $path = '';
	public $url = '';
	public $text_domain = 'woocommerce-product-filter';
	public $options; // PHP stdClass
	public $security = 'woocommerce-product-filter';
	public $file = '';
	public $helper; // CodeNegar_wcpf_helper object
	public $html; // CodeNegar_wcpf_html object
	public $filter; // CodeNegar_wcpf_filter object
	public $is_localized = false; // flag for localization; we want to run it once
	
	function __construct() {
		$this->file = __FILE__;
		$this->path = dirname($this->file) . '/';
		$this->url = WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/'; 
		require_once($this->path . 'helper.php');
		require_once($this->path . 'html.php');
		require_once($this->path . 'filter.php');
		$this->helper = new CodeNegar_wcpf_helper();
		$this->html = new CodeNegar_wcpf_html();
		$this->filter = new CodeNegar_wcpf_filter();
	}
	
	public function version() {
		return $this->version;
	}
	
	public function plugins_loaded(){
		load_plugin_textdomain($this->text_domain, false, dirname(plugin_basename($this->file)) . '/languages/');
	}
	
	public function activate() {
		$options = get_option('codenegar_product_filter');
		$defaults = $this->helper->default_options();
		$merged = codenegar_parse_args($options, $defaults);
		update_option('codenegar_product_filter', $merged);
		update_option('woocommerce_redirect_on_single_search_result', 'no');
	}
	
	public function initialize() {
		$options = get_option('codenegar_product_filter');
		$defaults = $this->helper->default_options();
		$merged = codenegar_parse_args($options, $defaults);
		$this->options = $this->helper->array_to_object($merged);
		$this->helper->register_sidebars();
		$this->helper->register_shortcode();
		add_filter('widget_text', 'do_shortcode'); // Enables using shortcode in text widget
	}
	
	public function show_admin_menu(){
		include $this->path . 'options.php';
	}
	
	public function admin_menu(){
		add_submenu_page('options-general.php', __('Woocommerce Product Filter', $this->text_domain), __('Product Filter', $this->text_domain), 'administrator', 'woocommerce_product_filter', array(&$this, 'show_admin_menu'));
	}
	
	public function register_frontend_assets(){
		// Add frontend assets in footer
		wp_register_script('codenegar-ajax-search-migrate',  $this->url . 'js/migrate.js', array('jquery'), false, true);
		wp_register_script('codenegar-wcpf-frontend', $this->url . 'js/script.js', array(), false, true);
		wp_register_script('codenegar-wcpf-history-js', $this->url . 'js/history.js', array(), false, true); // supports HTML5 pushState and HTML4 hashtags
		wp_register_style('codenegar-wcpf-frontend-style', $this->url . 'css/style.css');
	}
	
	public function register_admin_assets(){
		// Add admin assets in footer
		wp_register_script('codenegar-admin-option-widget', $this->url . 'js/widget.js', array(), false, true);
		wp_register_script('codenegar-admin-option-json', $this->url . 'js/json.js', array(), false, true);
		wp_register_script('codenegar-admin-option-script', $this->url . 'js/admin.js', array(), false, true);
		wp_register_script('codenegar-chosen-script', $this->url . 'js/chosen.js', array(), false, true);
		wp_register_style('codenegar-wcpf-admin-style', $this->url . 'css/admin.css');
		wp_register_style('codenegar-admin-option-widget-css', $this->url . 'css/widget.css');
		wp_register_style('codenegar-chosen-style', $this->url . 'css/chosen.css');
		wp_register_style('codenegar-wcpf-smoothness-style', $this->url . 'css/smoothness.css');
	}
	
	public function load_frontend_assets() {
		// Assets are loaded by widget builder to increase site speed on none shop archives
	}

	public function load_admin_assets() {
		// Load admin assets only in aas option page
		if (isset($_GET['page']) && $_GET['page'] == 'woocommerce_product_filter') {
			wp_enqueue_style('codenegar-wcpf-admin-style');
			wp_enqueue_style('codenegar-chosen-style');
			wp_enqueue_style('codenegar-wcpf-smoothness-style');
			
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('codenegar-admin-option-json');
			wp_enqueue_script('codenegar-admin-option-script');
			wp_enqueue_script('codenegar-chosen-script');
			$this->admin_script_translation();
		}
	}
	
	public function include_dependency(){
		require_once($this->path . 'widget/attribute.php');
		require_once($this->path . 'widget/category.php');
		require_once($this->path . 'widget/nonh_category.php');
		require_once($this->path . 'widget/custom_slider_meta.php');
		require_once($this->path . 'widget/meta.php');
		require_once($this->path . 'widget/reset.php');
		require_once($this->path . 'widget/order.php');
		require_once($this->path . 'widget/image_attribute.php');
		require_once($this->path . 'functions.php');
	}
	
	public function before_products(){
		echo '<span class="codenegar-shop-loop-wrapper">';
	}
	
	public function after_products(){
		echo '</span>';
	}
	
	public function before_no_products($template_name='', $template_path='', $located=''){
		if($template_name == 'loop/no-products-found.php'){
			echo '<span class="codenegar-shop-loop-wrapper">';
		}
	}
	
	public function after_no_products($template_name='', $template_path='', $located=''){
		if($template_name == 'loop/no-products-found.php'){
			echo '</span>';
		}
	}
	
	public function before_pagination($template_name='', $template_path='', $located=''){	
		echo '<span class="codenegar-shop-pagination-wrapper">';
	}
	
	public function after_pagination($template_name='', $template_path='', $located=''){
		echo '</span>';
	}
	
	public function add_paging_parameter($link){
		if(!isset($_GET['cnpf']) || !intval($_GET['cnpf'])==1){
			return $link;
		}
		
		$link = remove_query_arg("cnep", $link);
		$link = str_replace('#038;','&',$link); // wptexturize()
		$link = urldecode($link);
		$link = preg_replace('/&?cnep=[^&]*/', '', $link);
		$link = add_query_arg('cnep', '1', $link);

		return $link;
	}
	
	public function localize_script_config(){
		$handle = 'codenegar-wcpf-frontend';
		$object_name = 'codenegar_wcpf_config';
		$no_products = __('No products found which match your selection.', $this->text_domain);
		$l10n = array(
			'no_products_message' => '<p class="woocommerce-info codenegar_product_filter_no_products wcsl_message">'.$no_products.'</p>',
			'display_no_products_message' => $this->options->display_no_products_message,
			'loader_img' => $this->options->loader_image,
			'home_url' => home_url(),
		);
		wp_localize_script($handle, $object_name, $l10n);
	}
	
	public function admin_script_translation(){
		wp_localize_script('codenegar-admin-option-script', 'wcpf_words', array(
			'create_sidebar' => __('Create Sidebar', $this->text_domain),
			'cancel' => __('Cancel', $this->text_domain),
			'remove' => __('Remove', $this->text_domain),
		));
	}
}

// Create an object of Woocommerce Product Filter class
$codenegar_wcpf = new Codenegar_woocommerce_product_filter();

// Add an activation hook
register_activation_hook($codenegar_wcpf->file, array(&$codenegar_wcpf, 'activate'));

// Filter the posts
add_filter( 'pre_get_posts', array(&$codenegar_wcpf->filter,'filter_products'));
add_filter( 'posts_where', array(&$codenegar_wcpf->filter,'posts_where'));
add_filter('posts_join', array(&$codenegar_wcpf->filter,'posts_join'));
add_filter('posts_groupby', array(&$codenegar_wcpf->filter,'posts_groupby'));

// Register frontend/admin scripts and styles
add_action('wp_enqueue_scripts', array(&$codenegar_wcpf, 'register_frontend_assets'));
add_action('admin_init', array(&$codenegar_wcpf, 'register_admin_assets'));

// Make plugin translation ready
add_action('plugins_loaded', array(&$codenegar_wcpf, 'plugins_loaded'));

// actions to hook Plugin to Wordpress
add_action('init', array(&$codenegar_wcpf, 'initialize'));
add_action('admin_menu', array(&$codenegar_wcpf, 'admin_menu'));

// Load frontend/admin scripts and styles
add_action('wp_enqueue_scripts', array(&$codenegar_wcpf, 'load_frontend_assets'));
add_action('admin_enqueue_scripts', array(&$codenegar_wcpf, 'load_admin_assets'));

// Includes dependency scripts
$codenegar_wcpf->include_dependency();

// Wrap shop archive with HTML span
add_action( 'woocommerce_before_shop_loop', array(&$codenegar_wcpf,'before_products'), 3 );
add_action( 'woocommerce_after_shop_loop', array(&$codenegar_wcpf,'after_products'), 40 );
add_action( 'woocommerce_before_template_part', array(&$codenegar_wcpf,'before_no_products'), 3 );
add_action( 'woocommerce_after_template_part', array(&$codenegar_wcpf,'after_no_products'), 40 );

// Wrap shop pagination with HTML span
add_action( 'woocommerce_pagination', array(&$codenegar_wcpf,'before_pagination'), 3 );
add_action( 'woocommerce_pagination', array(&$codenegar_wcpf,'after_pagination'), 40 );

// Disables redirect on single result
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );

// Adds Paging Parameter
add_filter( 'paginate_links', array(&$codenegar_wcpf,'add_paging_parameter') );
?>