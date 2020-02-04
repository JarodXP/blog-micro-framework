<?php


namespace Models;


use Core\Manager;
use Entities\NetworkLink;
use PDO;
use PDOStatement;

class LinkManager extends Manager
{
    public const TABLE = 'network_links',
        NETWORK_ID = 'network_id',
        USER_ID = 'user_id',
        LINK = 'link';

    /**
     * Inserts a Network Link in database
     * @param NetworkLink $link
     * @return bool
     */
    public function insertNetworkLink(NetworkLink $link):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::TABLE.'('.self::NETWORK_ID.', '.self::USER_ID.', '.self::LINK.') 
                        VALUES(:networkId, :userId , :link)');

        $this->bindAllFields($q,$link);

        return $q->execute();
    }

    /**
     * Updates a given Network Link
     * @param NetworkLink $link
     * @return bool
     */
    public function updateNetworkLink(NetworkLink $link):array
    {
        $q = $this->dao->prepare('UPDATE '.self::TABLE.' SET '
            .self::USER_ID.'= :userId, '.self::NETWORK_ID.' = :networkId, '.self::LINK.' = :link
                                     WHERE id = :id');

        $q->bindValue(':id', $link->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q,$link);

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