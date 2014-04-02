<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    $action = $security->varGet('action');
    
    if (userLoggedIn()) {
	switch($action)
	{
	    case "new":
		require('modules/groups/new.php');
		break;
	    case "view":
		require('modules/groups/view.php');
		break;
	    case "add":
		require('modules/groups/insert.php');
		break;
	    case "mygroups":
	    default:
		require('modules/groups/mygroups.php');
		break;
	}
    } else {
	redirect('index.php?module=login');
    }

?>