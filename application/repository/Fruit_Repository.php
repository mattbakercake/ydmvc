<?php
/**
 * The Fruit_Repository class implements the Repository_Interface interface
 * it holds properties that abstract data access methods away from the model
 * 
 * @category Repository
 * @version 0.1
 * @since 09-05-2014
 * @author Matt Baker <dev@mikesierra.net>
 */
class Fruit_Repository implements Repository_Interface{
    
    /**
     * Database object
     * @var DB Object
     */
    protected $db;
    
    /**
     * constructor instantiates DB object if it doesn't exist
     */
    function __construct() {
        if (!is_object($this->db)) {
            $this->db = new DB();
        }
    }
    
    /**
     * Public function select all fruit from the database
     * 
     * @return array/assoc array
     */
    public function findAll(){
        try {
            $this->db->initDB();
            $sql = "SELECT * FROM fruit";
            $query = $this->db->_dbHandle->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $this->db->quitDB();
        } catch (PDOException $e) {
            $result = false;
        }
        
        return $result;
     }
    
    /**
     * public function selects fruit from db that matches id
     * 
     * @param int $id
     * @return array/assoc array
     */
    public function findById($id) {
        try {
            $this->db->initDB();
            $sql = "SELECT * FROM fruit WHERE id=" . $id;
            $query = $this->db->_dbHandle->prepare($sql);
            $query->execute();
            $result = $query->fetch();
            $this->db->quitDB();
        } catch (PDOException $e) {
            $result = false;
        }
        return $result;
    }
    
    /**
     * public function creates new fruit record
     */
    public function create($params) {
        //unused
    }
    
    /**
     * public function updates existing fruit record in DB
     */
    public function update() {
        //unused
    }
    
    /**
     * public function deletes fruit record from DB
     */
    public function destroy($id) {
        //unused
    }
    
}
