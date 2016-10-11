<?php
/**
 * Auto Version Twig Extension
 *
 * Created 9/29/15 10:01 PM
 * Helper function to use Pbc/AutoVersion
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Survey\Twig\Extension
 */

namespace Twig\Extension;

use Pbc\Bandolier\Type\Strings;

class FormatForTitle extends \Twig_Extension
{

    public function getName()
    {
        return 'format_title';
    }

    public function getFilters()
    {
        return [new \Twig_SimpleFilter('format_for_title', [$this, 'titleFilter'])];
    }

    public function titleFilter($value)
    {
        return Strings::formatForTitle($value);
    }

}