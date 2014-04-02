<div class="jumbotron">
    <div class="row">
	<div class="col-md-6">
    
	    <?php
		$groupsList = $userLogin->getGroups();

		if(count($groupsList) == 0) {
	    ?>
	    
	    You don't belong to any group yet. Try <a href='index.php?module=groups&action=new'>creating a new group</a> now!<br/>
	    If you want to join a group that already exists, you must be invited.
	    
	    <?php
		} else {
	    ?>
	    
	    <a href='index.php?module=groups&action=new'>Create a new group</a><br/><br/>
	    <table id="groups_table" class="table table-striped table-bordered">
		<tr><th>Group Name</th><th># of users</th><th><a href="index.php?module=positions&action=update&group=all" id='ajax_a_all'>Update All</a></th></tr>
		
		<?php
		
		    foreach($groupsList as $group) {
			echo '<tr><td><a href="index.php?module=groups&action=view&id='.
				$group->getId().'">'.$group->getName()."</a></td><td>".count($group->getUsers())."</td><td><a href='index.php?module=positions&action=update&group=".$group->getId()."' id='ajax_a_".$group->getId()."'>Update Position</a></td></tr>";
		    }
		
		?>
		
	    </table>
	    <?php
	    
	    
		}
	    ?>
	</div>
	<div class="col-md-6">
	    <?php loadGoogleMapsAPI(); ?>

	    <h3>Your location</h3><br/>
	    <div id="map-canvas"></div>
	</div>
    </div>
</div>

<script>
    
    var myPosition;
    var initialLocation;
    var siberia = new google.maps.LatLng(60, 105);
    var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
    var browserSupportFlag =  new Boolean();
    
    function addLinkEvents() {
	$("a[id^='ajax_a_']").each(
	  function(index) {
	      $(this).on('click', function(eventObject) {
		 url = $(this).attr('href');
		 $.ajax({
		     type: "POST",
		     url: url+'&ajax=1',
		     data: {
			 latitude: myPosition.coords.latitude,
			 longitude: myPosition.coords.longitude
		     }
		 }).done(function(msg) {
		     $("#scpt").html(msg);
		 });
		 eventObject.preventDefault();
		 return false;
	      });
	  }
	);
    }
      
    function initialize() {
      var myOptions = {
	center: new google.maps.LatLng(49.2569777,-123.123904),
	zoom: 17,
	mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
      var options = {
	enableHighAccuracy: true,
	timeout: 5000,
	maximumAge: 0
      };
      // Try W3C Geolocation (Preferred)
      if(navigator.geolocation) {

	browserSupportFlag = true;
	navigator.geolocation.getCurrentPosition(function(position) {
	  myPosition = position;
	  initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	  map.setCenter(initialLocation);
	  var marker = new google.maps.Marker({
		position: initialLocation,
		map: map,
		title:"Your location now!"
	    });
	}, function() {
	  handleNoGeolocation(browserSupportFlag);
	}, options);
      }
      // Browser doesn't support Geolocation
      else {

	browserSupportFlag = false;
	handleNoGeolocation(browserSupportFlag);
      }
      
      function handleNoGeolocation(errorFlag) {
	if (errorFlag == true) {
	  alert("Geolocation service failed.");
	  initialLocation = newyork;
	} else {
	  alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
	  initialLocation = siberia;
	}
	map.setCenter(initialLocation);
      }
      
    }
		
    $(document).ready(function() {
	initialize();
	addLinkEvents();
    });
</script>