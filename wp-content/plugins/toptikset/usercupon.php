<?php
session_start();
add_action('woocommerce_cart_credit','user_add_credit');
add_action('woocommerce_credit_reset','reset_credit');


function user_add_credit(){

global $_toptikcredit;
	 $_toptikcredit=array('credit'=>'','codcredit'=>'','creditId'=>'');


	global $woocommerce, $current_user;
	
	$lastwant=get_user_meta($current_user->ID ,'credit_want',true);
	if($lastwant){
		$_toptikcredit['credit']=get_user_meta($current_user->ID ,'credit_want',true);
	}
	
	$codecredit=get_user_meta($current_user->ID,'cerdit_cupon',true);
	if(!empty($codecredit)){
		if (!$woocommerce->cart->add_discount( sanitize_text_field($codecredit))){
				//		$woocommerce->show_messages();
		}

	}

	if(isset($_POST['apply_credit']) && !empty($_POST['credit_val'])){
	
      get_currentuserinfo();

      $user_ID=$current_user->ID;
	  
	  $wnat=(int)htmlspecialchars($_POST['credit_val']);
	  if(is_int($wnat)){
	  
	  $allcredit=(int)get_user_meta($user_ID,'credit',true);

	  $code=$current_user->ID."_".time();
	
	
			
	$coupon_code = $code; // Code
	$amount =  $wnat; // Amount
	$discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product
	
	
	
	$cCredit=get_user_meta($user_ID,'cerdit_cupon',true);


	if(!empty($cCredit)){
		$editCupon=addtocupon();
	}
	if($wnat<=$allcredit && empty($cCredit)){
			
			
			
			$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type'		=> 'shop_coupon'
				);
									
				$new_coupon_id = wp_insert_post( $coupon );
				update_user_meta($user_ID, 'credit_post', $new_coupon_id );			
				// Add meta
				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', 'no' );
				update_post_meta( $new_coupon_id, 'product_ids', '' );
				update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
				update_post_meta( $new_coupon_id, 'usage_limit', '' );
				update_post_meta( $new_coupon_id, 'expiry_date', '' );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
				
				if (!$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ))){
		//			$woocommerce->show_messages();
				}else{
					//cupon seccses
					$newcredit=$allcredit-$wnat;
					update_user_meta($user_ID, 'credit', $newcredit );
					update_user_meta($user_ID, 'credit_want', $wnat );
					$_toptikcredit['codcredit']=$coupon_code;
					update_user_meta($user_ID,'cerdit_cupon',$coupon_code);
				}
				
				}else{
					//send eroor u dont have the same credit
					if($editCupon!=1)echo "אין לך מספיק קרדיט למימוש";
					
				}
			}
		else{
			//send eroor is not a namber
			echo "לא הכנסת מספר- נסה שנית";
		}
	}//if isset
	
	
	if(isset($_GET['remove_discounts']) && $_GET['remove_discounts']==1 || isset($_GET['remove_credit']) && $_GET['remove_credit']==1){
		
		$cart=new WC_Cart;
		//echo "<pre>".print_r($woocommerce->cart,1)."</pre>";
		
		$lastwant=get_user_meta($current_user->ID ,'credit_want',true);
		$codecredit=get_user_meta($current_user->ID,'cerdit_cupon',true);
		$oldcredit=get_user_meta($current_user->ID, 'credit', true);
		$creditId=get_user_meta($current_user->ID, 'credit_post', true);
		
		$newCerdit=$lastwant+$oldcredit;
		echo "old: ".$oldcredit."<br>";
		echo "new: ".$codecredit."<br>";
		
		//$woocommerce->cart->remove_coupons($_GET['remove_discounts']);
		//$woocommerce->cart->remove_coupons();
		//$woocommerce->cart->remove_coupons($_GET['remove_credit']);
		$woocommerce->cart->remove_coupons($codecredit);			
					
		//if (!$woocommerce->cart->remove_coupons( sanitize_text_field( $codecredit ))) {
          //  $woocommerce->show_messages();
        //}
		
		wp_delete_post($creditId);
		update_user_meta($current_user->ID,'cerdit_cupon','');
		update_user_meta($current_user->ID, 'credit', $newCerdit );
		update_user_meta($current_user->ID, 'credit_want','');
		update_user_meta($current_user->ID, 'credit_post','');
		  $woocommerce->cart->calculate_totals();
		 unset( $woocommerce->session->coupon_codes, $woocommerce->session->coupon_amounts );  
			    
	}
}



