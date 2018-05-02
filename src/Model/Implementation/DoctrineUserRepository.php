<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 20:38
 */

namespace PwBox\Model\Implementation;


use Doctrine\DBAL\Connection;
use PwBox\Model\User;
use PwBox\Model\UserRepository;

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

        $sql = "INSERT INTO User(nom, surname, username, birth_date, email, psw, image) VALUES(:nom, :surname, :username, :birth_date, :email, :psw, :image)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("nom", $user->getNom(), 'string');
        $stmt->bindValue("surname", $user->getSurname(), 'string');
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("pswUser", $user->getPassword(), 'string');
        $stmt->bindValue("birth_date", $user->getBirthDate(), 'string');
        $stmt->bindValue("image", $user->getNomImage(), 'string');

        $stmt->execute();
    }
}