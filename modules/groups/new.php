<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    $submit = ($security->varPost('ping_form_new_submit') == '1');
    $createdGroup = false;
    
    if ($submit) {
	try {
	    $name = $security->varPost('ping_group_name');
	    
	    try {
		if ($name == '') {
		    throw new Exception('The field must not be empty!');
		}
		if (strlen($name) < 3) {
		    throw new Exception("The name must be at least 6 characteres long!");
		}
		if (checkGroupExists($database, $name)) {
		    throw new Exception("This name is already being used by another group. Please choose another name.");
		}
		
		$group = Groups::create($name);
		
		if ($group->isLoaded())
		{
		    $group->addOwner($userLogin);
		    $createdGroup = true;
		    redirect('index.php?module=groups&action=mygroups');
		}
		
	    } catch (Exception $ex) {
		printError($ex->getMessage());
	    }
	} catch (SecurityException $ex) {

	}
    }
    
    if (!$createdGroup) {
	require('modules/groups/form_new.html');
    }
?>
