<?php


namespace Entities;


use Core\Entity;
use Exceptions\EntityAttributeException;

class User extends Entity
{
    protected ?int $role;
    protected ?int $avatarId;
    protected ?int $resumeId;

    protected ?string $email;
    protected ?string $password;
    protected ?string $username;
    protected ?string $lastName;
    protected ?string $firstName;
    protected ?string $title;
    protected ?string $phone;
    protected ?string $baseline;
    protected ?string $introduction;
    protected ?string $dateAdded;
    protected ?string $notification;


    public const ROLE_ADMIN = 1,
        ROLE_MEMBER = 2,
        ROLE_VISITOR = 3;


    //GETTERS

    /**
     * @return int
     */
    public function getRole(): ?int
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
    public function getDateAdded(): ?string
    {
        return $this->dateAdded;
    }

    /**
     * @return string
     */
    public function getNotification(): ?string
    {
        return $this->notification;
    }



    //SETTERS

    /**
     * @param int $role
     */
    public function setRole(int $role = null): void
    {
        //Sets a default role to visitors
        if(is_null($role))
        {
            $role = self::ROLE_VISITOR;
        }

        $acceptedRoles = [self::ROLE_ADMIN,self::ROLE_MEMBER,self::ROLE_VISITOR];

        //Checks if the role is valid
        if(array_search($role,$acceptedRoles) === false)
        {
            throw new EntityAttributeException('Role is not valid');
        }

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
        if(!is_null($username) && !preg_match('~^[a-zA-Z0-9]{3,20}$~',$username))
        {
            throw new EntityAttributeException('Username is not valid');
        }

        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email = null): void
    {
        if(!is_null($email) && !preg_match('~^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-_]+(\.[([a-z]{2,}){1,3}$~',$email))
        {
            throw new EntityAttributeException('Email is not valid');
        }

        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password = null): void
    {
        //Checks if the password has already been hashed
        if(password_get_info($password)['algo'] == 0)
        {
            $this->password = password_hash($password,PASSWORD_DEFAULT);
        }
        else
        {
            $this->password = $password;
        }
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName = null): void
    {
        if(mb_strlen($firstName) > 30)
        {
            throw new EntityAttributeException('First name is not valid');
        }
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName = null): void
    {
        if(mb_strlen($lastName) > 30)
        {
            throw new EntityAttributeException('Last name is not valid');
        }

        $this->lastName = $lastName;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title = null): void
    {
        if(mb_strlen($title) > 100)
        {
            throw new EntityAttributeException('Title is not valid');
        }

        $this->title = $title;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone = null): void
    {
        if(!is_null($phone) && !preg_match('~^[a-zA-Z0-9.\-\s]{4,20}$~',$phone))
        {
            throw new EntityAttributeException('Phone number is not valid');
        }

        $this->phone = $phone;
    }

    /**
     * @param string $baseline
     */
    public function setBaseline(string $baseline = null): void
    {
        if(mb_strlen($baseline) > 100)
        {
            throw new EntityAttributeException('Baseline should be less than 100 characters');
        }
        $this->baseline = $baseline;
    }

    /**
     * @param string $introduction
     */
    public function setIntroduction(string $introduction = null): void
    {
        if(mb_strlen($introduction) > 5000)
        {
            throw new EntityAttributeException('Baseline should be less than 5000 characters');
        }

        $this->introduction = $introduction;
    }

    /**
     * @param string $dateAdded
     */
    protected function setDateAdded(string $dateAdded = null): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @param string $notification
     */
    public function setNotification(string $notification = null): void
    {
        $this->notification = $notification;
    }

    /**
     *Sets an array with the mandatory properties
     */
    protected function setMandatoryProperties()
    {
        $this->mandatoryProperties = ['username','email','password','role'];
    }

}