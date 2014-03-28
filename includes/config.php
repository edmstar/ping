<?php
	
    if (!defined('IN_INDEX')) {
	exit;
    }
    
    include_once('classes/Security.php');

    include_once('classes/Database.php');
    include_once('classes/CachedTable.php');

    include_once('classes/Users.php');
    include_once('classes/Groups.php');
    include_once('classes/GroupUser.php');
    include_once('classes/UserPositions.php');

    include_once('functions.php');
    
    $security = new Security();
    
    $database = Database::getDatabase('localhost', 'ping', 'ping', 'ping');
    $database->connect();
    
    //checks if the user is logged in and, if so, loads the user in the global variable $userLogin
    $GLOBALS["user_login"] = null;
    
    if (isset($_SESSION['user_id']))
    {
	try {
	    $GLOBALS["user_login"] = Users::load($_SESSION['user_id']);
	    if (!$GLOBALS["user_login"]->isLoaded())
	    {
		$GLOBALS["user_login"] = null;
		unset($_SESSION['user_id']);
	    }
	} catch (CachedTableException $e) {
	    $GLOBALS["user_login"] = null;
	    unset($_SESSION['user_id']);
	}
    }
    
    $userLogin = $GLOBALS["user_login"];
    


?>