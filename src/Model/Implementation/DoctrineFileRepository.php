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
use PwBox\Model\FileRepository;
use PwBox\Model\php;
use PwBox\Model\User;
use PwBox\Model\UserRepository;
use PDO;
class DoctrineFileRepository implements FileRepository
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

    public function iniciaFolder()
    {
        $sql = "INSERT INTO Item(nom, parent, type) VALUES(:nom, :parent, :type)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("nom", "root", 'string');
        $stmt->bindValue("parent", null, 'string');
        $stmt->bindValue("type", false, 'boolean');

        $stmt->execute();


        $sql = "SELECT id FROM Item order by id desc limit 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result[0]['id'];

    }


    public function saveItem($item)
    {
        /**
         * @param php $item
         * @throws \Doctrine\DBAL\DBALException
*/
        $nom = $item->getNom();
        $parent = $_SESSION['currentFolder'];
        $type = $item->getType();

        $sql = "INSERT INTO Item(nom, parent, type) VALUES(:nom, :parent, :type)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("nom", $nom, 'string');
        $stmt->bindValue("parent", $parent, 'string');
        $stmt->bindValue("type", $type, 'boolean');

        $stmt->execute();

    }


}