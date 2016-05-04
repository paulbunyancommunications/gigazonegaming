<?php
namespace GigaZone;

use Cocur\Slugify\Slugify;
use Pbc\AutoVersion;
use Pbc\Bandolier\Type\Strings;
use Timber;
use TimberSite;
use Twig_Extension_StringLoader;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * Class RedLakeElectricTimber
 * @package Wordpress\Timber
 */
class GigaZoneGamingBootstrap extends \TimberSite {

    /**
     *
     */
    function __construct() {
        add_filter( 'timber_context', array( $this, 'add_to_context' ) );
        add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
        parent::__construct();
    }

    /**
     * Add context to timber twig output
     *
     * @param $context
     * @return mixed
     */
    function add_to_context( $context ) {
        $context['menu'] = new \TimberMenu();
        $context['site'] = $this;
        return $context;
    }

    /**
     * Twig filters and functions
     *
     * @param $twig
     * @return mixed
     */
    function add_to_twig( $twig ) {
        /* this is where you can add your own functions to twig */
        $twig->addExtension( new Twig_Extension_StringLoader() );
        /**
         * Deprecated: Twig_Filter_Function, use Twig_SimpleFilter
         * http://twig.sensiolabs.org/doc/deprecated.html#filters
         * $twig->addFilter( 'whatever', new Twig_Filter_Function( 'whatever' ) );
         */
        $twig->addFilter('format_for_title', new Twig_SimpleFilter('format_for_title', array($this, 'titleFilter')));
        $twig->addFilter('slugify', new Twig_SimpleFilter('slugify', array($this, 'slugify')));
        $twig->addFilter('auto_version_path', new Twig_SimpleFilter('auto_version_path', array($this, 'autoVersionFilter')));
        return $twig;
    }

    /**
     * Slugify filter for twig templates
     *
     * @param $string
     * @return string
     */
    public function slugify($string)
    {
        $slugify  = new Slugify();
        return $slugify->slugify($string);
    }

    /**
     * Title filter for twig templates
     *
     * @param $value
     * @return bool|mixed|string
     */
    public function titleFilter($value)
    {
        return Strings::formatForTitle($value);
    }

    /**
     * asset visioning filter for twig templates
     *
     * @param $value
     * @return mixed
     */
    public function autoVersionFilter($value)
    {
        $av = new AutoVersion(getenv('DOCUMENT_ROOT'));
        return $av->file($value);
    }

    // [update-sign-up]
    function updateSignUpFormShortCode( $atts ) {
        $a = shortcode_atts( array(
            'foo' => 'something',
            'bar' => 'something else',
        ), $atts );

        return Timber::compile('forms/update-sign-up.twig', $a);
    }

    function blogInfoShortcode( $atts ) {
        extract(shortcode_atts(array(
            'key' => '',
            'filter' => ''
        ), $atts));
        /** @var string $key */
        /** @var string $filter */
        return get_bloginfo($key, $filter);
    }
}