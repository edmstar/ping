<?php

if (!defined('IN_INDEX')) {
    exit;
}

include_once('Database.php');

class CachedTableException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

abstract class CachedTable extends Database {

    protected static $objectList; // must be an array
    protected $id;
    protected $isLoaded;

    protected abstract static function loadVariables($parametersArray);

    private final static function isCachedTableObject(&$object) {
        return ($object instanceOf CachedTable);
    }

    protected final static function addObject(&$objectList, &$object) {
        if (!self::isCachedTableObject($object)) {
            throw new CachedTableException("The object provided must be a subclass of CachedTable!");
        }

        if (!self::hasObject($objectList, $object)) {
            if ($object->isLoaded()) {
                $objectList[$object->getId()] = &$object;
            }
        }
    }

    protected final function hasObject(&$objectList, &$object) {
        if (!self::isCachedTableObject($object)) {
            throw new CachedTableException("The object provided must be a subclass of CachedTable!");
        }

        foreach ($objectList as $item) {
            if ($item->getId() == $object->getId()) {
                return true;
            }
        }

        return false;
    }

    protected final static function &getObject(&$objectList, $id) {
        foreach ($objectList as $item) {
            if ($item->getId() == $id) {
                return $item;
            }
        }

        $null = null;
        return $null;
    }

    protected final static function removeObject(&$objectList, $id) {
        foreach ($objectList as $item) {
            if ($item->getId() == $id) {
                unset($objectList[$item->getId()]);
            }
        }
    }

    protected static function load(&$objectList, $table, $id, $loadVariables) {

        $database = Database::getDatabaseInstance();

        if ($database == null) {
            throw new DatabaseException("Database connection failed. Impossible to send a SQL Query without a connection!");
        }

        $object = self::getObject($objectList, $id);

        if ($object == null) {
            $sql_search = "SELECT * FROM `" . $table . "` WHERE `id`=" . $id . " LIMIT 1;";
            $query = $database->getPDOInstance()->query($sql_search);

            if ($query->rowCount() < 1) {
                throw new CachedTableException("ID not found on table '" . $table . "'!");
            }

            $object = $loadVariables($query->fetch());
            $object->isLoaded = true;

            self::addObject($objectList, $object);
        }

        return $object;
    }

    public function getId() {
        return $this->id;
    }

    public function isLoaded() {
        return $this->isLoaded;
    }
    
    protected function dbQuery($sql) {
	
	$database = Database::getDatabaseInstance();
	
	if ($database == null) {
	    throw new DatabaseException("Database connection failed. Impossible to send a SQL Query without a connection!");
	}
	
	return $database->getPDOInstance()->query($sql);
    }

}

?>