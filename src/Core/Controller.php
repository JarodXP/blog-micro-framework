<?php


namespace Core;


use App\Application;
use App\Shared\AdminTwigExtension;
use Models\UserManager;
use ReflectionClass;
use ReflectionException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected Application $app;
    protected FilesystemLoader $twigLoader;
    protected Environment $twigEnvironment;
    protected ?array $httpParameters;//The POST and GETS parameters sanitized in Router and gathered in this array
    protected HttpResponse $response;
    protected ?array $templateVars;//The variables array to be sent to twig render

    public function __construct(Application $app, array $httpParameters)
    {
        $this->app = $app;

        //Defines the calling Controller "Views" directory path
        $viewsPath = $this->defineViewsDirectoryPath(static::class);

        //Sets the Twig loader
        $this->twigLoader = new FilesystemLoader([$viewsPath, __DIR__ . '/../App/Shared/Views']);

        //Sets the Twig environment
        $this->twigEnvironment = new Environment($this->twigLoader,$this->app->getEnvironmentOptions());

        //Set globals variables to use on all templates
        $this->twigEnvironment->addGlobal('locale', $GLOBALS['locale']);
        $this->twigEnvironment->addGlobal('charset', $GLOBALS['charset']);
        $this->twigEnvironment->addGlobal('env', $GLOBALS['env']);
        $this->twigEnvironment->addGlobal('notification',$_SESSION['user']->getNotification());

        //Sets the Debug extension in development environment
        if($this->twigEnvironment->isDebug())
        {
            $this->twigEnvironment->addExtension(new DebugExtension());
        }

        //Sets the custom Twig Extensions
        $this->twigEnvironment->addExtension(new AdminTwigExtension());

        //Sets the parameters to be used in the actions
        $this->httpParameters = $httpParameters;

        //Sets an instance of HttpResponse for the Controllers to send response errors
        $this->response = new HttpResponse();

        //Sets the connected user info as default $templateVars to be sent to the render
        if(isset($_SESSION['user']) && !is_null($_SESSION['user']->getId()))
        {
            $userManager = new UserManager();

            $connectedUser = $userManager->findConnectedUserHeader($_SESSION['user']->getId());

            $this->templateVars['connectedUser'] = [
                'avatarFileName' => $connectedUser['avatarFileName'],
                'username' => $connectedUser['username']
            ];
        }
    }

    /**
     * Defines the path of the Views directory for Twig Loader
     * Path depends on the controller class calling the constructor
     * @param string $controllerName
     * @return string
     */
    private function defineViewsDirectoryPath(string $controllerName):string
    {
        //Gets the complete filename (path and name) of the calling Controller
        try
        {
            $reflector = new ReflectionClass($controllerName);
        }
        catch (ReflectionException $e)
        {
            print_r($e->getMessage());
            exit();
        }

        $controllerFileName = $reflector->getFileName();

        //Removes the name of the Controller in the file name to defines the calling Controller directory path
        $controllerDirPath = str_replace($reflector->getShortName().'.php','',$controllerFileName);

        //Returns the Views directory path
        return $controllerDirPath.'Views';
    }

    /**
     * Centralized twig render
     * @param string $template
     * @param array $twigVars
     */
    protected function twigRender(string $template):void
    {
        try
        {
            echo $this->twigEnvironment->render($template,$this->templateVars);

            exit();
        }
        catch (LoaderError | RuntimeError | SyntaxError $e)
        {
            print_r($e->getMessage());
        }
    }
}