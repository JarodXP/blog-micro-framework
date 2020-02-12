<?php


namespace Front;


use App\Application;
use Core\Controller;
use Entities\Upload;
use Entities\User;
use Exceptions\MailException;
use Models\UploadManager;
use Models\UserManager;
use Services\ListPaginator;
use Services\MailHandler;
use Services\SidebarBuilder;

class ProfileController extends Controller
{
    use ListPaginator, SidebarBuilder;

    public function __construct(Application $app, array $httpParameters)
    {
        parent::__construct($app, $httpParameters);

        //Sets the sidebar widget "last posts" list
        $this->sidebarPostsWidgetList(3);

        //Sets the sidebar widget "networks" list
        $this->sidebarNetworksList();

        //Sets the sidebar widget "resume"
        $this->sidebarResume();
    }

    public function displayProfileAction()
    {
        //Creates an instance of User for the admin to display profile information
        $userManager = new UserManager();

        $adminData = $userManager->findOneBy(['role' => User::ROLE_ADMIN]);

        $this->templateVars['profile'] = new User($adminData);

        //Creates an instance of Uploads to display profile's avatar
        $uploadManager = new UploadManager();

        if(!is_null($adminData['avatar_id']))
        {
            $avatarData = $uploadManager->findOneBy(['id' => $adminData['avatar_id']]);

            $this->templateVars['avatar'] = new Upload($avatarData);
        }

        //Creates an instance of Uploads to display profile's resume
        if(!is_null($adminData['resume_id']))
        {
            $resumeData = $uploadManager->findOneBy(['id' => $adminData['resume_id']]);

            $this->templateVars['resume'] = new Upload($resumeData);
        }

        $this->twigRender('/frontMyProfile.html.twig');
    }

    public function displayContactFormAction()
    {
        $this->twigRender('/frontContact.html.twig');
    }

    public function sendContactFormAction()
    {
        try
        {
            //Use of MailHandler service to send the mail
            $mailHandler = new MailHandler($this->httpParameters);

            $mailHandler->sendMail(MailHandler::CONTACT_MAIL);

            $mailHandler->sendMail(MailHandler::CONFIRMATION_MAIL);

            $this->response->redirect('/contact/thank-you');
        }
        catch (MailException $e)
        {
            $this->response->redirect('/contact',$e->getMessage());
        }
    }

    public function contactThankYouAction()
    {
        $this->templateVars['h1'] = 'Merci pour votre demande de contact!';

        $this->templateVars['content'] = 'Votre demande a bien été envoyée, 
        je m\'efforce d\'y répondre dans les plus brefs délais.';

        //Sets the link for "back" button
        $this->templateVars['previousPage'] = '/';

        $this->twigRender('/frontThankYou.html.twig');
    }
}