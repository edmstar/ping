<?php

include_once('CachedTable.php');

class UsersException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
	parent::__construct($message, $code, $previous);
    }

    public function __toString() {
	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

class Users extends CachedTable {

    const NEW_USER_ID = -1;

    protected static $objectList = array();
    private $email = null;
    private $password = null;
    private $name = null;
    private $lostPassword = null;

    private function __construct($id) {
	$this->id = $id;
    }

    private function __destruct() {
	if ($this->isLoaded()) {
	    parent::removeObject($this->id);
	}
    }

    public static function load($id) {
	if (!is_array(Users::$objectList)) {
	    Users::$objectList = array();
	}

	return parent::load(Users::$objectList, 'users', $id, function($par) {
		    return Users::loadVariables($par);
		});
    }

    protected static function loadVariables($parametersArray) {
	$object = new Users($parametersArray["id"]);

	$object->email = $parametersArray["email"];
	$object->password = $parametersArray["password"];
	$object->name = $parametersArray["name"];
	$object->lostPassword = $parametersArray["lostpassword"];

	return $object;
    }

    public static function create($email, $password, $name) {

	$user = new Users(USERS::NEW_USER_ID);

	if ($email == null || $password == null || $name == null) {
	    throw new UsersException("Call to method 'Users::create()' must not have null arguments!");
	}

	$user->setEmail($email);
	$user->setPassword($password);
	$user->setName($name);

	$user->save();

	return $user;
    }

    public function save() {

	$database = Database::getDatabaseInstance();
	
	if ($database == null) {
	    throw new DatabaseException("Database connection failed. Impossible to send a SQL Query without a connection!");
	}
	
	if ($this->id != USERS::NEW_USER_ID) {

	    if (!$this->isLoaded()) {
		throw new UsersException("User is not loaded!");
	    }
	    
	    $sqlUpdate = "UPDATE `users` SET `email` = '".$this->email."', `password` = '".$this->password."', `name` = '".$this->name."' WHERE `id` = ".$this->id." LIMIT 1;";
	    $query = $database->getPDOInstance()->query($sqlUpdate);
	   
	} else {

	    $sqlInsert = "INSERT INTO `users` (`email`, `password`, `name`) VALUES ('" . $this->email . "', '" . $this->password . "', '" . $this->name . "');";
	    $query = $database->getPDOInstance()->query($sqlInsert);

	    $newId = $database->getPDOInstance()->lastInsertId();

	    if ($newId > 0) {
		Users::load($newId);
		
		$this->isLoaded = true;
		self::addObject(Users::$objectList, $this);
	    }

	    if (!$this->isLoaded()) {
		throw new UsersException("An error occured while trying to insert the new user to the database.");
	    }
	}
    }

    public function getEmail() {
	return $this->email;
    }

    public function setEmail($email) {
	if (!$this->checkEmail($email)) {
	    throw new UsersException("The email provided is invalid.");
	}
	$this->email = $email;
    }

    public function getPassword() {
	return $this->password;
    }

    public function setPassword($password) {
	if ($password == '') {
	    throw new UsersException("The password provided cannot be null.");
	} else {
	    if (strlen($password) < 6) {
		throw new UsersException("The password provided must have at least 6 characteres");
	    }
	}
	$this->password = md5($password);
    }

    public function getName() {
	return $this->name;
    }

    public function setName($name) {
	if ($name == '') {
	    throw new UsersException("The name provided cannot be null.");
	}
	$this->name = $name;
    }

    public function getLostPassword() {
	return $this->lostPassword;
    }

    public function setLostPassword($lostPassword) {
	$this->lostPassword = $lostPassword;
    }

    private function checkEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}

?>