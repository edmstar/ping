<?php

	class DatabaseException extends Exception 
	{
		
	    public function __construct($message, $code = 0, Exception $previous = null)
	    {
	        parent::__construct($message, $code, $previous);
	    }

	    public function __toString()
	    {
	        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	    }

	}

	// This class manages the connection to the MySQL database
	class Database
	{

		private $localDatabase = null;
		private $db_name = null;
		private $db_server = null;
		private $db_user = null;
		private $db_password = null;

		// constructs the class and saves the values for the local variables
		// this values are gonna be used in the mysql connection
		// created by a PDO object
		public function __construct($server, $user, $password, $name)
		{

			if ($server == null || $user == null || $password == null || $name == null)
				throw new DatabaseException("Parameters provided cannot be null");

			$this->db_name = $name;
			$this->db_server = $server;
			$this->db_user = $user;
			$this->db_password = $password;
		}

		// tries to connect to the database
		// saves a null value in the $localDatabase variable
		// if the connection is not initialized
		public function connect()
		{
			var $dsn = "mysql:dbname=".$this->db_name.";host=".$this->db_server;

			try
			{
				$this->localDatabase = new PDO($dsn, $this->db_user, $this->db_password);
			}
			catch (PDOException $e)
			{
				$this->localDatabase = null;
				throw new DatabaseException("Connection failed: " . $e->__toString());
			}
		}

		// returns the local database instance (PDO Object)
		public function getPDOInstance()
		{
			return $this->localDatabase;
		}

		// checks if the connection is active
		public function isConnected()
		{
			return ($this->localDatabase instanceOf PDO);
		}

	}

?>