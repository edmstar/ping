<?php

    if (!defined('IN_INDEX')) {
	exit;
    }
    
    
    $module = $security->varGet("module");
    
    switch($module)
    {
	case "positions":
	    require('modules/positions/index.php');
	    break;
	
    }
?>
