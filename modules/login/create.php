<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    $submit = ($security->varPost('ping_form_create_submit') == '1');

    if ($submit) {

	try {

	    $name = $security->varPost('ping_create_name');
	    $email = $security->varPost('ping_create_email');
	    $password = $security->varPost('ping_create_password');
	    $confirm_password = $security->varPost('ping_create_password_confirm');

	    try {
		
		if ($name == '' || $email == '' || $password == '' || $confirm_password == '') {
		    throw new Exception("The fields must not be empty!");
		}
		if ($password != $confirm_password) {
		    throw new Exception("The password doesn't match!");
		}
		if (strlen($name) < 3) {
		    throw new Exception("The name must be at least 6 characteres long!");
		}
		if (checkEmailExists($database, $email)) {
		    throw new Exception("The email inserted is already being used! Try again with a different email.");
		}
		if (strlen($password) < 6) {
		    throw new Exception("The password must be at least 6 characteres long!");
		}

		$user = Users::create($email, $password, $name);
		
		if ($user->isLoaded())
		{
		    $userLogin = loginUser($user->getId());
		    redirect('index.php?module=login');
		}
		
	    } catch (Exception $ex) {
		printError($ex->getMessage());
	    }
	} catch (SecurityException $ex) {
	    //
	}
    }

    if (!userLoggedIn()) {
	require('modules/login/form.html');
    }
?>

