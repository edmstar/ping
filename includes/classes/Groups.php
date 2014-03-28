<?php

if (!defined('IN_INDEX')) {
    exit;
}

include_once('CachedTable.php');

class GroupsException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
	parent::__construct($message, $code, $previous);
    }

    public function __toString() {
	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

class Groups extends CachedTable {
    
    const NEW_GROUP_ID = -1;

    protected static $objectList = array();
    private $name = null;

    private function __construct($id) {
	$this->id = $id;
    }

    private function __destruct() {
	if ($this->isLoaded()) {
	    parent::removeObject($this->id);
	}
    }

    public static function load($id) {
	if (!is_array(Groups::$objectList)) {
	    Groups::$objectList = array();
	}

	return parent::load(Groups::$objectList, 'groups', $id, function($par) {
		    return Groups::loadVariables($par);
		});
    }

    protected static function loadVariables($parametersArray) {
	$object = new Groups($parametersArray["id"]);

	$object->name = $parametersArray["name"];

	return $object;
    }

    public static function create($name) {

	$group = new Groups(Groups::NEW_GROUP_ID);

	if ($name == null) {
	    throw new GroupsException("Call to method 'Groups::create()' must not have null arguments!");
	}

	$group->setName($name);

	$group->save();

	return $group;
    }

    public function save() {

	$database = Database::getDatabaseInstance();
	
	if ($database == null) {
	    throw new DatabaseException("Database connection failed. Impossible to send a SQL Query without a connection!");
	}
	
	if ($this->id != Groups::NEW_GROUP_ID) {

	    if (!$this->isLoaded()) {
		throw new GroupsException("Group is not loaded!");
	    }
	    
	    $sqlUpdate = "UPDATE `groups` SET `name` = '".$this->name."' WHERE `id` = ".$this->id." LIMIT 1;";
	    $query = $database->getPDOInstance()->query($sqlUpdate);
	   
	} else {

	    $sqlInsert = "INSERT INTO `groups` (`name`) VALUES ('" . $this->name . "');";
	    $query = $database->getPDOInstance()->query($sqlInsert);

	    $newId = $database->getPDOInstance()->lastInsertId();

	    if ($newId > 0) {
		Groups::load($newId);
		
		$this->isLoaded = true;
		self::addObject(Groups::$objectList, $this);
	    }

	    if (!$this->isLoaded()) {
		throw new GroupsException("An error occured while trying to insert the new group to the database.");
	    }
	}
    }

    public function getName() {
	return $this->name;
    }

    public function setName($name) {
	if ($name == '') {
	    throw new GroupsException("The name provided cannot be null.");
	}
	$this->name = $name;
    }

}

?>