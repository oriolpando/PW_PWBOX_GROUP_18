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
    public function save(User $user)
    {

        $sql = "INSERT INTO User(nom, surname, username, birth_date, email, pswUser) VALUES(:nom, :surname, :username, :birth_date, :email, :pswUser)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("nom", $user->getNom(), 'string');
        $stmt->bindValue("surname", $user->getSurname(), 'string');
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("pswUser", $user->getPassword(), 'string');
        $stmt->bindValue("birth_date", $user->getBirthDate() );



        $stmt->execute();

        echo $user->getBirthDate();

    }

    public function tryLogin(String $loginTry, String $psw)
    {

        $sql = "SELECT id FROM User WHERE (email LIKE ? OR username LIKE ?) AND pswUser LIKE ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $loginTry, PDO::PARAM_STR);
        $stmt->bindParam(2, $loginTry, PDO::PARAM_STR);
        $stmt->bindParam(3, $psw, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        var_dump($result);
        if (!empty($result)){
            return $result[0]['id'];
        }else{
            return -1;
        }
    }
}