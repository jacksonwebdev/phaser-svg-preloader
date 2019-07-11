<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       coolblueweb.com
 * @since      1.0.0
 *
 * @package    Phaser
 * @subpackage Phaser/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Phaser
 * @subpackage Phaser/public
 * @author     Daniel Jackson <daniel@coolblueweb.com>
 */
class Phaser_Public {

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

	private $option_fill;
	private $option_stroke;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $fill_option, $stroke_option ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->option_fill = $fill_option;
		$this->option_stroke = $stroke_option;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phaser_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phaser_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/phaser-public.css', array(), $this->version, 'all' );

		// options from options page
		$fill_color = $this->option_fill;
		$stroke_color = $this->option_stroke;

		///// I wonder how often this is used ////
		$css = '.cbw-phaser-load-container path { ';
		$fill_css = 'fill:#' . $fill_color . ';';
		$stroke_css = 'stroke:#' . $stroke_color . ';';
		$css_end = '}';
		/////
		if ( '' !== $fill_color && !empty( $fill_color ) ) {
			$css .= $fill_css;
		}
		if ( '' !== $stroke_color && !empty( $stroke_color ) ) {
			$css .= $stroke_css;
		}

		$css .= $css_end;
        
        wp_add_inline_style( $this->plugin_name, $css );
        //////////////////////////////////////////

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phaser_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phaser_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/phaser-public.js', array( 'jquery' ), $this->version, false );

	}

	public function show_svg_with_featured($html, $post_id, $post_thumbnail_id, $size, $attr) {
		$src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
		$svg_path = $this->get_svg_path( $post_thumbnail_id );
		$svg = $this->get_svg( $svg_path );
		$classes = $this->get_svg_classes( '', $svg );
		$html = '<div class="cbw-phaser-load-container">';
		$html .= $svg;
		$html .= '<img alt="" src="' . $src['0'] . '" data-alt="" class="wp-post-image ' . $classes . '" />';
		$html .= '</div>';
		return $html;
	}

	public function get_svg_path( $post_thumbnail_id ) {
		$svg_util = new Phaser_Create_SVG();
		$path = $svg_util->get_image_path( $post_thumbnail_id );
		$pathinfo = pathinfo($path);
		$svg_name = $pathinfo['filename'] . '-phaser.svg';
		$svg_path = $pathinfo['dirname'] . '/' . $svg_name;
		return $svg_path;
	}

	public function get_svg( $svg_path ) {
		$svg = '';
		try{
		    $svg = file_get_contents( $svg_path );
		}catch(Exception $ex){
		    $svg = '';
		}

		return $svg;
	}

	public function get_svg_classes( $classes, $svg ) {
		$classes = array();
		if ( '' !== $svg ) {
			array_push( $classes , 'cbw-phaser-selector' );
			array_push( $classes , 'cbw-phaser-loading' );
		}
		return implode( ' ', $classes );
	}




}
