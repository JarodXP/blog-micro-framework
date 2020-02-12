<?php


namespace Models;


use Core\Manager;
use Entities\User;
use PDO;
use PDOStatement;

class UserManager extends Manager
{
    public const TABLE = 'users',
        USERNAME = 'username',
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
     * Inserts a post in database
     * @param User $user
     * @return bool
     */
    public function insertUser(User $user):bool
    {
        //Checks if the username and email are unique
        $this->checkUniqueFields([
            self::USERNAME => $user->getUsername(),
            self::EMAIL => $user->getEmail()
        ],false);

        $q = $this->dao->prepare(
            'INSERT INTO '.self::TABLE.'('.self::USERNAME.', '.self::EMAIL.', '.self::PASSWORD.', '
                            .self::ROLE.', '.self::AVATAR_ID.', '.self::FIRST_NAME.', '.self::LAST_NAME.', '
                            .self::TITLE.', '.self::PHONE.', '.self::BASELINE.', '.self::INTRODUCTION.', '.self::RESUME_ID.') 
                        VALUES(:username, :email, :password, :role, :avatarId, :firstName, :lastName, 
                            :title, :phone, :baseline, :introduction, :resumeId)');

        $this->bindAllFields($q, $user);

        return $q->execute();
    }

    /**
     * Updates a given post
     * @param User $user
     * @return bool
     */
    public function updateUser(User $user)
    {
        //Checks if the username and email are unique
        $this->checkUniqueFields([
            self::USERNAME => $user->getUsername(),
            self::EMAIL => $user->getEmail()
        ],true,$user->getId());

        $q = $this->dao->prepare('UPDATE '.self::TABLE.' 
        SET '.self::USERNAME.' = :username, '.self::EMAIL.' = :email, '.self::PASSWORD.' = :password, '
            .self::ROLE.' = :role, '.self::AVATAR_ID.' = :avatarId, '.self::FIRST_NAME.' = :firstName, '
            .self::LAST_NAME.' = :lastName, '.self::TITLE.' = :title, '.self::PHONE.' = :phone, '
            .self::BASELINE.' = :baseline, '.self::INTRODUCTION.' = :introduction, '
            .self::RESUME_ID.' = :resumeId WHERE id = :id');

        $q->bindValue(':id', $user->getId(),PDO::PARAM_INT);

        $this->bindAllFields($q, $user);

        return $q->execute();
    }

    /**
     * Gets the username and avatar fileName for the connected user
     * @param int $id
     * @return array
     */
    public function findConnectedUserHeader(int $id)
    {
        $q = $this->dao->prepare(
            'SELECT users.username AS username, uploads.file_name AS avatarFileName
                            FROM users 
                            LEFT JOIN uploads ON users.avatar_id = uploads.id
                            WHERE users.id = :id');

        $q->bindValue(':id',$id,PDO::PARAM_INT);

        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /**
     * Gets the user resume
     * @param int $userId
     * @return array
     */
    public function findUserResume(int $userId)
    {
        $q = $this->dao->prepare(
            'SELECT users.username,
                            resume.file_name,
                            resume.original_name
                        FROM users
                        LEFT JOIN uploads AS resume ON users.resume_id = resume.id
                        WHERE users.id = :userId');

        $q->bindValue(':userId',$userId,PDO::PARAM_INT);

        $q->execute();

        return $q->fetch(PDO::FETCH_ASSOC);
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