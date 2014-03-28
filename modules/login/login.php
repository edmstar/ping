<?php

    if (!userLoggedIn())
    {
    
	$submit = ($security->varPost('ping_form_submit') == '1');

	if ($submit) {
	    try
	    {
		$email = $security->varPost('ping_email');
		$password = $security->varPost('ping_password');

		try {
		    $userId = checkLogin($database, $email, $password);
		    $_SESSION['user_id'] = $userId;

		    $GLOBALS['user_login'] = Users::load($userId);
		    $userLogin = $GLOBALS['user_login'];
		    
		    redirect('index.php?module=login');

		} catch (Exception $ex) {
		    echo '<p class="bg-danger">'.$ex->getMessage().'</p>';
		}
	    } catch (SecurityException $e) {
		//to be implemented
	    }

	}

	if (!userLoggedIn()) {
?>

<form role="form" method="POST" action="index.php?module=login">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="ping_email" name="ping_email" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="ping_password" name ="ping_password" placeholder="Password">
  </div>
  <input type="hidden" name="ping_form_submit" value="1"/>
  <button type="submit" class="btn btn-default">Submit</button>
</form>

<?php
	}
    }
?>