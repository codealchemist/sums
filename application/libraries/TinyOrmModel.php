<?php

/**
 * Tiny ORM provides a few very basic ORM features for your models.
 * 
 * @author Alberto Miranda <alberto.php@gmailc.com>
 */
class TinyOrmModel {
    protected $CI = null;
    protected $db = null;
    protected $collectionMap = null;
    
    /**
     *
     * @var Guid 
     */
    protected $Guid = null; //guid helper
    private $wasLoaded = false; //indicates if the object was loaded from db

    public function __construct($id = null) {
        $this->CI =& get_instance();
        $this->db = MongoDBAdapter::getInstance();
        
        //load guid helper
        $this->CI->load->helper('guid');
        $this->Guid = new Guid;
        
        //load from db
        if ($id) $this->load( new MongoId($id) );
        
        //if no collection map was provided build the dafault one
        if (!$this->collectionMap) $this->generateCollectionMap();
    }
    
    public function wasLoaded() {
        return $this->wasLoaded;
    }
    
    /**
     * Automatically generates a collection map when it was not provided by
     * mapping each property to a collection property with the same name.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    private function generateCollectionMap() {
        $this->log('generateCollectionMap: auto-generating collection');
        $properties = $this->getChildProperties();
        $collectionMap = array();
        foreach($properties as $propertyName) {
            $collectionMap[$propertyName] = $propertyName;
        }
        $this->collectionMap = $collectionMap;
    }
    
    /**
     * Default log for current class.
     * Uses default system log adding class signature to it.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $message
     */
    private function log($message) {
        log_message('debug', "[ TINY-ORM-MODEL ]------------> $message");
    }
    
    /**
     * Returns ID field for current object.
     * If none is specified returns default one: "id".
     * ID field can be specified on each child class with the $idField property.
     * For example, if the id field for the collection "videos" is "id_video", then
     * you can set the following ID field property in the Videos class:
     * protected $idField = 'id_video';
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    private function getIdField() {
        if (!property_exists($this, 'idField')) return 'id';
        return $this->idField;
    }
    
    /**
     * Loads requested object from db.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param int $id
     */
    protected function load($id) {
        $this->log(__METHOD__);
        if (!$id) {
            throw new Exception("'id' must be specified!");
        }
        
        $collection = $this->collectionName;
        $result = $this->db
            ->where("_id", $id)
            ->get($collection);
        $this->log("LOADED DATA from '$collection': " . print_r($result, true));
        
        if (empty($result)) {
            return null;
        }
        
        foreach($result[0] as $key => $value) {
            $this->setPropertyFromField($key, $value);
        }
        
        $this->wasLoaded = true;
        //$this->log("LOADED '$collection' OBJECT: " . print_r(self, true));
    }
    
    /**
     * Fills object with passed data array.
     * Every array key matching a collection field will have its value being set with
     * corresponding setter.
     * For example, if a field name is called "email" it must have a matching
     * "setEmail" method. Passing a data array like:
     * array('email' => 'alberto.php@gmail.com')
     * 
     * Will translate into:
     * $this->setEmail('alberto.php@gmail.com');
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $data
     */
    public function fill($data) {
        $this->log(__METHOD__);
        foreach($data as $key => $value) {
            $this->setPropertyFromField($key, $value);
        }
    }
    
    /**
     * Sets property from passed field name and value.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $fieldName
     * @param mixed $value
     * @return boolean true if setter was successfully called, false if not exists
     */
    private function setPropertyFromField($fieldName, $value) {
        $propertyName = $this->underscoreToCamelCase($fieldName);
        $method = "set" . ucfirst($propertyName);
        if (method_exists($this, $method)) {
            $this->$method($value);
            return true;
        }
        
        $this->log("WARNING: inexisting setter called: $method");
        return false;
    }
    
