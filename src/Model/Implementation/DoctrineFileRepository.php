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

        $nom = 'root';
        $id = $_SESSION['id'];

        $sql = "INSERT INTO Item(nom, parent, type, id_propietari) VALUES(?, null, false, ?)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $nom, PDO::PARAM_STR);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        $stmt->execute();


        $sql = "SELECT id FROM Item WHERE id_propietari = ? AND nom LIKE ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $nom, PDO::PARAM_STR);

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
        $id = $_SESSION['id'];


        $sql = "SELECT id FROM Item WHERE parent = ? AND type = ? AND nom LIKE ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $parent, PDO::PARAM_STR);
        $stmt->bindParam(2, $type, PDO::PARAM_BOOL);
        $stmt->bindParam(3, $nom, PDO::PARAM_STR);



        $stmt->execute();
        $result = $stmt->fetchAll();

        if (empty($result)){
            $sql = "INSERT INTO Item(nom, parent, type, id_propietari) VALUES(?, ?, ?, ?)";
            $stmt = $this->connection->prepare($sql);

            $stmt->bindParam(1, $nom, PDO::PARAM_STR);
            $stmt->bindParam(2, $parent, PDO::PARAM_STR);
            $stmt->bindParam(3, $type, PDO::PARAM_BOOL);
            $stmt->bindParam(4, $id, PDO::PARAM_INT);

            $stmt->execute();

            return true;
        }else{
            return false;
        }



    }


}