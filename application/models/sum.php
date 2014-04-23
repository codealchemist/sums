<?php
require_once dirname(__DIR__) . '/enum/SumStatus.php';

/**
 * Sum.
 * 
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class Sum extends TinyOrmModel {
    //--------------------------------------------------------------------------
    //TINYORM SETUP
    public function __construct($id = null) {
        $className = get_class();
        log_message('debug', "construct {$className}: $id");
        parent::__construct($id);
    }
    
    /**
     * Used by TinyOrm to map to collection.
     * @var string 
     */
    protected $collectionName = 'sums';
    //--------------------------------------------------------------------------
    
    private $_id = null;
    private $owner = null;
    private $version = 1;
    private $collaborators = array();
    private $createdDate = null;
    private $lastUpdatedDate = null;
    private $status = SumStatus::ACTIVE;
    private $title = null;
    private $description = null;
    private $tokens = array();
    private $total = 0;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function getCollaborators() {
        return $this->collaborators;
    }

    public function setCollaborators($collaborators) {
        $this->collaborators = $collaborators;
    }

    public function getCreatedDate() {
        return $this->createdDate;
    }

    /**
     * Sets created date.
     * If no date is passed sets current date.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $date MySQL date format: yyyy-mm-dd HH-mm-ss
     */
    public function setCreatedDate($date=null) {
        if (!$date) $date = date('Y-m-d G:i:s');
        $this->createdDate = new MongoDate(strtotime($date));
    }

    public function getLastUpdatedDate() {
        return $this->lastUpdated;
    }

    /**
     * Sets last updated date.
     * If no date is passed sets current date.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $date MySQL date format: yyyy-mm-dd HH-mm-ss
     */
    public function setLastUpdatedDate($date) {
        $this->lastUpdated = new MongoDate(strtotime($lastUpdated));;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getTokens() {
        return $this->tokens;
    }

    public function setTokens($tokens) {
        $this->tokens = $tokens;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }
    
    /**
     * Returns true if collaborator exists.
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $collaboratorId
     * @return boolean
     */
    private function collaboratorExists($collaboratorId) {
        if (!array_key_exists($collaboratorId, $this->collaborators)) {
            log_message('debug', "[ SUM ]--> collaboration exists: collaborator not found: $collaboratorId");
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns true if collaboration exists.
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $params
     * @return boolean
     */
    private function collaborationExists($collaboratorId, $collaborationId) {
        log_message('debug', '[ SUM ]--> collaboration exists');
        
        if (!$this->collaboratorExists($collaboratorId)) return false;
        
        $collaborator = $this->collaborators[$collaboratorId];
        if (!array_key_exists($collaborationId, $collaborator)) {
            log_message('debug', "[ SUM ]--> collaboration exists: collaboration not found: $collaborationId");
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns found collaborator id in passed params.
     * Collaborator id can be:
     * - userId
     * - collaborationToken
     * - email
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param type $params
     * @return boolean
     */
    private function getCollaboratorIdFromParams($params) {
        $collaboratorId = $params['userId'];
        if (empty($collaboratorId)) $userId = $params['collaborationToken'];
        if (empty($collaboratorId)) $userId = $params['email'];
        if (empty($collaboratorId)) {
            log_message('debug', '[ SUM ]--> get collaborator id from params: invalid request');
            return false;
        }
        
        return $collaboratorId;
    }
    
    /**
     * Returns requested collaboration.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $params [userId|collaborationToken|email, collaborationId]
     * @return array collaboration
     */
    public function getCollaboration($params) {
        log_message('debug', '[ SUM ]--> get collaboration: ' . print_r($params, true));
        $collaboratorId = $this->getCollaboratorIdFromParams($params);
        if (!$collaboratorId) {
            log_message('debug', '[ SUM ]--> get collaboration: invalid request');
            return false;
        }
        $collaborationId = $params['collaborationId'];
        
        //check if collaboration exists
        if (!$this->collaborationExists($collaboratorId, $collaborationId)) return false;
        
        //FOUND: return collaboration
        $collaborator = $this->collaborators[$collaboratorId];
        $collaboration = $collaborator[$collaborationId];
        return $collaboration;
    }
    
    /**
     * Updates existing collaboration.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $params
     */
    public function updateCollaboration($params) {
        log_message('debug', '[ SUM ]--> update collaboration: ' . print_r($params, true));
        
        //check collaborator id
        $collaboratorId = $this->getCollaboratorIdFromParams($params);
        if (!$collaboratorId) {
            log_message('debug', '[ SUM ]--> update collaboration: invalid request');
            return false;
        }
        
        //get collaborator
        if (!$this->collaboratorExists($collaboratorId)) {
            log_message('debug', "[ SUM ]--> add collaboration: collaborator NOT FOUND: $collaboratorId");
            return false;
        }
        $collaborator = $this->collaborators[$collaboratorId];
        
        //get collaboration
        $collaborationId = $params['collaborationId'];
        if (!$this->collaborationExists($collaboratorId, $collaborationId)) {
            log_message('debug', "[ SUM ]--> add collaboration: collaboration NOT FOUND: $collaborationId");
            return false;
        }
        $collaboration = $collaborator[$collaborationId];
        $collaboration['value'] = $params['collaboration']['value'];
        $date = date('Y-m-d G:i:s');
        $collaboration['updatedDate'] = new MongoDate(strtotime($date));
        
        //update collaboration
        $this->collaborators[$collaboratorId][$collaborationId] = $collaboration;
        log_message('debug', '[ SUM ]--> update collaboration: collaboration UPDATED: ' .  print_r($collaboration, true));
        return true;
    }
    
    /**
     * Saves passed collaboration.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $params
     */
    public function addCollaboration($params) {
        log_message('debug', '[ SUM ]--> add collaboration: ' . print_r($params, true));
        
        //check collaborator id
        $collaboratorId = $this->getCollaboratorIdFromParams($params);
        if (!$collaboratorId) {
            log_message('debug', '[ SUM ]--> save collaboration: invalid request');
            return false;
        }
        
        //get collaborator
        if (!$this->collaboratorExists($collaboratorId)) {
            log_message('debug', "[ SUM ]--> add collaboration: NEW collaborator added: $collaboratorId");
            $collaborator = array();
        } else {
            $collaborator = $this->collaborators[$collaboratorId];
            log_message('debug', "[ SUM ]--> add collaboration: got EXISTING collaborator: $collaboratorId");
        }
        
        //get collaboration
        $collaboration = $params['collaboration'];
        $date = date('Y-m-d G:i:s');
        $collaboration['createdDate'] = new MongoDate(strtotime($date));
        $collaborationId = $this->Guid->get();
        
        //add collaboration
        $this->collaborators[$collaboratorId][$collaborationId] = $collaboration;
        log_message('debug', '[ SUM ]--> add collaboration: collaboration ADDED: ' .  print_r($collaboration, true));
        return true;
    }
}