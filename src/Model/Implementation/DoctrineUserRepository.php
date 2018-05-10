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
    public function save(User $user){

        $psw = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $sql = "INSERT INTO User(nom, surname, username, birth_date, email, pswUser) VALUES(:nom, :surname, :username, :birth_date, :email, :pswUser)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("nom", $user->getNom(), 'string');
        $stmt->bindValue("surname", $user->getSurname(), 'string');
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("pswUser", $psw, 'string');
        $stmt->bindValue("birth_date", $user->getBirthDate() );



        $stmt->execute();

        echo $user->getBirthDate();

    }

    public function checkIfUserExists(string $username, string $mail){

        $sql = "SELECT id FROM User WHERE (email LIKE ? OR username LIKE ?) ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $mail, PDO::PARAM_STR);
        $stmt->bindParam(2, $username, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!empty($result)){
            return true;
            }else{
            return false;
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

        var_dump($result);
        if (!empty($result)){
            if (password_verify($password, $result[0]['pswUser'])){
                $results = [];
                $results[0] =  $result[0]['id'];
                $results[1] =  $result[0]['id_motherfolder'];

                return $results;
            }else{
                return -2;
            }
        }else{
            return -1;
        }
    }

    public function getUser(int $id){

        $sql = "SELECT * FROM User WHERE id LIKE ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $user = new User($result[0]['id'],$result[0]['nom'],$result[0]['surname'],$result[0]['username'], $result[0]['email'], $result[0]['pswUser'], $result[0]['birth_date']);

        return $user;
    }

    public function updateUser(String $email, String $psw)
    {

        $id = $_SESSION['id'];

        var_dump($email);
        $sql = "UPDATE User SET email = ?, pswUser = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $psw, PDO::PARAM_STR);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);

        $stmt->execute();

    }
}