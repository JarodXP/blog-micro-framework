<?php


namespace Models;


use Core\Manager;
use Entities\User;
use PDO;
use PDOStatement;

class UserManager extends Manager
{
    public const USERS_TABLE = 'users',
        USERNAME = 'title',
        EMAIL = 'email',
        PASSWORD = 'password',
        ROLE = 'role',
        AVATAR_ID = 'avatar_id',
        FIRST_NAME = 'first_name',
        LAST_NAME = 'last_name',
        TITLE = 'title',
        PHONE = 'phone',
        BASELINE = 'baseline',
        INTRODUCTION = 'introduction',
        RESUME_ID = 'resume_id';

    /**
     * Gets a list of Users with the requested parameters.
     * @param string|null $requestParameters
     * @return array
     */
    public function findListOf(string $requestParameters = null):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::USERS_TABLE.' '.$requestParameters);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a post in database
     * @param User $user
     * @return bool
     */
    public function insertUser(User $user):bool
    {
        $q = $this->dao->prepare(
            'INSERT INTO '.self::USERS_TABLE.'('.self::USERNAME.', '.self::EMAIL.', '.self::PASSWORD.', '
                            .self::ROLE.', '.self::AVATAR_ID.', '.self::FIRST_NAME.', '.self::LAST_NAME.', '
                            .self::TITLE.', '.self::PHONE.', '.self::BASELINE.', '.self::INTRODUCTION.', '.self::RESUME_ID.') 
                        VALUES(:username, :email, :password, :role, :avatarId, :firstName, :lastName, 
                            :title, :phone, :baseline, :introduction, : resumeId)');

        $this->bindAllFields($q, $user);

        return $q->execute();
    }

    /**
     * Gets a single post using its id
     * @param int $userId
     * @return array
     */
    public function findUser(int $userId):array
    {
        $q = $this->dao->prepare('SELECT * FROM '.self::USERS_TABLE.' WHERE id = :id');

        $q->bindValue(':id', $userId, PDO::PARAM_INT);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a given post
     * @param User $user
     * @return bool
     */
    public function updateUser(User $user)
    {
        $q = $this->dao->prepare('UPDATE '.self::USERS_TABLE.' 
        SET '.self::USERNAME.' = :username, '.self::EMAIL.' = :email, '.self::PASSWORD.' = :password, '
            .self::ROLE.' = role, '.self::AVATAR_ID.' = avatarId, '.self::FIRST_NAME.' = :firstName, '
            .self::LAST_NAME.' = lastName, '.self::TITLE.' = :title, '.self::PHONE.' = :phone, '
            .self::BASELINE.' = :baseline, '.self::INTRODUCTION.' = :introduction, '
            .self::RESUME_ID.' = :resumeId WHERE id = :id');

        $q->bindValue(':id', $user->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q, $user);

        return $q->execute();
    }

    /**
     * Removes a specific post
     * @param int $userId
     * @return bool
     */
    public function removeUser(int $userId):bool
    {
        $q = $this->dao->prepare('DELETE FROM '.self::USERS_TABLE.' WHERE id = :id');

        $q->bindValue(':id',$userId,PDO::PARAM_INT);

        return $q->execute();
    }

    /**
     * Binds all fields value with parameters
     * @param PDOStatement $q
     * @param User $user
     */
    private function bindAllFields(PDOStatement &$q, User $user)
    {
        $q->bindValue(':username',$user->getUsername());
        $q->bindValue(':email',$user->getEmail());
        $q->bindValue(':password',$user->getPassword());
        $q->bindValue(':role',$user->getRole(),PDO::PARAM_INT);
        $q->bindValue(':avatarId',$user->getAvatarId(),PDO::PARAM_INT);
        $q->bindValue(':firstName',$user->getFirstName());
        $q->bindValue(':lastName',$user->getLastName());
        $q->bindValue(':title',$user->getTitle());
        $q->bindValue(':phone',$user->getPhone());
        $q->bindValue(':baseline',$user->getBaseline());
        $q->bindValue(':introduction',$user->getIntroduction());
        $q->bindValue(':resumeId',$user->getResumeId(),PDO::PARAM_INT);
    }
}