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
    
    function redirect($url, $tempo) {
	$url = str_replace('&amp;', '&', $url);

	if($tempo > 0) {
	    echo "<script>red_timeout = setTimeout('redir(\'$url\')', $tempo);</script>";
	} else {
	    @ob_flush();
	    @ob_end_clean();
	    echo "<script>window.location='$url';</script>";
	    exit;
	}
    }
    
    function checkEmailExists($database, $email)
    {	
	$sql = "SELECT `id` FROM `users` WHERE `email`='".$email."' LIMIT 1;";
	
	return checkQuery($database, $sql);
    }
    
    function checkGroupExists($database, $name)
    {
	$sql = "SELECT `id` FROM `groups` WHERE `name`='".$name."' LIMIT 1;";
	
	return checkQuery($database, $sql);
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
    
    function loginUser($id)
    {
	$_SESSION['user_id'] = $id;
	$GLOBALS['user_login'] = Users::load($id);
	
	return $GLOBALS['user_login'];
    }
    
    function printError($error)
    {
	echo '<p class="bg-danger">'.$error.'</p>';
    }

?>
