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

    public function __destruct() {
	if ($this->isLoaded()) {
	    parent::removeObject(Groups::$objectList, $this->id);
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
	    $query = $this->dbQuery($sqlUpdate);
	   
	} else {

	    $sqlInsert = "INSERT INTO `groups` (`name`) VALUES ('" . $this->name . "');";
	    $query = $this->dbQuery($sqlInsert);

	    $newId = $database->getPDOInstance()->lastInsertId();

	    if ($newId > 0) {
		$this->isLoaded = true;
		$this->id = $newId;
		
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
    
    public function addOwner($user) {
	
	if (!($user instanceof Users)) {
	    throw new GroupsException('The argument must be a member of the "Users" class.');
	}
	
	foreach($user->getGroupUsers() as $groupuser) {
	    if ($groupuser->getGroup()->getId() == $this->id) {
		$groupuser->setOwner(true);
		$groupuser->save();
		return true;
	    }
	}
	
	$gu = GroupUser::create($user, $this, true);
    }
    
    public function addUser($user) {
	
	if (!($user instanceof Users)) {
	    throw new GroupsException('The argument must be a member of the "Users" class.');
	}
	
	foreach($user->getGroupUsers() as $groupuser) {
	    if ($groupuser->getGroup()->getId() == $this->id) {
		return true;
	    }
	}
	
	$gu = GroupUser::create($user, $this, false);
    }
    
    public function getUsers() {
	
	$sql = "SELECT u.`id` FROM `group_user` as gu, `users` as u WHERE u.`id`=gu.`user` AND gu.`group`=".$this->id." ORDER BY gu.`owner` DESC, u.`name` ASC;";
	
	$query = $this->dbQuery($sql);
	
	$list = array();
	
	if ($query->rowCount() >= 1) {
	    $rows = $query->fetchAll();
	    foreach($rows as $row) {
		$user = Users::load($row['id']);
		if ($user->isLoaded()) {
		    $list[] = $user;
		}
	    }
	}
	
	return $list;
    }
    
    public function getLastUserPositions() {
	
	$users = $this->getUsers();
	
	$list = array();
	
	foreach($users as $user) {
	    $up = $user->getLastPosition($this);
	    if ($up != null) {
		$list[] = $up;
	    }
	}
	
	return $list;
    }
}

?>