<?php

/**
 * Tiny ORM Collection provides a few very basic ORM features for your model
 * collections.
 * 
 * @author Alberto Miranda <alberto.php@gmailc.com>
 */
class TinyOrmCollection {
    protected $CI = null;
    protected $db = null;
    protected $pagination = array(
        'itemsPerPage' => 25
    );  

    public function __construct() {
        $this->CI =& get_instance();
        $this->db = $this->CI->db;
    }
    
    /**
     * Default log for current class.
     * Uses default system log adding class signature to it.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $message
     */
    private function log($message) {
        log_message('debug', "[ TINY-ORM-COLLECTION ]------------> $message");
    }
    
    private function getWhereFields($criteria, $logicalOperation = 'and') {
        $whereFields = '';
        
        foreach ($criteria as $key => $value) {
            if ($whereFields!='') $whereFields .= " $logicalOperation ";
            if (is_array($value)) {
                $whereFields .= '(' . $this->getWhereFields($value, $key) . ')'; //recurse for array values (or, and)
                continue;
            }
            
            $comparison = '=';
            if (preg_match("/^%/", $value) or preg_match("/%$/", $value)) $comparison = 'like';
            $whereFields .= "$key $comparison '$value'";
        }
        return $whereFields;
    }
    
    /**
     * Returns results for passed criteria.
     * If criteria is empty return all available results.
     * If $getObjects is true will return a collection of objects of the current
     * type instead of the database result.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $criteria
     * @param array $fields
     * @param array $pagination ["page": ..., "itemsPerPage": ...]
     * @param boolean $getObjects
     * @return array Novedad
     */
    public function find($criteria = null, $fields = null, $pagination = null, $getObjects = false) {
        $this->log(__METHOD__);
        
        //construct where conditions
        $where = '';
        if ($criteria) {
            $whereFields = $this->getWhereFields($criteria);
            $where = "where $whereFields";
        }
        
        //construct fields selection
        $fieldsQuery = '*';
        if ($fields) {
            $fieldsQuery = '';
            foreach ($fields as $fieldName) {
                if (!empty($fieldsQuery)) $fieldsQuery.=',';
                $fieldsQuery.="$fieldName";
            }
        }
        
        //query
        $this->log('returning database result');
        $table = $this->tableName;
        if (!$table){
            $message = __METHOD__ . ' EXCEPTION: NO TABLE NAME for this collection!';
            $this->log($message);
            throw new Exception($message);
        }
        
        //get total
        $total = $this->getTotal($where);
        $nextPage = null;
        if ($this->hasNext($total, $pagination)) {
            $this->log("PAGINATION: HAS NEXT PAGE");
            $nextPage = $this->getNextPage($pagination);
        } else {
            $this->log("PAGINATION: LAST PAGE");
        }
        
        $limit = $this->getLimit($pagination);
        $query = "select $fieldsQuery from $table $where $limit";
        $this->log("QUERY: $query");
        $result = $this->db
            ->query($query)
            ->result();
        
        //return database results
        if (!$getObjects) {
            $resultWithPaginationData = array(
                'result' => $result,
                'next' => $nextPage
            );
            return $resultWithPaginationData;
        }
        
        //return objects collection
        $this->log('returning objects collection');
        $collection = array();
        $class = $this->collectionOf;
        foreach($result as $dataItem) {
            /* @var $object TinyOrmModel */
            $object = new $class();
            $object->fill($dataItem);
            $collection[] = $object;
        }
        
        //return
        $collectionWithPaginationData = array(
            'result' => $collection,
            'next' => $nextPage
        );
        return $collectionWithPaginationData;
    }
    
    /**
     * Returns next page number.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $pagination
     * @return int
     */
    private function getNextPage($pagination) {
        $page = $this->getPage($pagination);
        $nextPage = ++$page;
        $this->log("PAGINATION: next page: $nextPage");
        return $nextPage;
    }
    
    /**
     * Returns true if there's a next page with more records.
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param int $total
     * @param array $pagination
     * @return boolean
     */
    private function hasNext($total, $pagination) {
        $page = $this->getPage($pagination);
        $itemsPerPage = $this->getItemsPerPage($pagination);
        
        $lastShownRecord = $page * $itemsPerPage;
        if ($page === 0) $lastShownRecord = $itemsPerPage;
        $this->log("PAGINATION: last shown record: $lastShownRecord");
        $this->log("PAGINATION: total: $total");
        if ($lastShownRecord < $total) return true;
        return false;
    }
    
    /**
     * Returns total records for passed where clause on current table.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $where
     * @return int
     */
    private function getTotal($where) {
        $table = $this->tableName;
        $query = "select count(*) as total from $table $where";
        $result = $this->db
            ->query($query)
            ->result();
        
        $total = $result[0]->total;
        $this->log("TOTAL: $total");
        return $total;
    }
    
    /**
     * Returns page.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $pagination
     * @return int
     */
    private function getPage($pagination) {
        $page = 0;
        if (empty($pagination)) return $page;
        
        if (array_key_exists('page', $pagination)) {
            $page = $pagination['page'];
        }
        return $page;
    }
    
    /**
     * Returns items per page.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $pagination
     * @return int
     */
    private function getItemsPerPage($pagination) {
        $itemsPerPage = $this->pagination['itemsPerPage'];
        if (empty($pagination)) return $itemsPerPage;
            
        if (array_key_exists('itemsPerPage', $pagination)) {
            $itemsPerPage = $pagination['itemsPerPage'];
        }
        return $itemsPerPage;
    }
    
    /**
     * Returns limit query part.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $pagination
     * @return string
     */
    private function getLimit($pagination) {
        //defaults
        $page = $this->getPage($pagination);
        $itemsPerPage = $this->getItemsPerPage($pagination);
        $skip = $page * $itemsPerPage;
        
        //set limit and return it
        $limit = "limit $skip, $itemsPerPage";
        return $limit;
    }
}