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
        $q = $this->dao->prepare('UPDATE '.self::TABLE.' SET '
            .self::UPLOAD_ID.'= :uploadId, '.self::NAME.' = :name WHERE id = :id');

        $q->bindValue(':id', $network->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$network);

        return $q->execute();
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