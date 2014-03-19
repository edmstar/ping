<?php

	include_once('CachedTable.php');

	class Users extends CachedTable
	{
		protected static $objectList = array();

		private $email 			= null;
		private $password 		= null;
		private $name 			= null;
		private $lostPassword 	= null;


		private function __construct($id)
		{
			$this->id = $id;
		}

		private function __destruct()
		{
			parent::removeObject($this->id);
		}
		
		public static function load($id)
		{
			if (!is_array(Users::$objectList))
				Users::$objectList = array();

			return parent::load(Users::$objectList, 'users', $id, function($par) { return Users::loadVariables($par); });
		}

		protected static function loadVariables($parametersArray)
		{
			$object = new Users($parametersArray["id"]);

			$object->setEmail($parametersArray["email"]);
			$object->setPassword($parametersArray["password"]);
			$object->setName($parametersArray["name"]);
			$object->setLostPassword($parametersArray["lostpassword"]);

			return $object;

		}

		public function getEmail()
		{
			return $this->email;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}

		public function getPassword()
		{
			return $this->password;
		}

		public function setPassword($password)
		{
			$this->password = $password;
		}

		public function getName()
		{
			return $this->name;
		}

		public function setName($name)
		{
			$this->name = $name;
		}

		public function getLostPassword()
		{
			return $this->lostPassword;
		}

		public function setLostPassword($lostPassword)
		{
			$this->lostPassword = $lostPassword;
		}
	}

?>