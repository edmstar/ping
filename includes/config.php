<?php
	
	include_once('classes/Security.php');
	
	include_once('classes/Database.php');
	include_once('classes/CachedTable.php');
	
	include_once('classes/Users.php');
	include_once('classes/Groups.php');
	include_once('classes/GroupUser.php');
	
	
	$database = Database::getDatabase('localhost', 'ping', 'ping', 'ping');
	$database->connect();
   
?>
