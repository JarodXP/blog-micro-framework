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
    protected string $signature;

    public const CONTACT_MAIL = 'contact',
        CONFIRMATION_MAIL = 'confirmation',
        COMMENT_MAIL = 'comment';

    public function __construct(array $emailParameters)
    {
        $this->setOwner();

        $this->setSignature();

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
     * Sets the post title for the comment mail
     * @param string $title
     */
    protected function setPostTitle(string $title):void
    {
        $this->postTitle = $title;
    }

    /**
     * Sets the comment content
     * @param string $content
     */
    protected function setContent(string $content):void
    {
        $this->content = $content;
    }

    /**
     * Sets the comment pseudo
     * @param string $pseudo
     */
    protected function setPseudo(string $pseudo):void
    {
        $this->pseudo = $pseudo;
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

    protected function setSignature()
    {
        $this->signature = '<p style="font-weight: bold; color: darkorange">'.$this->owner->getFirstName().
                                ' '.$this->owner->getLastName().'</p>
                            <p style="font-style: italic">'.$this->owner->getTitle().'</p>
                            <p>'.$this->owner->getPhone().'</p>
                            <p>'.$this->owner->getEmail().'</p>
                            <p><a href="https://developer.jarod-xp.com">developer.jarod-xp.com</a></p>';
    }

    /**
     * Builds the message attribute
     * @return string
     */
    private function buildContactMessage():string
    {
        //Splits the message as it can't have more than 70 characters / line
        return
            '<p>Vous avez reçu une nouvelle demande de contact de la part de :'
            .$this->gender.' '.$this->firstName.' '.$this->lastName.'</p>
            <p>Email: '.$this->mail.'</p>
            <p>Message: </p>
            <p style="font-style: italic">'.wordwrap($this->message, 70, "<br>").'</p>';
    }

    /**
     * Builds the message attribute
     * @return string
     */
    private function buildConfirmationMessage():string
    {
        //Builds the confirmation name
        if($this->firstName != '')
        {
            $name = $this->firstName;
        }
        elseif($this->lastName != '' && !is_null($this->gender))
        {
            $name = $this->gender.' '.$this->lastName;
        }
        else
        {
            $name = '';
        }

        //Builds the message
        return '<p>Bonjour '.$name.', </p>
            <p>Vous avez envoyé le message suivant: <br>
            <span style="font-style: italic">'.$this->message.'</span></p>
            <p>Je vous remercie pour votre demande de contact et vous confirme sa bonne réception. </p>
            <p>Je ferai mon possible pour vous répondre dans les plus brefs délais.</p>'
            .$this->signature;
    }

    /**
     * Builds the message attribute
     * @return string
     */
    private function buildCommentsMessage():string
    {
        //Builds the message
        return
            '<p>Bonjour '.$this->owner->getUsername().'</p>
            <p>Vous avez reçu un nouveau commentaire pour l\'article :</p> 
            <p><strong>'.$this->postTitle.'</strong></p>
            <p>
                <ul>
                    <li>'.'Pseudo : '.$this->pseudo.'</li>
                    <li>Commentaire : '.$this->content.'</li>
                </ul>
            </p>
            <p>Aller aux commentaires : <a href="https://developer.jarod-xp.com/admin/comments">Commentaires</a></p>';
    }
}