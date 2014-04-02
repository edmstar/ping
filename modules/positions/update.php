<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    $groupId = $security->varGet('group');
    $latitude = $security->varPost('latitude');
    $longitude = $security->varPost('longitude');
    $time = time();
    
    $groupsList = array();

    if ($groupId == 'all') {
	
	$groupsList = $userLogin->getGroupUsers();
	
    } else {
	$group = null;
	
	foreach($userLogin->getGroupUsers() as $gu)
	{
	    if ($gu->getGroup()->getId() != $groupId) {
		continue;
	    }
	    
	    $group = $gu;
	    break;
	}
	
	if ($group != null)
	{
	    $groupsList[] = $group;
	}
    }
    
    if (count($groupsList) > 0) {
	foreach($groupsList as $g) {
	    
	    $lastUserPosition = getLastUserPosition($database, $g);
	    
	    if ($lastUserPosition != null) {

		if (($time - $lastUserPosition->getTime()) >= MINIMUM_UPDATE_TIME) {
		    $userpos = UserPositions::create($g, $time, '0', $latitude, $longitude);
		} else {
		    $lastUserPosition->setTime($time);
		    $lastUserPosition->setLatitude($latitude);
		    $lastUserPosition->setLongitude($longitude);

		    $lastUserPosition->save();
		}
		    
	    } else {
		$userpos = UserPositions::create($g, $time, '0', $latitude, $longitude);
	    }
	}
	printSuccess("Group(s) update(d) successfully!");
    }
?>
