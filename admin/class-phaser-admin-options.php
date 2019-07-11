<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       coolblueweb.com
 * @since      1.0.0
 *
 * @package    Phaser
 * @subpackage Phaser/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Phaser
 * @subpackage Phaser/admin
 * @author     Daniel Jackson <daniel@coolblueweb.com>
 */
class Phaser_Admin_Options {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $page_sections;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	// White-lists options on custom pages.
	// Workaround for second issue: http://j.mp/Pk3UCF
	public function whitelist_custom_options_page( $whitelist_options ){
	    // Custom options are mapped by section id; Re-map by page slug.
	    foreach($this->page_sections as $page => $sections ){
	        $whitelist_options[$page] = array();
	        foreach( $sections as $section )
	            if( !empty( $whitelist_options[$section] ) )
	                foreach( $whitelist_options[$section] as $option )
	                    $whitelist_options[$page][] = $option;
	            }
	    return $whitelist_options;
	}

	// Wrapper for wp's `add_settings_section()` that tracks custom sections
	private function add_settings_section( $id, $title, $cb, $page ){
	    $this->add_settings_section( $id, $title, $cb, $page );
	    if( $id != $page ){
	        if( !isset($this->page_sections[$page]))
	            $this->page_sections[$page] = array();
	        $this->page_sections[$page][$id] = $id;
	    }
	}

	public function add_options_page() {

		add_options_page( 'Phaser', 'phaser', 'manage_options', 'phaser', array($this, 'add_options_template') );

	}

	public function add_options_page_group() {
		register_setting( 'phaser', 'phaser_fill_hex' );
		register_setting( 'phaser', 'phaser_stroke_hex' );
		register_setting( 'phaser', 'phaser_create_svg_bool' );
		register_setting( 'phaser', 'phaser_show_svg_bool' );
	}

	public function add_options_template() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/options/group.php';
	}


    
}
