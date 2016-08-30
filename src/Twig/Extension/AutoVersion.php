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

class AutoVersion extends \Twig_Extension
{

    public function getName()
    {
        return 'auto_version';
    }

    public function getFilters()
    {
        return [new \Twig_SimpleFilter('auto_version_path', [$this, 'autoversionFilter'])];
    }

    public function autoversionFilter($value)
    {
        $av = new \Pbc\AutoVersion(getenv('DOCUMENT_ROOT'));
        return $av->file($value);
    }

}