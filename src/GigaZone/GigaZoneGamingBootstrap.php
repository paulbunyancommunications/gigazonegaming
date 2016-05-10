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

    /**
     * Get Gigazone info block
     *
     * @param $atts
     * @return string
     */
    public function getGigazoneInfo($atts)
    {
        extract(shortcode_atts(array(
            'wrap_tag' => 'div',
            'wrap_class' => 'gigazone-info',
        ), $atts));

        /** @var string $wrap_tag tag to wrap the content block */
        /** @var string $wrap_class class to add to wrap tag */
        /** @var \Gigazone\Info\GigaZoneFromPaulBunyan $gigazone */
        $gigazone = new \GigaZone\Info\GigaZoneFromPaulBunyan();

        /** @var string $content */
        $content = $gigazone->getGigazoneInfo();
        wp_enqueue_style('gigazone-info', get_bloginfo('stylesheet_directory') . '/css/gigazone.css');
        return '<'.$wrap_tag.' class="'.$wrap_class.'">' . $content . '</'.$wrap_tag.'>';
    }

    // [contact-us new_line="," delimiter="|" questions=""]
    public function formFieldsShortCode( $atts, $content, $tag ) {
        /** @var string $questions */
        /** @var string $new_line */
        /** @var string $delimiter */
        /** @var string $inputs */
        $a = shortcode_atts( array(
            "questions" => "",
            "new_line" => ",",
            "delimiter" => "|",
            "inputs" => ""
        ), $atts );
        extract($a);
        $context = Timber::get_context();
        try {
            if (!is_array($questions) && strlen($questions) > 0) {
                $questions = str_replace($new_line, "\r\n", $questions);
                $csv = \League\Csv\Reader::createFromString($questions);
                $csv->setDelimiter($delimiter);
                $csv->setNewline("\n\r");
                $context['inputs'] = $csv->fetchAll();
            }
        } catch (\Exception $ex) {
            $context = ['inputs' => [$ex->getMessage(), false]];
        }
        try {
            if (!is_array($inputs) && strlen($inputs) > 0) {
                $inputs = str_replace($new_line, "\r\n", $inputs);
                $csv = \League\Csv\Reader::createFromString($inputs);
                $csv->setDelimiter($delimiter);
                $csv->setNewline("\n\r");
                $context['real_inputs'] = $csv->fetchAll();
            }
        } catch (\Exception $ex) {
            $context = ['real_inputs' => [$ex->getMessage(), false]];
        }
        $autoversion = new \Pbc\AutoVersion;
        // add styles ass needed
        if (stripos($questions, 'range') !== false) {
            wp_enqueue_style('bootstrap-slider',
                '/../' . $autoversion->file('/bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css'));
        }

        if (stripos($questions, 'boolean') !== false) {
            wp_enqueue_style('bootstrap-switch',
                '/../' . $autoversion->file('/bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'));
        }
        $context['action'] = '/app/'.$tag;
        $context['method'] = 'POST';
        $context['content'] = $content;
        return Timber::compile('forms/form-template.twig', $context);
    }

}