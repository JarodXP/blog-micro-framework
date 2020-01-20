<?php


namespace Models;


use Core\Manager;
use Entities\SocialNetwork;
use PDO;
use PDOStatement;

class NetworkManager extends Manager
{
    public const NETWORK_TABLE = 'social_networks',
        NAME = 'name',
        UPLOAD_ID = 'upload_id';

    /**
     * Gets a list SocialNetwork with the requested parameters.
     * @param string|null $requestParameters
     * @return array
     */
    public function findListOf(string $requestParameters = null):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::NETWORK_TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a social network in database
     * @param SocialNetwork $network
     * @return bool
     */
    public function insertSocialNetwork(SocialNetwork $network):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::NETWORK_TABLE.'('.self::NAME.', '.self::UPLOAD_ID.') 
                        VALUES(:name, :uploadId)');

        $this->bindAllFields($q,$network);

        return $q->execute();
    }

    /**
     * Gets a single social network using its id
     * @param int $networkId
     * @return array
     */
    public function findSocialNetwork(int $networkId):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::NETWORK_TABLE.' WHERE id = :id');

        $q->bindValue(':id', $networkId, PDO::PARAM_INT);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a given social network
     * @param SocialNetwork $network
     * @return bool
     */
    public function updateSocialNetwork(SocialNetwork $network)
    {
        $q = $this->dao->prepare('UPDATE '.self::NETWORK_TABLE.' SET '
            .self::UPLOAD_ID.'= :uploadId, '.self::NAME.' = :name WHERE id = :id');

        $q->bindValue(':id', $network->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$network);

        return $q->execute();
    }

    /**
     * Removes a specific social network
     * @param int $networkId
     * @return bool
     */
    public function removeSocialNetwork(int $networkId):bool
    {
        $q = $this->dao->prepare('DELETE FROM '.self::NETWORK_TABLE.' WHERE id = :id');

        $q->bindValue(':id',$networkId,PDO::PARAM_INT);

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