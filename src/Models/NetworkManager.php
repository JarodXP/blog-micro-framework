<?php


namespace Models;


use Core\Manager;
use Entities\SocialNetwork;
use PDO;
use PDOStatement;
use Services\ListConfigurator;

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
    public function updateSocialNetwork(SocialNetwork $network):bool
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
     * @param null $conditions
     * @param null $options
     * @return array
     */
    public function findNetworksAndIcons($conditions = null, $options = null)
    {
        //Sets the parameters with the ListConfigurator Service
        $listConfigurator = new ListConfigurator($this);

        $requestParameters = $listConfigurator->getRequestParameters($conditions,$options);

        $q = $this->dao->prepare(
            'SELECT social_networks.*,uploads.file_name AS fileName,uploads.original_name AS originalName
                            FROM social_networks 
                            INNER JOIN uploads ON social_networks.upload_id = uploads.id'.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets the list of networks and the links corresponding to users
     * @param int $userId
     * @param bool $findNoLinks
     * @return array
     */
    public function findNetworksAndLinks(int $userId, bool $findNoLinks)
    {
        //Sets a condition to get networks without links
        $findNoLinks ? $findNull = 'OR users.id IS NULL' : $findNull = '';

        $q = $this->dao->prepare('SELECT
                            social_networks.id,                     
                            network_links.link, 
                            network_links.id AS linkId,
                            social_networks.name AS networkName,
                            uploads.file_name AS iconFileName,
                            uploads.original_name AS iconOriginalName,
                            uploads.alt 
                        FROM social_networks
                            LEFT JOIN uploads ON social_networks.upload_id = uploads.id 
                            LEFT JOIN network_links ON social_networks.id = network_links.network_id 
                            LEFT JOIN users ON users.id = network_links.user_id
                        WHERE users.id = :userId'.' '.$findNull);

        $q->bindValue(':userId',$userId,PDO::PARAM_INT);

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