    /**
     * Converts undercore to camelCase notation.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $string
     * @return string
     */
    private function underscoreToCamelCase($string) {
        if (strpos($string, '_') === false) return $string;
        
        $parts = explode('_', $string);
        $camelCase = '';
        foreach($parts as $part) {
            $camelCase .= ucfirst($part);
        }
        return $camelCase;
    }
    
    /**
     * Returns array with child class properties.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @return array
     */
    private function getChildProperties() {
        $childClass = get_class($this);
        $this->log("CHILD CLASS: $childClass");
        $reflection = new ReflectionClass($childClass);
        $properties = $reflection->getProperties();
        
        $propertiesArray = array();
        foreach($properties as $property) {
            $propertiesArray[] = $property->name;
        }
        return $propertiesArray;
    }
    
    /**
     * Returns matching field name in $collectionMap for passed property name.
     * Returns false if not found.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $propertyName
     * @return string $fieldName
     */
    private function getFieldName($propertyName) {
        $reversedCollectionMap = array_flip($this->collectionMap);
        if (array_key_exists($propertyName, $reversedCollectionMap)) {
            return $reversedCollectionMap[$propertyName];
        }
        return false;
    }
    
    /**
     * Returns matching property name in $collectionMap for passed field name.
     * Returns false if not found.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $fieldName
     * @return string $propertyName
     */
    private function getPropertyName($fieldName) {
        $collectionMap = $this->collectionMap;
        if (array_key_exists($fieldName, $collectionMap)) {
            return $collectionMap[$fieldName];
        }
        return false;
    }
    
    /**
     * Returns true if passed property is set as hidden.
     * Uses child class $hiddenProperties protected property.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $propertyName
     * @return boolean
     */
    private function isHiddenProperty($propertyName) {
        if (!property_exists($this, 'hiddenProperties')) return false;
        if (!in_array($propertyName, $this->hiddenProperties)) return false;
        return true;
    }
    
    /**
     * Returns array representation of current object.
     * Returns only properties listed in $properties; if empty returns all.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $properties Return only properties listed here
     * @param boolean $returnHidden Return hidden properties too
     * @return array
     */
    public function toArray($properties = null, $returnHidden = false) {
        $this->log(__METHOD__);
        $array = array();
        
        //only requested properties
        if (!empty($properties)) {
            foreach($properties as $key) {
                if (!$returnHidden && $this->isHiddenProperty($key)) {
                    $this->log("TO-ARRAY: SKIPPING hidden property: $key");
                    continue;
                }
                
                $method = 'get' . ucfirst($key);
                if (method_exists($this, $method)) {
                    $fieldName = $this->getFieldName($key);
                    if (!$fieldName) $fieldName = $key; //default to key for non mapped properties
                    $array[$fieldName] = $this->$method();
                }
            }
            return $array;
        }
        
        //all properties
        $childProperties = $this->getChildProperties();
        foreach($childProperties as $propertyName) {
            if (!$returnHidden && $this->isHiddenProperty($propertyName)) {
                $this->log("TO-ARRAY: SKIPPING hidden property: $propertyName");
                continue;
            }
            
            $method = 'get' . ucfirst($propertyName);
            $this->log("TO-ARRAY: method: $method");
            if (method_exists($this, $method)) {
                //$this->log("TO-ARRAY: method: $method --> EXISTS, call");
                $fieldName = $this->getFieldName($propertyName);
                if (!$fieldName) $fieldName = $propertyName; //default to key for non mapped properties
                $array[$fieldName] = $this->$method();
            }
        }
        return $array;
    }
    
    /**
     * Ensures null values for empty ones on passed keys for given array.
     * Returns passed array with null values for passed keys if they were false,
     * empty string or 0.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $data
     * @param array $keys
     * @return array
     */
    private function ensureNullForEmptyValues($data, $keys) {
        foreach($keys as $key) {
            if (array_key_exists($key, $data)) {
                if (empty($data[$key])) $data[$key] = null;
            }
        }
        
        return $data;
    }
    
