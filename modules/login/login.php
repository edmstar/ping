<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    if ($action == "login")
    {
	$submit = ($security->varPost('ping_form_submit') == '1');

	if ($submit) {
	    try
	    {
		$email = $security->varPost('ping_email');
		$password = $security->varPost('ping_password');

		try {
		    
		    $userId = checkLogin($database, $email, $password);
		    $userLogin = loginUser($userId);

		    redirect('index.php?module=login');

		} catch (Exception $ex) {
		    printError($ex->getMessage());
		}
	    } catch (SecurityException $e) {
		//to be implemented
	    }

	}
    }

    if (!userLoggedIn()) {
	require('modules/login/form.html');
    }
?>