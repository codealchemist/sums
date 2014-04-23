<?php
/**
 * User.
 * 
 * @author Alberto Miranda <alberto.php@gmail.com>>
 */
class User extends TinyOrmModel {
    //--------------------------------------------------------------------------
    //TINYORM SETUP
    public function __construct($id = null) {
        $className = get_class();
        log_message('debug', "construct {$className}: $id");
        parent::__construct($id);
        
        $this->CI =& get_instance();
    }
    
    /**
     * Used by TinyOrm to map to collection.
     * @var string 
     */
    protected $collectionName = 'users';
    
    /**
     * Used by TinyOrm to hide object properties when using toArray().
     * @var array
     */
    protected $hiddenProperties = array('password', 'passwordRecovery');
    //--------------------------------------------------------------------------
    
    private $_id = null;
    private $name = null;
    private $email = null;
    private $password = null;
    private $sums = array();
    private $collaboratesOn = array();
    private $passwordRecovery = null;
    private $createdDate = null;
    private $lastLoginDate = null;
    private $loginToken = null;
    
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $password = md5($password);
        $this->password = $password;
    }

    public function getSums() {
        return $this->sums;
    }

    public function setSums($sums) {
        $this->sums = $sums;
    }

    public function getCollaboratesOn() {
        return $this->collaboratesOn;
    }

    public function setCollaboratesOn($collaboratesOn) {
        $this->collaboratesOn = $collaboratesOn;
    }

    public function getPasswordRecovery() {
        return $this->passwordRecovery;
    }

    public function setPasswordRecovery($passwordRecovery) {
        $this->passwordRecovery = $passwordRecovery;
    }
    
    public function getCreatedDate() {
        return $this->createdDate;
    }

    /**
     * Sets created date.
     * If no date is passed sets current date.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $createdDate MySQL date format: yyyy-mm-dd HH-mm-ss
     */
    public function setCreatedDate($createdDate=null) {
        if (!$createdDate) $createdDate = date('Y-m-d G:i:s');
        $this->createdDate = new MongoDate(strtotime($createdDate));
    }
    
    public function getLastLoginDate() {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate($lastLoginDate = null) {
        if (!$lastLoginDate) $lastLoginDate = date('Y-m-d G:i:s');
        $this->lastLoginDate = new MongoDate(strtotime($lastLoginDate));;
    }
    
    public function getLoginToken() {
        return $this->loginToken;
    }

    public function setLoginToken($loginToken = null) {
        if (!$loginToken) $loginToken = $this->getNewLoginToken();
        $this->loginToken = $loginToken;
    }
    
    /**
     * Returns true if passed login data is valid.
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @return boolean
     */
    public function hasValidLogin() {
        $criteria = array(
            'email' => $this->getEmail(),
            'password' => $this->getPassword()
        );
        
        $found = $this->findOne($criteria);
        if (!empty($found)) return true;
        return false;
    }
    
    /**
     * If current login data is valid loads user from db and true.
     * Returns false otherwise.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @return boolean
     */
    public function login() {
        $criteria = array(
            'email' => $this->getEmail(),
            'password' => $this->getPassword()
        );
        
        $found = $this->findOne($criteria, true);
        if (!empty($found)) {
            //login OK
            $this->fill($found);
            return true;
        }
        
        //invalid login
        return false;
    }
    
    /**
     * Returns a new login token.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @return string
     */
    public function getNewLoginToken() {
        log_message('debug', 'get login token');
        $prefix = $this->CI->config->item('tokenPrefixes')['login'];
        $prefixHash = md5($prefix);
        $guid = $this->Guid->get();
        $token = "$prefixHash-$guid";
        return $token;
    }
}