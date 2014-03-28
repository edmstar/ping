<?php

    if (!userLoggedIn())
    {
	require('modules/login/login.php');
    } else {
	$action = $security->varGet('action');
	
	switch($action)
	{
	    case "logout":
		require('modules/login/logout.php');
		break;
	}
    }
    
?>
