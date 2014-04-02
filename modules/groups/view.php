<div class="jumbotron">
    <div class="row">
	
<?php

    $id = $security->varGet('id');
    
    $group = null;
    $time = time();
    
    foreach($userLogin->getGroups() as $g) {
	if ($g->getId() == $id) {
	    $group = $g;
	    break;
	}
    }
    
    if ($group != null) {
	
	echo "<h3>".$group->getName()."</h3><br/><br/>";
	
	$users = $group->getUsers();
	
	$lastUserPositions = $group->getLastUserPositions();
	$hidden = true;
	$mygroupuser = null;
	
	foreach($lastUserPositions as $up) {
	    if ($up->getGroupUser()->getUser()->getId() == $userLogin->getId()) {
		$mygroupuser = $up->getGroupUser();
		if (($time - $up->getTime()) < MAXIMUM_TIME_WITHOUT_UPDATE) {
		    $hidden = false;
		    break;
		}
	    }
	}
	
	if (!$hidden) {
	    if ($mygroupuser->getOwner() == '1') {
		require('modules/groups/insertUserForm.php');
	    }
	    
?>
	<?php loadGoogleMapsAPI(); ?>
	<br/><br/>
	<div id="map-canvas" style="height: 500px;"></div>
	
	<script>

	    var map;
	    var bounds = new google.maps.LatLngBounds();
	    
	    function initialize() {
		var myOptions = {
		  center: new google.maps.LatLng(49.2569777,-123.123904),
		  zoom: 10,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
	    }
	    
	    function addMarker(latitude, longitude, user, time) {
		var loc = new google.maps.LatLng(latitude, longitude);
		
		var marker = new google.maps.Marker({
		    position: loc,
		    map: map,
		    title: user
		});
		marker.info = new google.maps.InfoWindow({
		    content: '<b>'+user+'</b><br/>Time: '+time
		});
		marker.info.open(map, marker);
		bounds.extend(marker.position);
	    }

	    $(document).ready(function() {
		initialize();
<?php
	
	    foreach($lastUserPositions as $up) {
		$sumLatitudes += $up->getLatitude();
		$sumLongitudes += $up->getLongitude();
		
		echo "addMarker(".$up->getLatitude().", ".$up->getLongitude().", '".$up->getGroupUser()->getUser()->getName()."', '".date('Y M d, h:i:s A', $up->getTime())."');";
	    }
	    $cnt = count($lastUserPositions);
	    if ($cnt > 0) {
		$sumLatitudes = $sumLatitudes / $cnt;
		$sumLongitudes = $sumLongitudes / $cnt;
		
		echo "var mean = new google.maps.LatLng(".$sumLatitudes.", ".$sumLongitudes."); map.setCenter(mean); map.fitBounds(bounds);";
	    }

?>
	    });
	</script>

<?php
	    
	    
	} else {
	    printError("Hidden!");
	    redirect('index.php?module=groups&action=mygroups');
	}
	
    } else {
	printError("You do not belong to this group!");
	redirect('index.php?module=groups&action=mygroups');
    }  
?>

    </div>
</div>