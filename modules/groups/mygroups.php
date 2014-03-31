<div class="jumbotron">
    <div class="row">
	<div class="col-md-6">
    
	    <?php
		$groupList = $userLogin->getGroups();

		if(count($groupList) == 0) {
	    ?>
	    
	    You don't belong to any group yet. Try <a href='index.php?module=groups&action=new'>creating a new group</a> now!<br/>
	    If you want to join a group that already exists, you must be invited.
	    
	    <?php
		} else {
	    ?>
	    
	    <a href='index.php?module=groups&action=new'>Create a new group</a><br/><br/>
	    <table class="table table-striped">
		<tr><th>Group Name</th><th># of users</th></tr>
		
		<?php
		
		    foreach($groupList as $group) {
			echo '<td><a href="index.php?module=groups&action=view&id='.$group->getId().'">'.$group->getName()."</a></td><td>".count($group->getUsers())."</td>";
		    }
		
		?>
		
	    </table>
	    <?php
	    
	    
		}
	    ?>
	</div>
    </div>
</div>