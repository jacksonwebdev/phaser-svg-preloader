<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       coolblueweb.com
 * @since      1.0.0
 *
 * @package    Phaser
 * @subpackage Phaser/includes
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
 * @package    Phaser
 * @subpackage Phaser/includes
 * @author     Daniel Jackson <daniel@coolblueweb.com>
 */
class Phaser {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Phaser_Loader    $loader    Maintains and registers all hooks for the plugin.
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

	private $option_fill;
	private $option_stroke;
	private $option_backend_create;
	private $option_frontend_show;

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
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'phaser';
		$this->option_fill = get_option('phaser_fill_hex');
		$this->option_stroke = get_option('phaser_stroke_hex');
		$this->option_backend_create = get_option('phaser_create_svg_bool');
		$this->option_frontend_show = get_option('phaser_show_svg_bool');

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
	 * - Phaser_Loader. Orchestrates the hooks of the plugin.
	 * - Phaser_i18n. Defines internationalization functionality.
	 * - Phaser_Admin. Defines all hooks for the admin area.
	 * - Phaser_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-phaser-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-phaser-create-svg.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-phaser-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-phaser-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-phaser-admin-options.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-phaser-public.php';


		


		$this->loader = new Phaser_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Phaser_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Phaser_i18n();

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

		$plugin_admin = new Phaser_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_options = new Phaser_Admin_Options( $this->get_plugin_name(), $this->get_version() );
		//Admin general
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Admin options
		$this->loader->add_action( 'admin_menu', $plugin_admin_options, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin_options, 'add_options_page_group' );

		// render if enabled
		$enable_svg_rendering = $this->option_backend_create;
		if( 'on' === $enable_svg_rendering ) {
			$this->loader->add_action( 'add_attachment', $plugin_admin, 'render_svg_on_upload', 1, 50 );
		}

		// enable manual ajax rendering
		$this->loader->add_action( 'wp_ajax_render_svg_ajax', $plugin_admin, 'render_svg_ajax' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// show if enabled
		// cut down on http requests

		$enable_svg_rendering = $this->option_frontend_show;
		if( 'on' === $enable_svg_rendering ) {
			$plugin_public = new Phaser_Public( $this->get_plugin_name(), $this->get_version(), $this->option_fill, $this->option_stroke );
			
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_filter( 'post_thumbnail_html', $plugin_public, 'show_svg_with_featured', 99, 5 );
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
	 * @return    Phaser_Loader    Orchestrates the hooks of the plugin.
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