    /**
     * Basic data sanitization.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $data
     */
    private function sanitize($data) {
        if (property_exists($this, 'nullableFields')) {
            $data = $this->ensureNullForEmptyValues($data, $this->nullableFields);
        }
        return $data;
    }
    
    /**
     * Creates record for current object with passed data.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $data
     * @return boolean
     */
    public function create($data) {
        $this->log(__METHOD__);
        $collection = $this->collectionName;
        $this->log("CREATE RECORD in '$collection': " . print_r($data, true));
        $data = $this->sanitize($data);
    	$id = $this->db->insert($collection, $data);
        
        //$id = $this->db->insert_id(); //mysql compatible
        return $id;
    }
    
    /**
     * Updates passed record id of current object collection with passed data.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, $data) {
        $this->log(__METHOD__);
        $collection = $this->collectionName;
        $this->log("UPDATE RECORD in '$collection': " . print_r($data, true));
        
        $data = $this->sanitize($data);
        return $this->db->update($collection, $data, "_id = $id");
    }
    
    /**
     * Fills current object with obtained database result for passed criteria.
     * If more than one result is found will use the first one.
     * Returns true if a record was found and the object filled.
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $criteria
     * @return boolean
     */
    public function get($criteria = null) {
        $this->log(__METHOD__);
        
        //construct where conditions
        $where = '';
        if ($criteria) {
            $whereFields = '';
            foreach($criteria as $key => $value) {
                if ($whereFields!='') $whereFields .= ' and ';
                $whereFields .= "$key = '$value'";
            }
            $where = "where $whereFields";
        }
        
        //query
        $collection = $this->collectionName;
        if (!$collection){
            $message = __METHOD__ . ' EXCEPTION: NO COLLECTION NAME! Create the object setting the ImageType.';
            $this->log($message);
            throw new Exception($message);
        }
        $query = "select * from $collection $where";
        $this->log("QUERY: $query");
        $result = $this->db
            ->query($query)
            ->result();
        
        //return false if no result
        if (empty($result)) return false;
        
        //fill object and return true
        $data = $result[0];
        $this->fill($data);
        return true;
    }
    
    /**
     * Returns one result for passed criteria.
     * If criteria is empty return first matched result.
     * If $getObjects is true will return an object of the current type instead 
     * of the database result.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $criteria
     * @param boolean $getObject
     * @return mixed array or object
     */
    public function findOne($criteria = null, $getObject = false) {
        $this->log(__METHOD__);
        
        //query
        $collection = $this->collectionName;
        if (!$collection){
            $message = __METHOD__ . ' EXCEPTION: NO COLLECTION NAME! Set "$collectionName" in class "' . get_class() . '".';
            $this->log($message);
            throw new Exception($message);
        }
        
        $this->log("CRITERIA: " . print_r($criteria, true));
        $result = $this->db
            ->where($criteria)
            ->get($this->collectionName);
        
        //if no results return false
        if (empty($result)) return false;
        
        //return database results
        $data = $result[0];
        if (!$getObject) {
            $this->log('returning database result');
            return $data;
        }
        
        //return filled object
        $this->log('returning object');
        $this->fill($data);
        return $this;
    }
    
    /**
     * Saves current object to database.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function save() {
        $this->log(__METHOD__);
        
        //if was loaded from db update existing record
        if ($this->wasLoaded) {
            return $this->update($this->getId(), $this->toArray());
        }
        
        //create new record
        return $this->create($this->toArray(null, true));
    }
    
    /**
     * Deletes database record matching passed id.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param int $id
     */
    public function remove($id = null) {
        $this->log(__METHOD__ . ": ID: $id");
        
        if ($this->wasLoaded){
            $id = $this->getId();
        } else {
            if ($id === null) {
                throw new Exception('Trying to delete an Agenda Entry without specifying its ID!');
            }
        }
        
        //create delete query and execute it
        $collection = $this->collectionName;
        $idField = $this->getIdField();
        $this->db->delete($collection, array($idField => $id));
        return true;
    }
}
