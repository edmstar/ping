<?php

	if (!defined('IN_INDEX')) { exit; }

	class SecurityException extends Exception
	{
		CONST LOCALMESSAGE = "";

	    public function __construct(Exception $previous = null) {
	        parent::__construct(self::LOCALMESSAGE, 0, $previous);
	    }

		// custom string representation of object
	    public function __toString() {
	        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	    }
	}

	class Security {

		private $getVars = array();
		private $postVars = array();
		private $requestVars = array();

		private $prot = true;

		public function __construct() {

			$this->getVars = $_GET;
			$this->requestVars = $_REQUEST;
			$this->postVars = $_POST;

			$_GET = null;
			$_POST = null;
			$_REQUEST = null;
		}

		public function runProtection($run = true) {

			$this->prot = $run;

		}

		public function varGet($var) {

			if (!isset($this->getVars[$var]))
				return null;

			if (!$this->prot)
				return $this->getVars[$var];

			if ( strtolower($this->getVars[$var]) == strtolower($this->checkString($this->getVars[$var])) )
			{
				return $this->checkCharacters($this->getVars[$var]);
			}
			else
			{
				throw new SecurityException();
			}
		}

		public function varPost($var, $html = false) {

			if (!$this->prot)
				return $this->postVars[$var];

			$value = $html ? $this->checkHTMLString($this->postVars[$var]) : $this->checkString($this->postVars[$var]);
			
			if ( strtolower($this->postVars[$var]) == strtolower($value) )
			{
				if (!$html) return utf8_decode($this->checkCharacters($this->postVars[$var]));
				return utf8_decode($this->postVars[$var]);
			}
			else
			{
				throw new SecurityException();
			}

		}

		public function varPostArray($var, $html = false) {
			$vetor = array();

			if (!$this->prot)
				return $this->postVars[$var];

			if (is_array($this->postVars[$var])) 
			{
				foreach($this->postVars[$var] as $element)
				{
					$value = $html ? $this->checkHTMLString($element) : $this->checkString($element);
					if ( strtolower($element) == strtolower($value) ) 
					{
						$vetor[] = utf8_decode($this->checkCharacters($element));
					} else {
						throw new SecurityException();
					}
				}
			}

			return $vetor;
		}

		public function getPost() {

			$ret = array();

			if (count($this->postVars) == 0)
				return array();

			foreach($this->postVars as $indice => $valor)
			{
				$ret[$indice] = $this->varPost($indice);
			}

			return $ret;
		}

		public function value($var) {

			if (!$this->prot) 
				return $this->requestVars[$var];

			if ( strtolower($this->requestVars[$var]) == strtolower($this->checkString($this->requestVars[$var])) )
			{
				return utf8_decode($this->checkCharacters($this->requestVars[$var]));
			}
			else
			{
				throw new SecurityException();
			}
		}

		public function checkString($sql) {

			$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop database|drop table|show tables|#|\*|--|\\\\)/"),"",$sql); //Remove comandos SQL
			$sql = trim($sql); //Limpa espaços vazio
			$sql = strip_tags($sql); //Tira tags html e php
			$sql = addslashes($sql); //Adiciona barras invertidas a uma string
			return $sql;

		}

		public function checkCharacters($sql) {

			$sql = trim($sql); //Limpa espaços vazio
			$sql = strip_tags($sql); //Tira tags html e php
			$sql = addslashes($sql); //Adiciona barras invertidas a uma string
			return $sql;

		}

		public function checkHTMLString($sql) {

			$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop database|drop table|show tables|#|\*|--|\\\\)/"),"",$sql); //Remove comandos SQL
			return $sql;

		}

// this should no be here. to be moved
/*		function Redirect($url, $tempo)	{
			$url = str_replace('&amp;', '&', $url);
				
			if($tempo > 0) {
				echo "<script>red_timeout = setTimeout('redir(\'$url\')', $tempo);</script>";
			} else {
				@ob_flush();
				@ob_end_clean();
				echo "<script>window.location='$url';</script>";
				exit;
			}
		}

	}
*/

?>