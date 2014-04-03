<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    function userLoggedIn()
    {
	return isset($GLOBALS["user_login"]);
    }
    
    function checkLogin($database, $email, $password)
    {
	$pass = md5($password);
	$sql = "SELECT `id` FROM `users` WHERE `email`='".$email."' AND `password`='".$pass."' LIMIT 1;";
	
	if ($database->isConnected())
	{
	    $query = $database->getPDOInstance()->query($sql);
	    if ($query->rowCount() == 1) {
		$row = $query->fetch();
		return $row['id'];
	    } else {
		throw new Exception("The email or password provided is incorrect!");
	    }
	} else { 
	    throw new Exception("Database connection failed.");
	}
		
    }
    
    function redirect($url, $time = 1) {
	$url = str_replace('&amp;', '&', $url);
	echo "<script>$(document).ready(function(){
		setTimeout(function() {
		 window.location.href = '".$url."';
		}, ".$time.");
	      });</script>";
    }
    
    function checkEmailExists($database, $email)
    {	
	$sql = "SELECT `id` FROM `users` WHERE `email`='".$email."' LIMIT 1;";
	
	return checkQuery($database, $sql);
    }
    
    function checkEmailExistsReturnId($database, $email)
    {	
	$sql = "SELECT `id` FROM `users` WHERE `email`='".$email."' LIMIT 1;";
	
	return checkQueryId($database, $sql);
    }
    
    function checkGroupExists($database, $name)
    {
	$sql = "SELECT `id` FROM `groups` WHERE `name`='".$name."' LIMIT 1;";
	
	return checkQuery($database, $sql);
    }
    
    function getLastUserPosition($database, $groupuser) {
	
	$sql = "SELECT `id` FROM `user_positions` WHERE `group_user`='".$groupuser->getId()."' ORDER BY `time` DESC LIMIT 1;";
	
	if ($database->isConnected())
	{
	    $query = $database->getPDOInstance()->query($sql);
	    if ($query->rowCount() == 1) {
		$q = $query->fetch();
		return UserPositions::load($q['id']);
	    } else {
		return null;
	    }
	} else { 
	    throw new Exception("Database connection failed.");
	}
    }
    
    function checkQuery($database, $sql)
    {
	if ($database->isConnected())
	{
	    $query = $database->getPDOInstance()->query($sql);
	    if ($query->rowCount() == 1) {
		return true;
	    } else {
		return false;
	    }
	} else { 
	    throw new Exception("Database connection failed.");
	}
    }
    
    function checkQueryId($database, $sql)
    {
	if ($database->isConnected())
	{
	    $query = $database->getPDOInstance()->query($sql);
	    if ($query->rowCount() == 1) {
		$q = $query->fetch();
		
		return $q['id'];
	    } else {
		return null;
	    }
	} else { 
	    throw new Exception("Database connection failed.");
	}
    }
    
    function loginUser($id)
    {
	$_SESSION['user_id'] = $id;
	$GLOBALS['user_login'] = Users::load($id);
	
	return $GLOBALS['user_login'];
    }
    
    function printError($error)
    {
	echo ''
	. '<script>'
	. 'var m = "<p class=\'bg-danger\' style=\'padding: 10px;\'>'.$error.'</p>";'
	. '$("#message").hide().html(m).fadeIn();'
	. '</script>';
    }
    
    function printSuccess($message)
    {
	echo ''
	. '<script>'
	. 'var m = "<p class=\'bg-success\' style=\'padding: 10px;\'>'.$message.'</p>";'
	. '$("#message").hide().html(m).fadeIn();'
	. '</script>';
    }
    
    function loadGoogleMapsAPI() {
	echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='.$GLOBALS['google_api_key'].'&sensor=true"></script>';
    }

?>
