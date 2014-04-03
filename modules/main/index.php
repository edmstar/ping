<?php


?>

	
<!-- Main component for a primary marketing message or call to action -->
<div class="jumbotron">
  <h1>Ping</h1>
  <p>Welcome to Ping!</p>
  <p>
<?php

if (!userLoggedIn()) {

?>
    <a class="btn btn-lg btn-primary" href="index.php?module=login" role="button">Login</a>
<?php
} else {

?>
    <a class="btn btn-lg btn-primary" href="index.php?module=groups&action=mygroups" role="button">View my groups</a>
<?php

}
?>
  </p>
</div>