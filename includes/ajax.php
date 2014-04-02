<?php
    
    if (!defined('IN_INDEX')) {
	exit;
    }
    
    $ajax = $security->varGet('ajax') == '1' ? true : false;
    
    if ($ajax) {
	include('includes/ajax_modules.php');
	exit;
    }
?>
