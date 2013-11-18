<?php
//error_reporting(E_ALL);
/*Plugin Name: top-tik setting
Plugin URI:cambium.co.il
Descriotion: home setting and more...
Author: yanai edri
Author URI:cambium.co.il
Version:1.0*/
require_once('usercupon.php');
require_once('toptikWidget.php');

class TOP_Option{
	public $options;
	
	public function __construct(){
		$this->options=get_option('ye_plugin_options');
		$this->register_setting_and_fields();
	}

	public function add_menu_page(){
		add_options_page('toptik option','ניהול אתר טופ-תיק','administrator',__FILE__,array('TOP_Option','toptikop'));
	}
	
	
	public function toptikop(){
		?>
		<div class="wrap">
			<?php screen_icon();?><h2>הגדרות אתר טופ-תיק</h2>
			<form method="post"	 action="options.php" enctype="multipart/form-data"> 
				<?php settings_fields('ye_plugin_options');?>
				<?php do_settings_sections(__FILE__);?>
				<p class="submit">
					<input type="submit" name="submit" class="botton-primary" value="שמור שינויים">
				</p>
			</form>
		</div>
	
	<?php
	}
	
	
	public function register_setting_and_fields(){
		register_setting('ye_plugin_options','ye_plugin_options');//3rd=optional bd
		//add_settings_section( $id, $title, $callback, $page );
		add_settings_section('ye_main_section','הגדרות TOPTIK',array($this,'ye_main_section_cb'),__FILE__);
		// add_settings_field( $id, $title, $callback, $page, $section, $args );
		add_settings_field('ye_rec_top','מומלצים עליון :',array($this,'ye_rec_top_setting'),__FILE__,'ye_main_section');
		add_settings_field('ye_rec_down','מומלצים תחתון :',array($this,'ye_rec_down_setting'),__FILE__,'ye_main_section');
		add_settings_field('ye_face_mail','עמוד פייסבוק :',array($this,'ye_down_pace'),__FILE__,'ye_main_section');
		add_settings_field('ye_credit','אחוזי קרדיט :',array($this,'ye_credit_set'),__FILE__,'ye_main_section');
		add_settings_field('ye_new','כמה ימים חדש :',array($this,'ye_new_set'),__FILE__,'ye_main_section');
		add_settings_field('ye_tel','טלפון חנות :',array($this,'ye_new_tel'),__FILE__,'ye_main_section');
		add_settings_field('ye_link',' טקסט עבור כותרת לינקים :',array($this,'ye_new_link'),__FILE__,'ye_main_section');
		add_settings_field('ye_regMail','טקסט עבור רישום משתמש חדש :',array($this,'ye_new_regMail'),__FILE__,'ye_main_section');

	}
	
	
	public function ye_main_section_cb(){
	
	}
	//inputs----------------------------------------*/
	//top rec
	public function ye_rec_top_setting(){
	//echo "<input name='ye_plugin_options[ye_rec_top]' type='text' value='{$this->options[ye_rec_top]}'/>";	
		$items=array('4','8','12','16');
		echo "<select name='ye_plugin_options[ye_rec_top]'/>";	
		foreach ($items as $item){
			$selected=($this->options['ye_rec_top']===$item)?'selected="selected"':'';
			echo "<option value='$item' $selected>$item</option>";
		}
	
	}
	
	public function ye_rec_down_setting(){
		$items=array('4','8','12','16');
		echo "<select name='ye_plugin_options[ye_rec_down]'/>";	
		foreach ($items as $item){
			$selected=($this->options['ye_rec_down']===$item)?'selected="selected"':'';
			echo "<option value='$item' $selected>$item</option>";
		}
	}
	
	
	public function ye_down_pace(){
		echo "<input type='text' name='ye_plugin_options[ye_paypal_face]' value='".$this->options['ye_paypal_face']."'/>";	
		}
	
	public function ye_credit_set(){
		echo "<input type='number' name='ye_plugin_options[ye_credit]' min='0' max='100' value='".$this->options['ye_credit']."'/>";	
		}

	public function ye_new_set(){
		echo "<input type='number' name='ye_plugin_options[ye_new]' min='1' max='365' value='".$this->options['ye_new']."'/>";	
		}

	public function ye_new_tel(){
		echo "<input type='text' name='ye_plugin_options[ye_tel]'  value='".$this->options['ye_tel']."'/>";	
		}
	public function ye_new_link(){
		echo "<input type='text' name='ye_plugin_options[ye_link]'  value='".$this->options['ye_link']."'/>";	
		}
	public function ye_new_regMail(){
		echo "<textarea cols='50' rows='10' name='ye_plugin_options[ye_regMail]'/>".$this->options['ye_regMail']."</textarea>";	
		}
	
}

add_action('admin_menu',function(){
	TOP_Option::add_menu_page();
});

add_action('admin_init',function(){
	new TOP_Option();
});

require_once('usereg.php');
require_once('getway.php');

