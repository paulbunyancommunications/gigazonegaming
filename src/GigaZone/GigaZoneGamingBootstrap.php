<?php
namespace GigaZone;

use Cocur\Slugify\Slugify;
use GigaZone\Info\GigaZoneFromPaulBunyan;
use League\Csv\Reader;
use Pbc\AutoVersion;
use Pbc\Bandolier\Type\Strings;
use Timber\Timber;
use Timber\Menu;
use TimberSite;
use Twig_Extension_StringLoader;
use Twig_SimpleFilter;


/**
 * Class RedLakeElectricTimber
 * @package Wordpress\Timber
 */
class GigaZoneGamingBootstrap extends Timber
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
        $context['menu'] = new Menu();
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
        $twig->addFilter('str_to_bool', new Twig_SimpleFilter('str_to_bool', array($this, 'strToBool')));
        $twig->addFilter(
            'auto_version_path',
            new Twig_SimpleFilter('auto_version_path', array($this, 'autoVersionFilter'))
        );
        return $twig;
    }


    public function strToBool($string)
    {
        return \utilphp\util::str_to_bool($string);
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
        $autoVersion = new AutoVersion(env('DOCUMENT_ROOT'));
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

        return Timber::compile(['forms/update-sign-up' . ($attr['type'] ? '-' . $attr['type'] : '') . '.twig'], $attr);
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

        wp_enqueue_style('gigazone-info', get_bloginfo('stylesheet_directory') . '/css/gigazone.css');
        return $this->getInfo(
            array_merge(
                $attr,
                [
                    'uri' => GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/index.html',
                    'class' => 'GigaZone\\Info\\GigaZoneFromPaulBunyan'
                ]
            )
        );

    }

    /**
     * Get info block
     *
     * @param $attributes
     * @return string
     */
    public function getInfo($attributes)
    {
        $attr = shortcode_atts(array(
            'wrap_tag' => 'div',
            'wrap_class' => 'gigazone-info',
            'uri' => '',
            'class' => 'GigaZone\\Info\\GigaZoneFromPaulBunyan',
        ), $attributes);

        $driver = new \Stash\Driver\FileSystem(['path' => dirname(dirname(__DIR__)) . "/storage/framework/cache"]);
        $config = array_merge($attr, ['pool' => new \Stash\Pool($driver)]);
        $class = $attr['class'];
        /** @var $info */
        $info = new $class($config);

        /** @var string $content */
        $content = $info->getInfo();
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
        /** @var string $special_questions */
        /** @var string $new_line */
        /** @var string $delimiter */
        /** @var string $inputs */
        /** @var string $headings */
        $defaults = array(
            "name" => $tag,
            "questions" => "",
            "new_line" => ",",
            "delimiter" => "|",
            "inputs" => "",
            "headings" => "",
            "ids" => "",
            "special_questions" => ""
        );
        $attr = shortcode_atts($defaults, $attributes);
        extract($attr);

        /** @var string $special_questions */
        $special_questions = $attr['special_questions'];

        /** @var string $delimiter */
        $delimiter = $attr['delimiter'];

        /** @var string $new_line */
        $new_line = $attr['new_line'];

        /** @var string $name */
        $name = $attr['name'];

        /** @var string $questions */
        $questions = $attr['questions'];

        $context = self::get_context();
        foreach (array_keys($defaults) as $default) {
            switch ($default) {
                case ('new_line'):
                case ('delimiter'):
                case ('legend'):
                break;
                case ('special_questions'):
                    $context[$default] = strpos($special_questions, $delimiter) !== false ? explode($delimiter,
                        $special_questions) : [$special_questions];
                    break;
                default:
                    $this->parseCsv($context, $$default, $default, $delimiter, $new_line);
                    break;
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
        $context['action'] = '/app/' . $name;
        $context['method'] = 'POST';
        $context['content'] = $content;
        $context['legend'] = Strings::formatForTitle(str_replace('-', ' ', $name));
        $context['tag'] = $tag;
        $context['submitId'] = 'doFormSubmit';
        return Timber::compile(['forms/form-template.twig'], $context);
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

    /**
     * Get image shortcode
     * Get image by id, usage [get-image 12345]
     * This will output the image with height and width attributes and class of get-image
     * @param $attributes
     * @param $content
     * @param $tag
     * @return string|null
     */
    public function getMediaImageShortCode($attributes, $content, $tag)
    {
        if ($attributes) {
            $image = wp_get_attachment_image_src($attributes[0], '');
            return '<!-- '. $content .' --><img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" class="' . $tag . '" />';
        }

        return null;
    }

    /**
     * Get environment var by short key
     * usage: [env APP_ENV] will get the APP_ENV environment variable and return it
     *
     * @param $attributes
     * @return mixed
     */
    public function getEnvShortCode($attributes)
    {
        if ($attributes) {
            return env($attributes[0], $attributes[0]);
        }
        return null;
    }

    public function showExtraProfileFields($user)
    {
        echo '<h3>Extra Profile Information</h3>';
        for($i=0; $i < count(self::extraProfileFields()); $i++) {
            echo Timber::compile(['forms/show-extra-profile-fields.twig'], ['user' => $user, 'field' => self::extraProfileFields()[$i]]);
        }
        echo '<hr />';
    }

    public static function extraProfileFields()
    {
        return ['twitter','twitch','youtube','steam'];
    }

    public function saveExtraProfileFields($user_id)
    {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }

        /* Copy and paste this line for additional fields. */
        for($i=0; $i < count(self::extraProfileFields()); $i++) {
            if(isset($_POST[self::extraProfileFields()[$i]])) {
                update_user_meta($user_id, self::extraProfileFields()[$i], $_POST[self::extraProfileFields()[$i]]);
            }
            if(isset($_POST[self::extraProfileFields()[$i].'_profile'])) {
                update_user_meta($user_id, self::extraProfileFields()[$i].'_profile', $_POST[self::extraProfileFields()[$i].'_profile']);
            }
        }
    }

    public function userProfileShortCode($attributes)
    {
        $attr = shortcode_atts(array(
            'id' => '',
            'author_prefix' => '',
            'author_suffix' => '',
        ), $attributes);

        $user = get_user_by((is_numeric($attr['id']) ? 'ID' : 'login'), $attr['id']);
        if(!$user) {
            return false;
        }
        $userProfileData = ['fields' =>  self::extraProfileFields()];
        $userProfileData['user'] = $user;
        $userProfileData['author_prefix'] = $attr['author_prefix'];
        $userProfileData['author_suffix'] = $attr['author_suffix'];
        $userProfileData['meta'] = [];
        for($i=0; $i < count($userProfileData['fields']); $i++) {
            $userProfileData['meta'][$userProfileData['fields'][$i]] = get_user_meta($user->ID, $userProfileData['fields'][$i]);
            $userProfileData['meta'][$userProfileData['fields'][$i].'_profile'] = get_user_meta($user->ID, $userProfileData['fields'][$i].'_profile');

        }
        return Timber::compile(['partials/user/profile.twig'], $userProfileData);
    }
}
