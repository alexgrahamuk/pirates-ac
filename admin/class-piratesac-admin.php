<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://a-graham.com
 * @since      1.0.0
 *
 * @package    Piratesac
 * @subpackage Piratesac/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Piratesac
 * @subpackage Piratesac/admin
 * @author     A Graham <alex@a-graham.com>
 */
class Piratesac_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    private $option_name = "piratesac";

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Piratesac_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Piratesac_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/piratesac-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Piratesac_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Piratesac_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/piratesac-admin.js', array('jquery'), $this->version, false);

    }

    public function add_menu()
    {

        /*
        $this->plugin_screen_hook_suffix = add_options_page
        (
            'Piratesac',
            'Piratesac',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_options_page')
        );

        add_menu_page(
            'Settings',
            'Piratesac',
            'manage_options',
            'piratesac',
            array($this, 'display_options_page'),
            plugin_dir_url(__FILE__) . 'images/generic.png',
            20
        );
        */
    }

    public function display_options_page()
    {
        //include_once 'partials/piratesac-admin-display.php';
    }


    public function register_setting()
    {
    }

    public function dashboard_widgets()
    {
    }

    public function piratesac_widget()
    {
        //include_once 'partials/piratesac-admin-widget.php';
    }
}
