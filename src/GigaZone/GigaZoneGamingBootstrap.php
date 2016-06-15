<?php
namespace GigaZone;

use Cocur\Slugify\Slugify;
use GigaZone\Info\GigaZoneFromPaulBunyan;
use League\Csv\Reader;
use Pbc\AutoVersion;
use Pbc\Bandolier\Type\Strings;
use Timber;
use TimberSite;
use Twig_Extension_StringLoader;
use Twig_SimpleFilter;

/**
 * Class RedLakeElectricTimber
 * @package Wordpress\Timber
 */
class GigaZoneGamingBootstrap extends \TimberSite
{

    /**
     *
     */
    public function __construct()
    {
        add_filter('timber_context', array($this, 'addToContext'));
        add_filter('get_twig', array($this, 'addToTwig'));
        parent::__construct();
    }

    /**
     * Add context to timber twig output
     *
     * @param $context
     * @return mixed
     */
    public function addToContext($context)
    {
        $context['menu'] = new \TimberMenu();
        $context['site'] = $this;
        return $context;
    }

    /**
     * Twig filters and functions
     *
     * @param $twig \Twig
     * @return mixed
     */
    public function addToTwig($twig)
    {
        /* this is where you can add your own functions to twig */
        $twig->addExtension(new Twig_Extension_StringLoader());
        /**
         * Deprecated: Twig_Filter_Function, use Twig_SimpleFilter
         * http://twig.sensiolabs.org/doc/deprecated.html#filters
         * $twig->addFilter( 'whatever', new Twig_Filter_Function( 'whatever' ) );
         */
        $twig->addFilter('format_for_title', new Twig_SimpleFilter('format_for_title', array($this, 'titleFilter')));
        $twig->addFilter('slugify', new Twig_SimpleFilter('slugify', array($this, 'slugify')));
        $twig->addFilter('mdfive', new Twig_SimpleFilter('mdfive', array($this, 'mdfive')));
        $twig->addFilter(
            'auto_version_path',
            new Twig_SimpleFilter('auto_version_path', array($this, 'autoVersionFilter'))
        );
        return $twig;
    }

    public function mdfive($string)
    {
        return md5($string);
    }
    
    /**
     * Slugify filter for twig templates
     *
     * @param $string
     * @return string
     */
    public function slugify($string)
    {
        $slugify = new Slugify();
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
        $autoVersion = new AutoVersion(getenv('DOCUMENT_ROOT'));
        return $autoVersion->file($value);
    }

    // [update-sign-up]
    /**
     * @param $attributes
     * @return bool|string
     */
    public function updateSignUpFormShortCode($attributes)
    {
        $attr = shortcode_atts(array(
            'type' => '',
        ), $attributes);

        return Timber::compile('forms/update-sign-up'.($attr['type'] ? '-'.$attr['type'] : '').'.twig', $attr);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function blogInfoShortCode($attributes)
    {
        $attr = shortcode_atts(array(
            'key' => '',
            'filter' => ''
        ), $attributes);
        /** @var string $key */
        /** @var string $filter */
        return get_bloginfo($attr['key'], $attr['filter']);
    }

    /**
     * Get Gigazone info block
     *
     * @param $attributes
     * @return string
     */
    public function getGigazoneInfo($attributes)
    {
        $attr = shortcode_atts(array(
            'wrap_tag' => 'div',
            'wrap_class' => 'gigazone-info',
        ), $attributes);

        /** @var GigaZoneFromPaulBunyan $gigazone */
        $gigazone = new GigaZoneFromPaulBunyan();

        /** @var string $content */
        $content = $gigazone->getGigazoneInfo();
        wp_enqueue_style('gigazone-info', get_bloginfo('stylesheet_directory') . '/css/gigazone.css');
        return '<' . $attr['wrap_tag'] . ' class="' . $attr['wrap_class'] . '">'
        . $content
        . '</' . $attr['wrap_tag'] . '>';
    }


    /**
     * Form Fields short code
     * For generating a form from short code values
     * Example:
     * [contact-us new_line="," delimiter="|" questions="Your Name,Your Email|email" headings="A title prior to Your Name Field|your-name" inputs="your-name|name"]This is the form description[/contact-us]
     * In this example:
     * * The tag is "contact-us", which will be in the "tag" key for the view
     * * The questions csv has a new line character of "," and a delimiter of "|" so if this was a csv file/string it would look like this:
     *
     *      "Your Name",
     *      "Your Email"|"email",
     *
     *      The first column is the field label and used as the field name run through slugify
     *      These values get parsed add applied to the "questions" key
     *
     * * There's a title of "A title prior to Your Name Field" that gets applied prior to the "your-name" field
     * * The actual name of the input for "your-name" (slugify the label) will be "name" set in the inputs
     *
     * @param $attributes
     * @param $content
     * @param $tag
     * @return bool|string
     */
    public function formFieldsShortCode($attributes, $content, $tag)
    {
        /** @var string $questions */
        /** @var string $new_line */
        /** @var string $delimiter */
        /** @var string $inputs */
        /** @var string $headings */
        $defaults = array(
            "questions" => "",
            "new_line" => ",",
            "delimiter" => "|",
            "inputs" => "",
            "headings" => "",
        );
        $attr = shortcode_atts($defaults, $attributes);
        extract($attr);
        $context = Timber::get_context();
        foreach (array_keys($defaults) as $default) {
            switch ($default) {
                case ('new_line'):
                case ('delimiter'):
                    break;
                default:
                    $this->parseCsv($context, $$default, $default, $delimiter, $new_line);
            }
        }
        $autoVersion = new AutoVersion;
        // add styles ass needed
        if (stripos($questions, 'range') !== false) {
            wp_enqueue_style(
                'bootstrap-slider',
                '/../' . $autoVersion->file('/bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css')
            );
        }

        if (stripos($questions, 'boolean') !== false) {
            wp_enqueue_style(
                'bootstrap-switch',
                '/../' . $autoVersion->file('/bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')
            );
        }
        $context['action'] = '/app/' . $tag;
        $context['method'] = 'POST';
        $context['content'] = $content;
        $context['tag'] = $tag;

        return Timber::compile('forms/form-template.twig', $context);
    }

    /**
     * Parse scv values and add them to view context
     *
     * @param $context
     * @param $list
     * @param $key
     * @param string $delimiter
     * @param string $newLine
     */
    private function parseCsv(&$context, $list, $key, $delimiter = "|", $newLine = ",")
    {

        try {
            if (!is_array($list) && strlen($list) > 0) {
                $list = str_replace($newLine, "\r\n", $list);
                $csv = Reader::createFromString($list);
                $csv->setDelimiter($delimiter);
                $csv->setNewline("\n\r");
                $context[$key] = $csv->fetchAll();
            }
        } catch (\Exception $ex) {
            $context[$key] = [$ex->getMessage(), false];
        }

    }
}
