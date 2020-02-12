<?php


namespace Core;


use Exceptions\MailException;

class Mail
{
    protected string $to, $from, $subject, $message;
    protected array $headers = [];

    /**
     * Mail constructor.
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $message
     * @throws MailException
     */
    public function __construct(string $from, string $to, string $subject, string $message)
    {
        $this->setTo($to);

        $this->setFrom($from);

        $this->setSubject($subject);

        $this->setMessage($message);

        $this->setHeaders();
    }

    /**
     * Sends the mail
     * @return bool
     */
    public function sendMail():bool
    {
        return mail($this->to, $this->subject, $this->message, $this->headers);
    }

    /**
     * Sets the headers
     * @param string $from
     */
    private function setHeaders()
    {
        $this->headers =[
            'From' => $this->from,
            'Reply-To' => $this->from,
            'Content-Type' => 'text/html; charset="UTF-8"',
            'Mime-Version' => '1.0',
            'X-Mailer' => 'PHP/' . phpversion()
        ];
    }

    /**
     * Sets the destination mail
     * @param string $to
     * @throws MailException
     */
    private function setTo(string $to)
    {
        //Checks validity
        if(!preg_match('~^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-_]+(\.[([a-z]{2,}){1,3}$~',$to))
        {
            throw new MailException('L\'adresse mail du destinataire n\'est pas valide.');
        }

        $this->to = $to;
    }

    /**
     * @param string $from
     * @throws MailException
     */
    private function setFrom(string $from)
    {
        //Checks validity
        if(!preg_match('~^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-_]+(\.[([a-z]{2,}){1,3}$~',$from))
        {
            throw new MailException('L\'adresse mail de l\'expéditeur n\'est pas valide.');
        }

        $this->from = $from;
    }

    /**
     * @param string $subject
     * @throws MailException
     */
    private function setSubject(string $subject)
    {
        //Checks the subject size (limit of gmail is 130)
        if(mb_strlen($subject) > 130)
        {
            throw new MailException('La longueur de l\'objet ne doit pas dépasser 130 caractères');
        }

        $this->subject = $subject;
    }

    /**
     * @param string $message
     * @throws MailException
     */
    private function setMessage(string $message)
    {
        //Checks the subject size (limit of gmail is 130)
        if(mb_strlen($message) > 5000)
        {
            throw new MailException('La longueur de l\'objet ne doit pas dépasser 5000 caractères');
        }

        $this->message = $message;
    }



}