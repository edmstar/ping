<?php

    unset($_SESSION['user_id']);
    unset($GLOBALS['user_login']);
    unset($userLogin);
    
    redirect('index.php', 0);
?>
