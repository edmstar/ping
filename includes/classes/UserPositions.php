<?php

if (!defined('IN_INDEX')) {
    exit;
}

include_once('CachedTable.php');
include_once('Users.php');

class UserPositionsException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
	parent::__construct($message, $code, $previous);
    }

    public function __toString() {
	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

class UserPositions extends CachedTable {
    
    const NEW_USERPOSITION_ID = -1;

    protected static $objectList = array();
    
    private $groupUser = null;
    private $time = null;
    private $hidden = null;
    private $latitude = null;
    private $longitude= null;
    
    private function __construct($id) {
	$this->id = $id;
    }

    public function __destruct() {
	if ($this->isLoaded()) {
	    parent::removeObject(UserPositions::$objectList, $this->id);
	}
    }

    public static function load($id) {
	if (!is_array(UserPositions::$objectList)) {
	    UserPositions::$objectList = array();
	}

	return parent::load(UserPositions::$objectList, 'user_positions', $id, function($par) {
		    return UserPositions::loadVariables($par);
		});
    }

    protected static function loadVariables($parametersArray) {
	$object = new UserPositions($parametersArray["id"]);

	$object->groupUser = $parametersArray["group_user"];
	$object->time = $parametersArray["time"];
	$object->hidden = $parametersArray["hidden"];
	$object->latitude = $parametersArray["latitude"];
	$object->longitude = $parametersArray["longitude"];

	return $object;
    }
    
    public static function create($groupUser, $time, $hidden, $latitude, $longitude) {

	$userposition = new UserPositions(UserPositions::NEW_USERPOSITION_ID);

	if ($groupUser == null || $time == null || $hidden == null || $latitude == null || $longitude == null) {
	    throw new UserPositionsException("Call to method 'UserPositions::create()' must not have null arguments!");
	}

	$userposition->setGroupUser($groupUser);
	$userposition->setTime($time);
	$userposition->setHidden($hidden);
	$userposition->setLatitude($latitude);
	$userposition->setLongitude($longitude);
	
	$userposition->save();

	return $userposition;
    }

    public function save() {

	$database = Database::getDatabaseInstance();
	
	if ($database == null) {
	    throw new DatabaseException("Database connection failed. Impossible to send a SQL Query without a connection!");
	}
	
	if ($this->id != UserPositions::NEW_USERPOSITION_ID) {

	    if (!$this->isLoaded()) {
		throw new UserPositionsException("UserPositions is not loaded!");
	    }
	    
	    $sqlUpdate = "UPDATE `user_positions` SET `group_user` = '".$this->groupUser."', `time` = '".$this->time."', `hidden` = ".$this->hidden.", `latitude` = '".$this->latitude."', `longitude` = '".$this->longitude."' WHERE `id` = ".$this->id." LIMIT 1;";
	    $query = $database->getPDOInstance()->query($sqlUpdate);
	   
	} else {

	    $sqlInsert = "INSERT INTO `user_positions` (`group_user`, `time`, `hidden`, `latitude`, `longitude`) VALUES ('".$this->groupUser."', '".$this->time."', ".$this->hidden.", '".$this->latitude."', '".$this->longitude."');";
	    $query = $database->getPDOInstance()->query($sqlInsert);

	    $newId = $database->getPDOInstance()->lastInsertId();

	    if ($newId > 0) {
		$this->isLoaded = true;
		$this->id = $newId;
		
		self::addObject(UserPositions::$objectList, $this);
	    }

	    if (!$this->isLoaded()) {
		throw new UserPositionsException("An error occured while trying to insert the new GroupUser to the database.");
	    }
	}
    }
    

    public function getGroupUser() {
	return GroupUser::load($this->groupUser);
    }

    public function setGroupUser($groupUser) {
	if (!($groupUser instanceof GroupUser)) {
	    throw new UserPositionsException("The argument provided must be an 'GroupUser' object!.");
	}
	$this->groupUser = $groupUser->getId();
    }

    public function getTime() {
	    return $this->time;
    }

    public function setTime($time) {
	    $this->time = $time;
    }

    public function getHidden() {
	    return $this->hidden;
    }

    public function setHidden($hidden) {
	    $this->hidden = $hidden;
    }

    public function getLatitude() {
	    return $this->latitude;
    }

    public function setLatitude($latitude) {
	    $this->latitude = $latitude;
    }

    public function getLongitude() {
	    return $this->longitude;
    }

    public function setLongitude($longitude) {
	    $this->longitude = $longitude;
    }
}

