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
use function PHPSTORM_META\type;
use PwBox\Model\FileRepository;
use PwBox\Model\Item;
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

    public function getUsernameFromId ($id){

        $sql = "SELECT username FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result[0]['username'];
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

    public function getUsedBytes ($id){

        $sql = "SELECT total_bytes FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result[0]['total_bytes'];

    }

    public function saveItem($item, $total)
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
            if ($type == 1){
                    $sql = "UPDATE User SET total_bytes = ? WHERE id = ?";
                    $stmt = $this->connection->prepare($sql);
                    $stmt->bindParam(1, $total, PDO::PARAM_INT);
                    $stmt->bindParam(2, $id, PDO::PARAM_INT);
                    $stmt->execute();
                    return true;


            }
            return true;

        }else{
            return false;
        }

    }

    public function saveSharedFolder($item)
    {
        /**
         * @param php $item
         * @throws \Doctrine\DBAL\DBALException
         */
        $nom = $item->getNom();
        $parent = $_SESSION['currentSharedFolder'];
        $type = $item->getType();

        $username = $this->getUsernameFromSharedFolder($parent);


        $sql = "SELECT id FROM Item WHERE parent = ? AND type = ? AND nom LIKE ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $parent, PDO::PARAM_STR);
        $stmt->bindParam(2, $type, PDO::PARAM_BOOL);
        $stmt->bindParam(3, $nom, PDO::PARAM_STR);



        $stmt->execute();
        $result = $stmt->fetchAll();

        if (empty($result)){

            $idShared = $this->getIdFromParent($parent);
            $roleShared = $this->getRoleFromId($parent);

            if (strcmp('Admin', $roleShared['role']) == 0){
                $sql = "INSERT INTO Item(nom, parent, type, id_propietari) VALUES(?, ?, ?, ?)";
                $stmt = $this->connection->prepare($sql);

                $stmt->bindParam(1, $nom, PDO::PARAM_STR);
                $stmt->bindParam(2, $parent, PDO::PARAM_STR);
                $stmt->bindParam(3, $type, PDO::PARAM_BOOL);
                $stmt->bindParam(4, $idShared, PDO::PARAM_INT);

                $stmt->execute();

                $sql = "SELECT id FROM Item WHERE parent = ? AND type = ? AND nom LIKE ?";
                $stmt = $this->connection->prepare($sql);

                $stmt->bindParam(1, $parent, PDO::PARAM_STR);
                $stmt->bindParam(2, $type, PDO::PARAM_BOOL);
                $stmt->bindParam(3, $nom, PDO::PARAM_STR);



                $stmt->execute();
                $result = $stmt->fetchAll();
                $idFolder = $result[0]['id'];
                $sql = "INSERT INTO Share(id_user, role, id_propietari, id_folder, parent) VALUES(?, 'Admin', ?, ?, 1)";
                $stmt = $this->connection->prepare($sql);

                $idUser = $_SESSION['id'];
                $stmt->bindParam(1, $idUser, PDO::PARAM_INT);
                $stmt->bindParam(2, $idShared, PDO::PARAM_INT);
                $stmt->bindParam(3, $idFolder, PDO::PARAM_INT);

                $stmt->execute();

                return 0;
            }else{
                return -2;
            }

        }else{
            return -1;
        }

    }

    public function saveSharedFile($item, $total)
    {
        /**
         * @param php $item
         * @throws \Doctrine\DBAL\DBALException
         */
        $nom = $item->getNom();
        $parent = $_SESSION['currentSharedFolder'];
        $type = $item->getType();

        $username = $this->getUsernameFromSharedFolder($parent);

        $sql = "SELECT id FROM Item WHERE parent = ? AND type = ? AND nom LIKE ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $parent, PDO::PARAM_STR);
        $stmt->bindParam(2, $type, PDO::PARAM_BOOL);
        $stmt->bindParam(3, $nom, PDO::PARAM_STR);



        $stmt->execute();
        $result = $stmt->fetchAll();

        if (empty($result)){

            $idShared = $this->getIdFromParent($parent);
            $roleShared = $this->getRoleFromId($parent);

            if (strcmp('Admin', $roleShared['role']) == 0){


                $sql = "INSERT INTO Item(nom, parent, type, id_propietari) VALUES(?, ?, ?, ?)";
                $stmt = $this->connection->prepare($sql);

                $stmt->bindParam(1, $nom, PDO::PARAM_STR);
                $stmt->bindParam(2, $parent, PDO::PARAM_STR);
                $stmt->bindParam(3, $type, PDO::PARAM_BOOL);
                $stmt->bindParam(4, $idShared, PDO::PARAM_INT);

                $stmt->execute();

                $sql = "SELECT id FROM Item WHERE parent = ? AND type = ? AND nom LIKE ?";
                $stmt = $this->connection->prepare($sql);

                $stmt->bindParam(1, $parent, PDO::PARAM_STR);
                $stmt->bindParam(2, $type, PDO::PARAM_BOOL);
                $stmt->bindParam(3, $nom, PDO::PARAM_STR);


                $stmt->execute();
                $result = $stmt->fetchAll();
                $idFolder = $result[0]['id'];
                $sql = "INSERT INTO Share(id_user, role, id_propietari, id_folder, parent) VALUES(?, 'Admin', ?, ?, 1)";
                $stmt = $this->connection->prepare($sql);

                $idUser = $_SESSION['id'];
                $stmt->bindParam(1, $idUser, PDO::PARAM_INT);
                $stmt->bindParam(2, $idShared, PDO::PARAM_INT);
                $stmt->bindParam(3, $idFolder, PDO::PARAM_INT);

                $stmt->execute();

                $sql = "UPDATE User SET total_bytes = ? WHERE id = ?";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindParam(1, $total, PDO::PARAM_INT);
                $stmt->bindParam(2, $idShared, PDO::PARAM_INT);
                $stmt->execute();


                return 0;
            }else{
                return -2;
            }

        }else{
            return -1;
        }

    }

    public function getCurrentItems()
    {

        $parent = $_SESSION['currentFolder'];

        $sql = "SELECT * FROM Item WHERE parent = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $parent, PDO::PARAM_INT);



        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;

    }

    public function getRootSharedItems()
    {

        $id = $_SESSION['id'];

        $sql = "SELECT id_folder FROM Share WHERE id_user = ? && parent = 0";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);



        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;

    }

    public function getCurrentSharedItems()
    {
        $parent = $_SESSION['currentSharedFolder'];

        $sql = "SELECT * FROM Item WHERE parent = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $parent, PDO::PARAM_INT);



        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;


    }

    public function getFileNameFromId($id)
    {

        $sql = "SELECT nom FROM Item WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);



        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0];

    }

    public function getRoleFromId($id)
{
    $idUser = $_SESSION['id'];
    $sql = "SELECT role FROM Share WHERE id_folder = ? AND id_user = ?";
    $stmt = $this->connection->prepare($sql);

    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->bindParam(2, $idUser, PDO::PARAM_INT);




    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result[0];

}

    public function getIdFromParent($id)
    {

        $sql = "SELECT id_propietari FROM Item WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);



        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0]['id_propietari'];

    }

    public function getSharedId($nom)
    {

        $sql = "SELECT id FROM Item WHERE nom = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $nom, PDO::PARAM_STR);



        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0];

    }

    public function getItem($id)
    {

        $sql = "SELECT * FROM Item WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);



        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0];

    }

    public function deleteFile($item)
    {
        $id = $item['id'];

        $bytes = $this->getUsedBytes($item['id_propietari']);


        $username = $this->getUsernameFromId($item['id_propietari']);
        $target_dir = 'assets/resources/perfils/'.$username.'/root/'.$item['parent'].'¿'.$item['nom'];
        $fileBytes = filesize('assets/resources/perfils/'.$username.'/root/'.$item['parent'].'¿'.$item['nom']);


        $total = $bytes - $fileBytes;

        $idProp = $item['id_propietari'];

        $sql = "UPDATE User SET total_bytes = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $total, PDO::PARAM_INT);
        $stmt->bindParam(2, $idProp, PDO::PARAM_INT);
        $stmt->execute();


        unlink($target_dir);

        $sql = "DELETE FROM Share WHERE id_folder = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);


        $stmt->execute();

        $sql = "DELETE FROM Item WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        return true;

    }

    public function deleteFolder($item)
    {
        $id = $item['id'];

        $sql = "SELECT * FROM Item WHERE parent LIKE ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll();
        if (!empty($results)) {
            foreach ($results as $result){

                if ($result['type'] == 0){
                    $ok = $this->deleteFolder($result);
                }else{
                    $ok = $this->deleteFile($result);
                }
            }
        }



        $sql = "DELETE FROM Share WHERE id_folder = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);


        $stmt->execute();


        $sql = "DELETE FROM Item WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        return true;

    }

    public function renameFolder($item, $rename)
    {
        $id = $item['id'];

        $sql = "UPDATE Item SET nom = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $rename, PDO::PARAM_STR);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);


        $stmt->execute();

        return true;

    }

    public function renameFile($item, $rename)
    {
        $username = $this->getUsernameFromId($item['id_propietari']);
        $target_dir = 'assets/resources/perfils/'.$username.'/root/'.$item['parent'].'¿'.$item['nom'];

        $extension = explode(".", $item['nom']);

        rename($target_dir, 'assets/resources/perfils/'.$username.'/root/'.$item['parent'].'¿'.$rename.".".$extension[1]);


        $id = $item['id'];

        $rename = $rename.".".$extension[1];
        $sql = "UPDATE Item SET nom = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $rename, PDO::PARAM_STR);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);


        $stmt->execute();

        return true;

    }

    public function getUsernameFromSharedFolder($id){
        $sql = "SELECT id_propietari FROM Item WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);


        $stmt->execute();
        $result = $stmt->fetchAll();

        $id = $result[0]['id_propietari'];
        $sql = "SELECT username FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1,$id,PDO::PARAM_INT);


        $stmt->execute();
        $results = $stmt->fetchAll();

        return $results[0]['username'];
    }

    public function shareFolder($idFolder, $email, $role, $parent){

        $sql = "SELECT id_propietari FROM Item WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $idFolder, PDO::PARAM_INT);

        $stmt->execute();

        $resultId = $stmt->fetchAll();
        $id = $resultId[0]['id_propietari'];

        $sql = "SELECT email FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();


        $user = $result[0];



        if (strcmp($email,$user['email']) != 0){
            $sql = "SELECT id FROM User WHERE email LIKE ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(1, $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll();
            if (!empty($result)){
                try{

                $sql = "INSERT INTO Share(id_user, role, id_propietari, id_folder, parent) VALUES(?, ?, ?, ?, ?)";
                $stmt = $this->connection->prepare($sql);

                $stmt->bindParam(1, $result[0]['id'], PDO::PARAM_INT);
                $stmt->bindParam(2, $role, PDO::PARAM_STR);
                $stmt->bindParam(3, $id, PDO::PARAM_INT);
                $stmt->bindParam(4, $idFolder, PDO::PARAM_INT);
                $stmt->bindParam(5, $parent, PDO::PARAM_INT);



                $stmt->execute();


                $sql = "SELECT id FROM Item WHERE parent = ? ";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindParam(1, $idFolder, PDO::PARAM_INT);
                $stmt->execute();

                $resultsRec = $stmt->fetchAll();
                if (!empty($resultsRec)){

                    foreach ($resultsRec as $resultRec){

                        $ok = $this->shareFolder($resultRec['id'],$email, $role, 1);
                    }
                }

                return true;
                }catch (\Exception $e){
                    return false;
                }
            }
        }

        return false;
    }

    public function insertBBDD($id, $message){
        $sql = "INSERT INTO Notification(id_user, notification) VALUES(?, ?)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $message, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getOwner($id){
        $sql = "SELECT id_propietari FROM Item WHERE id = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll();
        var_dump($results);
        return $results[0]['id_propietari'];

    }

    public function getEmail($id){
        $sql = "SELECT email FROM User WHERE id = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll();
        return $results[0]['email'];

    }

}