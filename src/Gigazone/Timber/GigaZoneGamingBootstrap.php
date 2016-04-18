<?php
namespace GigaZone\Timber;

use Cocur\Slugify\Slugify;
use Pbc\AutoVersion;
use Pbc\Bandolier\Type\Strings;
use TimberSite;
use Twig_Extension_StringLoader;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * Class RedLakeElectricTimber
 * @package Wordpress\Timber
 */
class GigaZoneGamingBootstrap extends TimberSite {

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

        return \Timber::compile('forms/update-sign-up.twig', $a);
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

    /**
     * Check for maintenance mode and if set return maintenance view
     */
    function maintenance_mode(){
        $path = explode('public_html', ABSPATH);
        $storage = $path[0] . '/storage/wordpress/.wp-maintenance';
        if(file_exists($storage)) {
            include(locate_template('get-context.php'));
            /**
             * Set 503 headers for maintenance mode
             * http://stackoverflow.com/a/2760908/405758
             */
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            // recheck in 300 seconds
            header('Retry-After: 300');
            
            /** @var array $context */
            // set correct body class (mimic home styles)
            $context['body_class'] = 'home page page-template-default';
            Timber::render('pages/maintenance.twig', $context);
            die();
        }
    }
    
}