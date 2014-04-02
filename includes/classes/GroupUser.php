<?php

if (!defined('IN_INDEX')) {
    exit;
}

include_once('CachedTable.php');
include_once('Users.php');
include_once('Groups.php');

class GroupUserException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
	parent::__construct($message, $code, $previous);
    }

    public function __toString() {
	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

class GroupUser extends CachedTable {
    
    const NEW_GROUPUSER_ID = -1;

    protected static $objectList = array();
    private $group = null;
    private $user = null;
    private $owner = null;

    private function __construct($id) {
	$this->id = $id;
    }

    public function __destruct() {
	if ($this->isLoaded()) {
	    parent::removeObject(GroupUser::$objectList, $this->id);
	}
    }

    public static function load($id) {
	if (!is_array(GroupUser::$objectList)) {
	    GroupUser::$objectList = array();
	}

	return parent::load(GroupUser::$objectList, 'group_user', $id, function($par) {
		    return GroupUser::loadVariables($par);
		});
    }

    protected static function loadVariables($parametersArray) {
	$object = new GroupUser($parametersArray["id"]);

	$object->user = $parametersArray["user"];
	$object->group = $parametersArray["group"];
	$object->owner = $parametersArray["owner"];

	return $object;
    }

    public static function create($user, $group, $owner) {

	$groupuser = new GroupUser(GroupUser::NEW_GROUPUSER_ID);

	if ($user == null || $group == null || $owner == null) {
	    throw new GroupUserException("Call to method 'GroupUser::create()' must not have null arguments!");
	}

	$groupuser->setUser($user);
	$groupuser->setGroup($group);
	$groupuser->setOwner($owner);
	
	$groupuser->save();

	return $groupuser;
    }

    public function save() {

	$database = Database::getDatabaseInstance();
	
	if ($database == null) {
	    throw new DatabaseException("Database connection failed. Impossible to send a SQL Query without a connection!");
	}
	
	if ($this->id != GroupUser::NEW_GROUPUSER_ID) {

	    if (!$this->isLoaded()) {
		throw new GroupUserException("GroupUser is not loaded!");
	    }
	    
	    $sqlUpdate = "UPDATE `group_user` SET `user` = '".$this->user."', `group` = '".$this->group."', `owner` = '".$this->owner."' WHERE `id` = ".$this->id." LIMIT 1;";
	    $query = $database->getPDOInstance()->query($sqlUpdate);
	   
	} else {

	    $sqlInsert = "INSERT INTO `group_user` (`user`, `group`, `owner`) VALUES ('".$this->user."', '".$this->group."', '".$this->owner."');";
	    $query = $database->getPDOInstance()->query($sqlInsert);

	    $newId = $database->getPDOInstance()->lastInsertId();

	    if ($newId > 0) {
		$this->isLoaded = true;
		$this->id = $newId;
		
		self::addObject(GroupUser::$objectList, $this);
	    }

	    if (!$this->isLoaded()) {
		throw new GroupUserException("An error occured while trying to insert the new GroupUser to the database.");
	    }
	}
    }

    public function getUser() {
	return Users::load($this->user);
    }

    public function setUser($user) {
	if (!($user instanceof Users)) {
	    throw new GroupUserException("The argument provided must be an 'Users' object!.");
	}
	
	$this->user = $user->getId();
    }
    
    public function getGroup() {
	return Groups::load($this->group);
    }

    public function setGroup($group) {
	if (!($group instanceof Groups)) {
	    throw new GroupUserException("The user id provided cannot be 0.");
	}
	
	$this->group = $group->getId();
    }
    
    public function getOwner() {
	return $this->owner;
    }

    public function setOwner($owner) {
	if ($owner == null) {
	    throw new GroupUserException("The owner argument provided cannot be null.");
	}
	
	$this->owner = (bool) $owner;
    }
}
