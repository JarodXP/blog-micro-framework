<?php


namespace Entities;


use Core\Entity;

class NetworkLink extends Entity
{
    protected int $networkId, $userId;

    protected string $link;

    //GETTERS

    /**
     * @return int
     */
    public function getNetworkId(): int
    {
        return $this->networkId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    //SETTERS

    /**
     * @param int $networkId
     */
    public function setNetworkId(int $networkId): void
    {
        $this->networkId = $networkId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

}