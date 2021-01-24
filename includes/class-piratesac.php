<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://a-graham.com
 * @since      1.0.0
 *
 * @package    Piratesac
 * @subpackage Piratesac/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Piratesac
 * @subpackage Piratesac/includes
 * @author     A Graham <alex@a-graham.com>
 */
class Piratesac {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Piratesac_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PIRATESAC_VERSION' ) ) {
			$this->version = PIRATESAC_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'piratesac';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Piratesac_Loader. Orchestrates the hooks of the plugin.
	 * - Piratesac_i18n. Defines internationalization functionality.
	 * - Piratesac_Admin. Defines all hooks for the admin area.
	 * - Piratesac_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-piratesac-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-piratesac-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-piratesac-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-piratesac-public.php';

		$this->loader = new Piratesac_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Piratesac_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Piratesac_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new piratesac_admin( $this->get_plugin_name(), $this->get_version() );

     	$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//Add in the shiz for the menu, we used to have more - may add to in future
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );
    }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Piratesac_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//When files are changed
        $this->loader->add_filter( 'um_user_pre_updating_files_array', $plugin_public, 'piratesac_pre_update_profile' );
        //Stop messing on with expiry and issue dates
        $this->loader->add_action( 'um_user_pre_updating_profile', $plugin_public, 'piratesac_pre_update_profile_date_check');

        //Do some tidy based on yes no fields
        $this->loader->add_action( 'um_user_after_updating_profile', $plugin_public, 'piratesac_tidy_orphan_data', 10, 2);

        //Show tables for admin
        $this->loader->add_filter('the_content', $plugin_public, 'admin_dash');

        $fields = get_option("piratesac_verify_fields", "");
        $fields = trim($fields, ",");
        $fields = explode(",", $fields);

        foreach ($fields as $field)
        {
            /**
             * Below is causing problems, think not returning a hidden field from hook, will
             * let um handle it for the moment
             *
             */
            /*
            //This gets the html display for upload file box in edit mode
            $instance = new ParamPass(array($plugin_public, 'show_upload_edit'), array($field));
            add_action('um_'.$field.'_form_edit_field', array( $instance, 'invoke'));
            $instance = new ParamPass(array($plugin_public, 'show_upload_edit'), array($field, '_issue_date'));
            add_action('um_'.$field.'_issue_date_form_edit_field', array( $instance, 'invoke'));
            $instance = new ParamPass(array($plugin_public, 'show_upload_edit'), array($field, '_expiry_date'));
            add_action('um_'.$field.'_expiry_date_form_edit_field', array( $instance, 'invoke'));
            */


            //This gets the html display for upload file box in display mode
            $instance = new ParamPass(array($plugin_public, 'show_upload_view'), array($field));
            add_action('um_'.$field.'_form_show_field', array( $instance, 'invoke'));
            $instance = new ParamPass(array($plugin_public, 'show_upload_view'), array($field, '_issue_date'));
            add_action('um_'.$field.'_issue_date_form_show_field', array( $instance, 'invoke'));
            $instance = new ParamPass(array($plugin_public, 'show_upload_view'), array($field, '_expiry_date'));
            add_action('um_'.$field.'_expiry_date_form_show_field', array( $instance, 'invoke'));
        }

    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Piratesac_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}


class ParamPass
{

    function __construct( $cb, $args ) {
        $this->cb = $cb;
        $this->args = $args;
    }

    function invoke() {
        $args = func_get_args();
        return call_user_func_array( $this->cb, array_merge( $args, $this->args ) );
    }
}
