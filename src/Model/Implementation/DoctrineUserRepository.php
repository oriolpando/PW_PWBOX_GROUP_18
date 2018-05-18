<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 20:38
 */

namespace PwBox\Model\Implementation;


use Doctrine\DBAL\Connection;
use function FastRoute\TestFixtures\empty_options_cached;
use PwBox\Model\User;
use PwBox\Model\UserRepository;
use PDO;
class DoctrineUserRepository implements UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var Connection */
    private $connection;

    /**
     * DoctrineUserRepository constructor.
     * @param $connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param User $user
     * @throws \Doctrine\DBAL\DBALException
     */

    public function getInfoValidate ($id){
        $sql = "SELECT nom,email FROM User WHERE id LIKE ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $torna = [];
        $torna[0] = $result[0]['nom'];
        $torna[1] = $result[0]['email'];




        return $torna;


    }

    public function save(User $user){

        $nom = $user->getNom();
        $surname = $user->getSurname();
        $username = $user->getUsername();
        $birthDate = $user->getBirthDate();
        $email = $user->getEmail();
        $pswUser = $user->getPassword();
        $psw = password_hash($pswUser, PASSWORD_DEFAULT);
        $motherfolder = $user->getMotherFolder();
        ($user);

        $sql = "INSERT INTO User(nom, surname, username, birth_date, email, pswUser, id_motherfolder) VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $nom, PDO::PARAM_STR);
        $stmt->bindParam(2, $surname, PDO::PARAM_STR);
        $stmt->bindParam(3, $username, PDO::PARAM_STR);
        $stmt->bindParam(4, $birthDate, PDO::PARAM_STR);
        $stmt->bindParam(5, $email, PDO::PARAM_STR);
        $stmt->bindParam(6, $psw, PDO::PARAM_STR);
        $stmt->bindParam(7, $motherfolder, PDO::PARAM_INT);

        $stmt->execute();

    }



    public function checkValidate ($id){
        $sql = "SELECT validat FROM User WHERE id LIKE ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['validat'];

    }

    public function checkIfEmailExists(string $email){

        $sql = "SELECT id FROM User WHERE (email LIKE ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!empty($result)){
            return true;
        }else{
            return false;
        }
    }

    public function checkIfUsernameExists(string $username){

        $sql = "SELECT id FROM User WHERE (username LIKE ?) ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!empty($result)){
            return true;
            }else{
            return false;
        }

    }

    public function getId(String $loginTry)
    {

        $sql = "SELECT id FROM User WHERE (email LIKE ? OR username LIKE ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $loginTry, PDO::PARAM_STR);
        $stmt->bindParam(2, $loginTry, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

       // ($result);
        if (!empty($result)){
            return $result[0]['id'];
        }else{
            return -1;
        }
    }

    public function tryLogin(String $loginTry, String $password)
    {

        $sql = "SELECT * FROM User WHERE (email LIKE ? OR username LIKE ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $loginTry, PDO::PARAM_STR);
        $stmt->bindParam(2, $loginTry, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $id = [];
        if (!empty($result)){
            if (password_verify($password, $result[0]['pswUser'])){
                $results = [];
                $results[0] =  $result[0]['id'];
                $results[1] =  $result[0]['id_motherfolder'];

                return $results;
            }else{
                array_push($id,-2);
                return $id;
            }
        }else{
            array_push($id,-1);
            return $id;
        }
    }

    public function getUser($id){
        $sql = "SELECT * FROM User WHERE id LIKE ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $user = new User($result[0]['id'],$result[0]['nom'],$result[0]['surname'],$result[0]['username'], $result[0]['email'], $result[0]['pswUser'], $result[0]['birth_date'],$result[0]['id_motherfolder']);

        return $user;
    }

    public function updateUser(String $email, String $psw)
    {

        $id = $_SESSION['id'];

        $sql = "UPDATE User SET email = ?, pswUser = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $psw, PDO::PARAM_STR);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);

        $stmt->execute();

    }

    public function setMotherFolder(int $id_motherfolder)
    {

        $id = $_SESSION['id'];

        $sql = "UPDATE User SET id_motherfolder = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id_motherfolder, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        $stmt->execute();

    }

    public function deleteUser()
    {
        $id = $_SESSION['id'];

        $sql = "DELETE FROM Notification WHERE id_user = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();


        $sql = "DELETE FROM Share WHERE id_propietari = ? OR id_user = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);


        $stmt->execute();

        $sql = "DELETE FROM Item WHERE id_propietari = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        $sql = "DELETE FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

    }

    public function validateUser($id){


        $sql = "UPDATE User SET validat = 1 WHERE id = ? AND validat = 0";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        $sql = "SELECT * FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        //($result);
        if (!empty($result)){
            $results = [];
            $results[0] =  $result[0]['id'];
            $results[1] =  $result[0]['id_motherfolder'];

            return $results;
        }else{
            return -1;
        }

    }

    public function getNotifications($id){
        $sql = "SELECT * FROM Notification WHERE id_user = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        $results = $stmt->fetchAll();

        $html = '';

        if (!empty($results)){
            foreach ($results as $result){
                $html = $html.'<tr><td>'.$result['notification'].'</td></tr>';
            }
        }else{
            $html = $html.'<tr><td>There isn\'t any notification yet</td></tr>';
        }
        return $html;

    }
}