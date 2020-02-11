<?php


namespace Services;


use Core\Mail;
use Entities\User;
use Exceptions\MailException;
use Models\UserManager;

class MailHandler
{
    protected User $owner;
    protected ?string $firstName;
    protected ?string $lastName;
    protected ?string $gender;
    protected ?string $message;
    protected ?string $mail;
    protected ?string $postTitle;
    protected ?string $pseudo;
    protected ?string $content;

    public const CONTACT_MAIL = 'contact',
        CONFIRMATION_MAIL = 'confirmation',
        COMMENT_MAIL = 'comment';

    public function __construct(array $emailParameters)
    {
        $this->setOwner();

        $this->hydrate($emailParameters);
    }

    /**
     * Sends the mail
     * @param string $type
     * @return bool
     * @throws MailException
     */
    public function sendMail(string $type):bool
    {
        switch ($type)
        {
            case self::CONTACT_MAIL : $mail = new Mail(
                $this->mail,
                $this->owner->getEmail(),
                'Demande de contact',
                $this->buildContactMessage());
            break;

            case self::CONFIRMATION_MAIL : $mail = new Mail(
                $this->owner->getEmail(),
                $this->mail,
                'Merci pour votre demande de contact',
                $this->buildConfirmationMessage());
            break;

            case self::COMMENT_MAIL : $mail = new Mail(
                'comment@jarod-xp.com',
                $this->owner->getEmail(),
                'Nouveau commentaire',
                $this->buildCommentsMessage());
            break;

            default;
            break;
        }

        if(isset($mail))
        {
            return $mail->sendMail();
        }
        else
        {
            throw new MailException('Un problème est survenu, veuillez ressaisir la demande');
        }
    }

    private function hydrate(array $emailParameters)
    {
        foreach ($emailParameters as $parameter => $value)
        {
            $setter = 'set'.ucfirst($parameter);

            if(is_callable([$this,$setter]))
            {
                $this->$setter($value);
            }
        }
    }

    /**
     * Sets the gender used in the construction of the message
     * @param int|null $gender
     * @throws MailException
     */
    protected function setGender(int $gender = null):void
    {
        if(!is_null($gender))
        {
            switch ($gender)
            {
                case 0: $this->gender = 'M.';
                break;

                case 1: $this->gender = 'Mme';
                break;

                default: throw new MailException('La civilité n\'est pas valide.');
            }
        }
    }

    /**
     * Sets the first name used in the construction of the message
     * @param string|null $firstName
     * @throws MailException
     */
    protected function setFirstName(string $firstName = null):void
    {
        if(!is_null($firstName) && mb_strlen($firstName) > 50)
        {
            throw new MailException('Le prénom ne peut dépasser 50 caractères');
        }

        $this->firstName = $firstName;
    }

    /**
     * Sets the last name used in the construction of the message
     * @param string|null $lastName
     * @throws MailException
     */
    protected function setLastName(string $lastName = null):void
    {
        if(!is_null($lastName) && mb_strlen($lastName) > 50 )
        {
            throw new MailException('Le nom ne peut dépasser 50 caractères');
        }

        $this->lastName = $lastName;
    }

    /**
     * Sets the contact mail
     * @param string $mail
     * @throws MailException
     */
    protected function setMail(string $mail):void
    {
        //Checks validity
        if(!preg_match('~^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-_]+(\.[([a-z]{2,}){1,3}$~',$mail))
        {
            throw new MailException('L\'adresse mail n\'est pas valide.');
        }

        $this->mail = $mail;
    }

    /**
     * Sets the message for the mail
     * @param string $message
     * @throws MailException
     */
    protected function setMessage(string $message):void
    {
        if(is_null($message) || mb_strlen($message) > 50)
        {
            throw new MailException('Le prénom ne peut dépasser 50 caractères');
        }

        $this->message = $message;
    }

    /**
     * Sets the admin owner
     */
    protected function setOwner()
    {
        $userManager = new UserManager();

        $owner = $userManager->findOneBy(['role' => User::ROLE_ADMIN]);

        $this->owner = new User($owner);
    }

    /**
     * Builds the message attribute
     * @return string
     */
    private function buildContactMessage():string
    {
        //Splits the message as it can't have more than 70 characters / line
        $formatedMessage = wordwrap($this->message, 70, "\r\n");

        //Builds the message
        return 'Vous avez reçu une nouvelle demande de contact de la part de '
            .$this->gender.' '.$this->firstName.' '.$this->lastName.'.\r\n'.
            ' Email : '.$this->mail.'r\n'.$formatedMessage;
    }

    /**
     * Builds the message attribute
     * @return string
     */
    private function buildConfirmationMessage():string
    {
        //Builds the message

        $finalMessage = 'Bonjour, \r\n 
            Vous avez envoyé le message suivant: \r\n'
            .$this->message.
            'Je vous remercie pour votre demande de contact et vous confirme sa bonne réception. \r\n
        Je ferai mon possible pour vous répondre dans les plus brefs délais.';

        return $finalMessage;
    }

    /**
     * Builds the message attribute
     * @return string
     */
    private function buildCommentsMessage():string
    {
        //Builds the message
        return 'Vous avez reçu un nouveau commentaire pour l\'article '.$this->postTitle.'.\r\n'
            .'Pseudo : '.$this->pseudo.'\r\n'
            .'Commentaire : '.$this->content;
    }
}