<?php

    $module = $security->varGet("module");
    
    switch($module)
    {
	case 'login':
	    require('modules/login/index.php');
	    break;
	case 'main':
	default:
	    require('modules/main/index.php');
	    break;
    }
    
?>