function reset_credit($page=''){
	
	global $woocommerce, $current_user;

	 
	   $newaddcredit=$_SESSION['addcredit'];	

			
		$lastwant=get_user_meta($current_user->ID ,'credit_want',true);
		$codecredit=get_user_meta($current_user->ID,'cerdit_cupon',true);
		$oldcredit=get_user_meta($current_user->ID, 'credit', true);
		$creditId=get_user_meta($current_user->ID, 'credit_post', true);
		
		wp_delete_post($creditId);
		
		$newCerdit=$newaddcredit+$oldcredit;
		
		wp_delete_post($creditId);
		update_user_meta($current_user->ID, 'credit', $newCerdit);
		update_user_meta($current_user->ID,'cerdit_cupon','');
		update_user_meta($current_user->ID, 'credit_want','');
		update_user_meta($current_user->ID, 'credit_post','');
		
		unset($_SESSION['addcredit']);

}

function addtocupon(){
		global $woocommerce, $current_user;
		
		$code=$current_user->ID."_".time();
		
		$oldWant=get_user_meta($current_user->ID ,'credit_want',true);
		$newWant=get_user_meta($current_user->ID,'cerdit_cupon',true);
		$oldcredit=get_user_meta($current_user->ID, 'credit', true);
		
		$codecredit=get_user_meta($current_user->ID,'cerdit_cupon',true);
		
		$creditNew=(int)htmlspecialchars($_POST['credit_val']);
		
		if($creditNew<=($oldcredit+$oldWant)){
		
			if($creditNew<=$oldWant){
				$newcedituser=$oldcredit+$oldWant-$creditNew;
			}else{
				$newcedituser=($oldcredit+$oldWant)-($creditNew);
			}
			//new credit user update
			update_user_meta($current_user->ID, 'credit', $newcedituser);
			//remove old cupon
			//$woocommerce->cart->remove_coupons(1);
			//$woocommerce->cart->remove_coupons();
			$woocommerce->cart->remove_coupons($codecredit);
			
			$creditId=get_user_meta($current_user->ID, 'credit_post', true);
			
			wp_delete_post($creditId);
			//new cuopne
			
			$coupon = array(
						'post_title' => $code,
						'post_content' => '',
						'post_status' => 'publish',
						'post_author' => 1,
						'post_type'		=> 'shop_coupon'
					);
										
					$new_coupon_id = wp_insert_post( $coupon );
					update_user_meta($current_user->ID, 'credit_post', $new_coupon_id );			
					// Add meta
					update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
					update_post_meta( $new_coupon_id, 'coupon_amount', $creditNew );
					update_post_meta( $new_coupon_id, 'individual_use', 'no' );
					update_post_meta( $new_coupon_id, 'product_ids', '' );
					update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
					update_post_meta( $new_coupon_id, 'usage_limit', '' );
					update_post_meta( $new_coupon_id, 'expiry_date', '' );
					update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
					update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
					
				if (!$woocommerce->cart->add_discount($code)){
						//$woocommerce->show_messages();
					}
			
				update_user_meta($current_user->ID, 'credit_want', $creditNew );
				update_user_meta($current_user->ID, 'cerdit_cupon',$code );
				
				$_toptikcredit['codcredit']='';
				$_toptikcredit['codcredit']=$code;
				
				return '1';	
		}else{
				return '0';
		}
}