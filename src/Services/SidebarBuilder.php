<?php


namespace Services;


use Entities\Post;
use Entities\User;
use Models\NetworkManager;
use Models\PostManager;
use Models\UploadManager;
use Models\UserManager;

trait SidebarBuilder
{
    protected ?array $templateVars;

    /**
     * Sets the sidebar widget "last posts" list
     * @param int $nbOfPosts
     */
    public function sidebarPostsWidgetList(int $nbOfPosts)
    {
        $postManager = new PostManager();

        //Gets the list of the 3 last posts order by date_added
        $this->templateVars['lastPosts'] = $postManager->findListBy(['status' => Post::STATUS_PUBLISHED],
            [
                ListConfigurator::LIMIT => $nbOfPosts,
                ListConfigurator::ORDER => PostManager::DATE_ADDED,
                ListConfigurator::DIRECTION => ListConfigurator::DIRECTION_DESC
            ]);
    }

    public function sidebarNetworksList()
    {
        //Gets the admin data
        $userManager = new UserManager();

        $admin = $userManager->findOneBy(['role' => User::ROLE_ADMIN]);

        //Sends the list of networks links corresponding to the admin user to the twig template
        $networkManager = new NetworkManager();

        $this->templateVars['networks'] = $networkManager->findNetworksAndLinks($admin['id'],false);
    }

    public function sidebarResume()
    {
        //Gets the admin data
        $userManager = new UserManager();

        $admin = $userManager->findOneBy(['role' => User::ROLE_ADMIN]);

        //Sends the list of networks links corresponding to the admin user to the twig template
        $uploadManager = new UploadManager();

        $this->templateVars['resume'] = $uploadManager->findOneBy(['id'=>$admin['resume_id']]);
    }
}