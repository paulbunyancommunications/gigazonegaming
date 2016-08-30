<?php
/**
 * Slugify Twig Extension
 *
 * Created 9/29/15 10:01 PM
 * Helper function to use \Cocur\Slugify\Slugify
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Twig\Extension
 */

namespace Twig\Extension;

class Slugify extends \Twig_Extension
{

    public function getName()
    {
        return 'slugify';
    }

    public function getFilters()
    {
        return [new \Twig_SimpleFilter('slugify', [$this, 'slugifyFilter'])];
    }

    public function slugifyFilter($value)
    {
        $slugify = new \Cocur\Slugify\Slugify();
        return $slugify->slugify($value);
    }

}