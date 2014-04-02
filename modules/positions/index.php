<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    if (!userLoggedIn()) {
	redirect('index.php?module=login', 0);
	exit;
    }
    
    $action = $security->varGet("action");
    
    switch($action) {
	case "update":
	    require('modules/positions/update.php');
	    break;
    }
    
    

?>

