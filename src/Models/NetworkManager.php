<?php


namespace Models;


use Core\Manager;
use Entities\SocialNetwork;
use PDO;
use PDOStatement;

class NetworkManager extends Manager
{
    public const TABLE = 'social_networks',
        NAME = 'name',
        UPLOAD_ID = 'upload_id';


    /**
     * Inserts a social network in database
     * @param SocialNetwork $network
     * @return bool
     */
    public function insertSocialNetwork(SocialNetwork $network):bool
    {
        //Checks if the name is unique
        $this->checkUniqueFields([
            self::NAME => $network->getName(),
        ],false);

        $q = $this->dao->prepare(
            'INSERT INTO '.self::TABLE.'('.self::NAME.', '.self::UPLOAD_ID.') 
                        VALUES(:name, :uploadId)');

        $this->bindAllFields($q,$network);

        return $q->execute();
    }

    /**
     * Updates a given social network
     * @param SocialNetwork $network
     * @return bool
     */
    public function updateSocialNetwork(SocialNetwork $network)
    {
        //Checks if the name is unique
        $this->checkUniqueFields([
            self::NAME => $network->getName(),
        ],true,$network->getId());

        $q = $this->dao->prepare('UPDATE '.self::TABLE.' SET '
            .self::UPLOAD_ID.'= :uploadId, '.self::NAME.' = :name WHERE id = :id');

        $q->bindValue(':id', $network->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$network);

        return $q->execute();
    }

    /**
     * Gets the username and avatar fileName for the connected user
     * @param int $id
     * @return array
     */
    public function findNetworksAndIcons()
    {
        $q = $this->dao->prepare(
            'SELECT social_networks.*,uploads.file_name
                            FROM social_networks 
                            INNER JOIN uploads ON social_networks.upload_id = uploads.id');

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Binds all fields value with parameters
     * @param PDOStatement $q
     * @param SocialNetwork $network
     */
    private function bindAllFields(PDOStatement &$q, SocialNetwork $network)
    {
        $q->bindValue(':uploadId', $network->getUploadId(),PDO::PARAM_INT);
        $q->bindValue(':name', $network->getName());
    }
}