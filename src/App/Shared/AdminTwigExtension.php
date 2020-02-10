<?php


namespace App\Shared;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AdminTwigExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('directionIcon', [$this,'headerDirectionIcon']),
            new TwigFunction('enableClass', [$this,'enableClassPageArrow']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('statusPost',[$this,'literalStatusPost']),
            new TwigFilter('statusComment',[$this,'literalStatusComment']),
            new TwigFilter('defaultAvatar',[$this,'defaultAvatar']),
            new TwigFilter('defaultProfileName',[$this,'defaultProfileName']),
            new TwigFilter('defaultBaseline',[$this,'defaultBaseline']),
            new TwigFilter('defaultIntroduction',[$this,'defaultIntroduction']),
            ];
    }

    /////////FUNCTIONS//////////////////

    /**
     * Toggles the direction icon in regards to the order requested and the header's title.
     * If the user requests the same order as header's title, it toggles direction icon
     * @param string $title
     * @param string $order
     * @param string $direction
     * @return string
     */
    public function headerDirectionIcon(string $title, string $order, string $direction):string
    {
        //Checks if headers title matches the order
        if($title == $order)
        {
            //toggles direction icon
            strtolower($direction) == 'asc'
                ? $directionIcon = '<i class="fas fa-sort-up"></i>'
                : $directionIcon = '<i class="fas fa-sort-down"></i>';
        }
        else
        {
            //default icon
            $directionIcon = '<i class="fas fa-sort"></i>';
        }

        return $directionIcon;
    }

    /**
     * Enables / disables the page arrow by setting the class depending on if the nextPage or prevPage number is null
     * @param int|null $pageNb
     * @return string
     */
    public function enableClassPageArrow(int $pageNb = null)
    {
        if(is_null($pageNb))
        {
            return 'btn-disabled';
        }
        else
        {
            return '';
        }
    }

    /////////FILTERS/////////////////////

    /**
     * Displays a literal for post status
     * @param int $status
     * @return string
     */
    public function literalStatusPost(int $status):string
    {
        if($status == 1)
        {
            return 'Publié';
        }
        else
        {
            return 'Brouillon';
        }
    }

    /**
     * Displays a literal for comments status
     * @param int $status
     * @return string
     */
    public function literalStatusComment(int $status):string
    {
        if($status == 1)
        {
            return 'Publié';
        }
        else
        {
            return 'A modérer';
        }
    }

    /**
     * Sets a default avatar if not set
     * @param string $avatarFileName
     * @return string
     */
    public function defaultAvatar(string $avatarFileName = null):string
    {
        if(is_null($avatarFileName) || $avatarFileName == '/assets/uploads/')
        {
            return '/assets/images/icons/avatar.png';
        }
        else
        {
            return $avatarFileName;
        }
    }

    /**
     * Sets a default Profile name if not set
     * @param string $profileName
     * @return string
     */
    public function defaultProfileName(string $profileName = null):string
    {
        if(is_null($profileName))
        {
            return 'John Doe';
        }
        else
        {
            return $profileName;
        }
    }

    /**
     * Sets a default baseline if not set
     * @param string $baseline
     * @return string
     */
    public function defaultBaseline(string $baseline = null):string
    {
        if(is_null($baseline))
        {
            return 'Baseline à ajouter';
        }
        else
        {
            return $baseline;
        }
    }

    /**
     * Sets a default introduction if not set
     * @param string $introduction
     * @return string
     */
    public function defaultIntroduction(string $introduction = null):string
    {
        if(is_null($introduction))
        {
            return '

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a nibh eget eros bibendum aliquet. Nullam hendrerit a orci eu porta. Phasellus pharetra feugiat nunc nec ultricies. Sed lacus ante, posuere id sapien at, convallis venenatis quam. Aliquam molestie augue ut laoreet accumsan. Nulla facilisi. Aliquam quis arcu eros. Fusce feugiat ipsum lacus, sed cursus lorem elementum quis. Nullam eu libero nisi. Donec lacinia laoreet auctor. Aliquam erat volutpat.

Ut sollicitudin dapibus metus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Integer tristique, erat sed fringilla convallis, massa magna rhoncus metus, eget gravida dui arcu vel mi. Sed cursus, nisi sed aliquet pretium, enim urna fringilla dolor, pharetra ultricies sem libero ut lectus. Phasellus congue, sem sollicitudin mattis consectetur, neque ipsum auctor mi, eget molestie nunc justo sed nibh. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec egestas risus sapien, non tincidunt urna ultricies quis. Nam hendrerit sem quis urna tempor luctus.

Nulla ac consequat felis. Duis rutrum orci ac tempus venenatis. Phasellus tincidunt justo ut ultricies vulputate. Donec pellentesque enim vel diam pellentesque, vel accumsan tortor mattis. Fusce tellus nisi, tempus non odio et, dignissim suscipit orci. Mauris quis mollis sem. Donec ac venenatis sapien, porta consequat massa. Duis facilisis tincidunt sapien non bibendum. Vestibulum lobortis nisl sit amet lacinia fringilla. Quisque ornare pulvinar tellus quis ultricies. ';
        }
        else
        {
            return $introduction;
        }
    }

}