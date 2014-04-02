<div class="col-md-12">
    <div class="col-md-2" style="padding-top: 8px;"><b>User email</b></div>
    <div class="col-md-8">
	<form class="form-inline" role="form" method="POST" action="index.php?module=groups&action=add&group=<?php echo $group->getId(); ?>">
	  <label class="sr-only" for="ping_user_email">User Email</label>
	  <input type="text" class="form-control" id="ping_user_email" name="ping_user_email" placeholder="Enter user email" value="" style="width: 80%; max-width: 700px;"/>
	  <input type="hidden" name="ping_form_insert_submit" value="1"/>
	  <button type="submit" class="btn btn-primary">Insert User</button>
	</form>
    </div>
</div>
