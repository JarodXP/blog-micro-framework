<?php


namespace App\Shared;


use Twig\Extension\AbstractExtension;
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

}