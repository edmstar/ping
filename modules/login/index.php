<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    $action = $security->varGet('action');
    
    if (!userLoggedIn())
    {
	switch($action)
	{
	    case "create":
		require('modules/login/create.php');
		break;
	    case "login":
	    default:
		require('modules/login/login.php');
		break;
	}

    } else {
	switch($action)
	{
	    case "logout":
		require('modules/login/logout.php');
		break;
	}
    }
    
?>
