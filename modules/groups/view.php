<div class="jumbotron">
    <div class="row">
	
<?php

    $id = $security->varGet('id');
    
    $group = null;
    $time = time();
    
    foreach($userLogin->getGroups() as $g) {
	if ($g->getId() == $id) {
	    $group = $g;
	    break;
	}
    }
    
    if ($group != null) {
	
	$users = $group->getUsers();
	
	$lastUserPositions = $group->getLastUserPositions();
	$hidden = true;
	$mygroupuser = null;
	
	foreach($lastUserPositions as $up) {
	    if ($up->getGroupUser()->getUser()->getId() == $userLogin->getId()) {
		$mygroupuser = $up->getGroupUser();
		if (($time - $up->getTime()) < MAXIMUM_TIME_WITHOUT_UPDATE) {
		    $hidden = false;
		    break;
		}
	    }
	}
	
	if (!$hidden) {
	    if ($mygroupuser->getOwner() == '1') {
		require('modules/groups/insertUserForm.php');
	    }
	} else {
	    printError("Hidden!");
	    redirect('index.php?module=groups&action=mygroups');
	}
	
    } else {
	printError("You do not belong to this group!");
	redirect('index.php?module=groups&action=mygroups');
    }  
?>

    </div>
</div>