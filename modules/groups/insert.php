<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    $submit = ($security->varPost('ping_form_insert_submit') == '1');
    $insertedUser = false;
    
    if ($submit) {

	try {

	    $groupId = $security->varGet('group');
	    $email = $security->varPost('ping_user_email');
	    
	    $group = Groups::load($groupId);
	    
	    if (!$group->isLoaded()) {
		throw new Exception('The group provided does not exist!');
	    }

	    $groupUsers = $userLogin->getGroupUsers();
	    $owner = false;

	    foreach($groupUsers as $gu) {
		if ($gu->getGroup()->getId() == $group->getId()) {
		    if ($gu->getOwner() == '1') {
			$owner = true;
		    }
		    break;
		}
	    }

	    if (!$owner) {
		throw new Exception('You must be the owner of the group to insert people!');
	    }
	    
	    if ($email == '') {
		throw new Exception('The field must not be empty!');
	    }

	    $id = checkEmailExistsReturnId($database, $email);

	    if ($id == null) {
		throw new Exception("This email is not registered in our database.");
	    }

	    $user = Users::load($id);

	    foreach($user->getGroups() as $g) {
		if ($g->getId() == $group->getId()) {
		    $userBelongs = true;
		    break;
		}
	    }
	    
	    if ($userBelongs) {
		throw new Exception("The email inserted already belongs to this group.");
	    }
	    
	    $groupuser = GroupUser::create($user, $group, '0');
	    
	    if ($groupuser->isLoaded()) {
		printSuccess("User ".$user->getEmail(). " added to the group!");
		$createdGroup = true;
		redirect('index.php?module=groups&action=view&id='.$group->getId());
	    }
	    
		
	} catch (SecurityException $ex) {

	} catch (Exception $ex) {
	    printError($ex->getMessage());
	}
    }
    
    if (!$createdGroup) {
	require('modules/groups/mygroups.php');
    }

?>
