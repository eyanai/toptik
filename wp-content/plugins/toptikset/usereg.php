<?php
    //1. Add a new form element...
    add_action('register_form','myplugin_register_form');
    function myplugin_register_form (){
        $first_name = ( isset( $_POST['first_name'] ) ) ? $_POST['first_name']: '';
        ?>
		<h2 class="newH2"><span class="newH2span">פרטים אישיים</span> [<span class="red">*</span>] שדות חובה</h2>
		<p>
            <label for="first_name">זכר
             <input type="radio" name="sex"  value="זכר"/></label>
			 <label for="first_name">נקבה
             <input type="radio" name="sex"  value="נקבה"/></label>
        </p>
		<p>
            <label for="first_name"><?php _e('First Name','mydomain') ?>
             <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(stripslashes($first_name)); ?>" size="25" /></label>
        </p>
        <p>
            <label for="last_name"><?php _e('Last Name','mydomain') ?>
                <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr(stripslashes($last_name)); ?>" size="25" /></label>
        </p>
		<p>
            <label for="Comp_name"><?php _e('Birth Day','mydomain') ?>
                <input type="date" name="billing_company" id="billing_company" class="date"></label>
        </p>
		<p>
            <label for="email"><?php _e('Email','mydomain') ?>
                <input type="email" name="email" id="email" class="input" ></label>
        </p>
		<p>
            <label for="phone"><?php _e('Phone','mydomain') ?>
                <input type="tel" name="phone" id="phone" class="input"></label>
        </p>
		
		<h2 class="newH2"><span class="newH2span">כתובת למשלוח</span> [<span class="red">*</span>] שדות חובה</h2>
		<p>
            <label for="adress"><?php _e('Adress','mydomain') ?>
                <input type="text" name="adress" id="adress" class="input" ></label>
        </p>
		<p>
            <label for="city"><?php _e('City','mydomain') ?>
                <input type="text" name="city" id="city" class="input"></label>
        </p>
		<p>
            <label for="zipcode"><?php _e('zipcode','mydomain') ?>
                <input type="number" name="zipcode" id="zipcode" class="input"></label>
        </p>
		<?php /*?><h2 class="newH2"><span class="newH2span">סיסמא</span> [<span class="red">*</span>] שדות חובה</h2>
		
		<p>
            <label for="pass"><?php _e('Password','mydomain') ?>
                <input type="password" name="pass" id="pass" class="input"></label>
        </p>
		<p>
            <label for="repass"><?php _e('rePassword','mydomain') ?>
                <input type="password" name="pass" id="pass" class="input"></label><?php */?>
        </p>
        <?php
		$newid=wp_create_user('dammy1', 'dammy1','dammy1@mail.com' );
		update_user_meta($newid, 'billing_company', '13-06-79');
		
		//$user_data = array(
//		'ID' => $newid,
//		//'user_pass' => wp_generate_password(),
//		'first_name' => 'dummy',
//		'last_name' => 'Dummy',
//		'user_email' => 'dummy@example.com',
//		'billing_company' => 'Dummy',
//		'nickname' => 'dummy',
//		'first_name' => 'Dummy',
//		'role' => get_option('default_role') // Use default role or another role, e.g. 'editor'
//		);
//	
//	$user_id = wp_insert_user( $user_data );
//	echo "hallo ->". $user_id;
    }

    //2. Add validation. In this case, we make sure first_name is required.
    add_filter('registration_errors', 'myplugin_registration_errors', 10, 3);
    function myplugin_registration_errors ($errors, $sanitized_user_login, $user_email) {

        if ( empty( $_POST['first_name'])||empty( $_POST['last_name'] )|| empty( $_POST['comp_name'] ) )
            $errors->add( 'first_name_error', __('<strong>שגיאה: </strong>:אנא מלא שדות נדרשים.','mydomain') );
		/* if ( empty( $_POST['last_name'] ) )
            $errors->add( 'last_name_error', __('<strong>שגיאה: </strong>:הכנס שם משפחה.','mydomain') );
		if ( empty( $_POST['comp_name'] ) )
           $errors->add( 'comp_name_error', __('<strong>שגיאה: </strong>תאריך לידה לא מולא.','mydomain') );
		if ( empty( $_POST['first_name'] ) )
            $errors->add( 'first_name_error', __('<strong>שגיאה: </strong>:אתה חייב להכניס את השם שלך.','mydomain') );
		if ( empty( $_POST['first_name'] ) )
            $errors->add( 'first_name_error', __('<strong>שגיאה: </strong>:אתה חייב להכניס את השם שלך.','mydomain') );
		if ( empty( $_POST['first_name'] ) )
            $errors->add( 'first_name_error', __('<strong>שגיאה: </strong>:אתה חייב להכניס את השם שלך.','mydomain') );*/

        return $errors;
    }



	

    //3. Finally, save our extra registration user meta.
    add_action('user_register', 'myplugin_user_register');
    function myplugin_user_register ($user_id) {
        if ( isset( $_POST['first_name'] ) )
			update_user_meta($user_id, 'first_name', $_POST['first_name']);
            update_user_meta($user_id, 'billing_first_name', $_POST['first_name']);
			update_user_meta($user_id, 'last_name', $_POST['last_name']);
            update_user_meta($user_id, 'billing_last_name', $_POST['last_name']);
			update_user_meta($user_id, 'billing_company', $_POST['comp_name']);
            update_user_meta($user_id, 'billing_last_name', $_POST['last_name']);
    }