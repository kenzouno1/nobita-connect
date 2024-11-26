<?php if (!defined('ABSPATH')) {
    exit;
}
/**
 * Class NF_Nobi
 */
final class NF_Nobi
{
    const VERSION = '3.0.5';
    const SLUG = 'nobi';
    const NAME = 'nobi';
    const AUTHOR = 'Nobi';
    const PREFIX = 'NF_Nobi';

    /**
     * @var NF_Nobi
     * @since 3.0
     */
    private static $instance;

    /**
     * Plugin Directory
     *
     * @since 3.0
     * @var string $dir
     */
    public static $dir = '';

    /**
     * Plugin URL
     *
     * @since 3.0
     * @var string $url
     */
    public static $url = '';

    /**
     * Main Plugin Instance
     *
     * Insures that only one instance of a plugin class exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 3.0
     * @static
     * @static var array $instance
     * @return NF_Nobi Highlander Instance
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof NF_Nobi)) {
            self::$instance = new NF_Nobi();

            self::$dir = plugin_dir_path(__FILE__);

            self::$url = plugin_dir_url(__FILE__);

            /*
             * Register our autoloader
             */
            spl_autoload_register(array(self::$instance, 'autoloader'));
        }
    }

    public function __construct()
    {
        /*
         * Optional. If your extension processes or alters form submission data on a per form basis...
         */
        add_filter( 'ninja_forms_register_merge_tags',  array($this,"register_tags" ));
        add_filter('ninja_forms_register_actions', array($this, 'register_actions'));
    }

    public function register_tags($merge_tags){
        $merge_tags["nobi"] = new NF_Nobi_MergeTags_Nobi();
        return $merge_tags;
    }

    /**
     * Optional. If your extension processes or alters form submission data on a per form basis...
     */
    public function register_actions($actions)
    {
        $actions['nobi'] = new NF_Nobi_Actions_Nobi();

        return $actions;
    }

    /*
     * Optional methods for convenience.
     */

    public function autoloader($class_name)
    {
        if (class_exists($class_name)) {
            return;
        }

        if (false === strpos($class_name, self::PREFIX)) {
            return;
        }
       

        $class_name = str_replace(self::PREFIX, '', $class_name);
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;

        $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

        if (file_exists($classes_dir . $class_file)) {
            require_once $classes_dir . $class_file;
        }
    }

    /**
     * Template
     *
     * @param string $file_name
     * @param array $data
     */
    public static function template($file_name = '', array $data = array())
    {
        if (!$file_name) {
            return;
        }

        extract($data);

        include self::$dir . 'includes/Templates/' . $file_name;
    }

    /**
     * Config
     *
     * @param $file_name
     * @return mixed
     */
    public static function config($file_name)
    {
        return include self::$dir . 'includes/Config/' . $file_name . '.php';
    }


}

/**
 * The main function responsible for returning The Highlander Plugin
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 3.0
 * @return {class} Highlander Instance
 */
function NF_Nobi()
{
    return NF_Nobi::instance();
}

NF_Nobi();
