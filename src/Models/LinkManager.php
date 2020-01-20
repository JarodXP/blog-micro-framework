<?php


namespace Models;


use Core\Manager;
use Entities\NetworkLink;
use PDO;
use PDOStatement;

class LinkManager extends Manager
{
    public const LINKS_TABLE = 'network_links',
        NETWORK_ID = 'network_id',
        USER_ID = 'user_id',
        LINK = 'link';

    /**
     * Gets a list NetworkLinks with the requested parameters.
     * @param string|null $requestParameters
     * @return array
     */
    public function findListOf(string $requestParameters = null):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::LINKS_TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a Network Link in database
     * @param NetworkLink $link
     * @return bool
     */
    public function insertNetworkLink(NetworkLink $link):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::LINKS_TABLE.'('.self::NETWORK_ID.', '.self::USER_ID.', '.self::LINK.') 
                        VALUES(:networkId, :userId , :link)');

        $this->bindAllFields($q,$link);

        return $q->execute();
    }

    /**
     * Gets a single Network Link using its id
     * @param int $linkId
     * @return array
     */
    public function findNetworkLink(int $linkId):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::LINKS_TABLE.' WHERE id = :id');

        $q->bindValue(':id', $linkId, PDO::PARAM_INT);

        $q->execute();

        return $q->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a given Network Link
     * @param NetworkLink $link
     * @return bool
     */
    public function updateNetworkLink(NetworkLink $link)
    {
        $q = $this->dao->prepare('UPDATE '.self::LINKS_TABLE.' SET '
            .self::USER_ID.'= :userId, '.self::NETWORK_ID.' = :networkId, '.self::LINK.' = :link
                                     WHERE id = :id');

        $q->bindValue(':id', $link->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$link);

        return $q->execute();
    }

    /**
     * Removes a specific Network Link
     * @param int $linkId
     * @return bool
     */
    public function removeNetworkLink(int $linkId):bool
    {
        $q = $this->dao->prepare('DELETE FROM '.self::LINKS_TABLE.' WHERE id = :id');

        $q->bindValue(':id',$linkId,PDO::PARAM_INT);

        return $q->execute();
    }

    /**
     * Binds all fields value with parameters
     * @param PDOStatement $q
     * @param NetworkLink $link
     */
    private function bindAllFields(PDOStatement &$q, NetworkLink $link)
    {
        $q->bindValue(':userId', $link->getUserId(),PDO::PARAM_INT);
        $q->bindValue(':networkId', $link->getNetworkId(),PDO::PARAM_INT);
        $q->bindValue(':link', $link->getLink());

    }
}