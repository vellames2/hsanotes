<?php

/**
 * This class access the database to do the necessary modifications and insertions in table
 * @author cassiano.vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class AuthDAO extends DAO {

    /**
     * @var Dao Instance of singleton
     */
    private static $instance;

    /**
     * @var string Table name used to do the database manipulations
     */
    private $tableName = "auth";

    /**
     * The parent construct initialize the $response variable
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * Singleton implementation
     * @return mixed Return an instance of an DAO
     */
    public static function getInstance() {
        if(!isset(self::$instance)){
            self::$instance = new AuthDAO();
        }
        return self::$instance;
    }

    /**
     * Select the authorization information of an user
     * @param int $id Id of user
     *
     * @return array Return a array with the result information
     */
    public function selectById(int $id): array{
        $sql = "SELECT * FROM {$this->tableName} where user_id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        return PDOSelectResult::returnArray($stmt->execute(), $stmt);
    }

    /**
     * Create the authentication register of user
     * @param mixed $object User ID
     * @return array Return a response with the result information
     */
    public function insert($object): array {
        $dateTime = (new DateTime())->format("Y-m-d H:i:s");

        $sql = "INSERT INTO {$this->tableName} (user_id, code, expiration) VALUES (?,?,?)";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $object, PDO::PARAM_INT);
        $stmt->bindValue(2, ApplicationSecurity::generateAuthHash($object), PDO::PARAM_STR);
        $stmt->bindValue(3, $dateTime, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->errorInfo();
    }

    /**
     * Actualize the user token
     * @param AuthBean $object Object to update in database
     *
     * @return array Return an array with the result information
     */
    public function update(IBean $object): array{

        $sql = "UPDATE {$this->tableName} set code = ?, expiration = ? where user_id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $object->getCode() , PDO::PARAM_STR);
        $stmt->bindValue(2, $object->getExpiration()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(3, $object->getUser()->getId(), PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->errorInfo();
    }

    /**
     * This method must delete an row in database
     * @param int $id Id to delete
     *
     * @return array Return an array with the result information
     */
    public function deleteById(int $id): array
    {
        // TODO: Implement deleteById() method.
    }
}