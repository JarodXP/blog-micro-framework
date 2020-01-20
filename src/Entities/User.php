<?php


namespace Entities;


use Core\Entity;

class User extends Entity
{
    protected int $role;
    protected ?int $avatarId;
    protected ?int $resumeId;

    protected string $email, $password, $dateAdded;

    protected ?string $username;
    protected ?string $lastName;
    protected ?string $firstName;
    protected ?string $title;
    protected ?string $phone;
    protected ?string $baseline;
    protected ?string$introduction;


    //GETTERS

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @return int
     */
    public function getAvatarId(): ?int
    {
        return $this->avatarId;
    }

    /**
     * @return int
     */
    public function getResumeId(): ?int
    {
        return $this->resumeId;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getBaseline(): ?string
    {
        return $this->baseline;
    }

    /**
     * @return string
     */
    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    /**
     * @return string
     */
    public function getDateAdded(): string
    {
        return $this->dateAdded;
    }

    //SETTERS

    /**
     * @param int $role
     */
    public function setRole(int $role): void
    {
        $this->role = $role;
    }

    /**
     * @param int $avatarId
     */
    public function setAvatarId(int $avatarId = null): void
    {
        $this->avatarId = $avatarId;
    }

    /**
     * @param int $resumeId
     */
    public function setResumeId(int $resumeId = null): void
    {
        $this->resumeId = $resumeId;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username = null): void
    {
        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName = null): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName = null): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title = null): void
    {
        $this->title = $title;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone = null): void
    {
        $this->phone = $phone;
    }

    /**
     * @param string $baseline
     */
    public function setBaseline(string $baseline = null): void
    {
        $this->baseline = $baseline;
    }

    /**
     * @param string $introduction
     */
    public function setIntroduction(string $introduction = null): void
    {
        $this->introduction = $introduction;
    }

    /**
     * @param string $dateAdded
     */
    public function setDateAdded(string $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }
}