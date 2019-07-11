<?php

require_once ( plugin_dir_path( __FILE__ ) . '/vendor/potracio-modified.php' );
use Potracio\Potracio as Potracio;
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
class Phaser_Create_SVG {

	public $pot;

	
	public function __construct() {
		$this->pot = new Potracio();
	}

	public function get_image_path( $id ) {
		return get_attached_file( $id );
	}

	public function get_small_image_path( $path, $new_meta ) {
		$image_meta =  $new_meta;
		$file = $image_meta['file'];
		$small_file = $image_meta['sizes']['medium']['file'];
		$original_path = pathinfo( $path );
		$smaller_path = pathinfo( $small_file );
		return $original_path['dirname'] . '/' . $smaller_path['filename'] . '.' . $original_path['extension'];
	}

	public function get_image_data( $id, $path ) {
		$meta = wp_get_attachment_metadata( $id );
		if ( false === $meta || empty( $meta ) ) {
			$attach_data = wp_generate_attachment_metadata( $id,  $path );
			wp_update_attachment_metadata( $id,  $attach_data );
		}
		$mimetype = $this->get_mime( $path );
		$new_meta = wp_get_attachment_metadata( $id );
		$small_size_path = $this->get_small_image_path( $path, $new_meta );
		$new_meta['mime'] = $mimetype;
		$new_meta['smaller_svg_filepath'] = $small_size_path;
		
		return $new_meta;
	}

	public function get_mime( $path ) {
		$filename = $path; 
		$size = getimagesize($filename); 
		return $size['mime'];
		// switch ($size['mime']) { 
		//     case "image/gif": 
		//         echo "Image is a gif"; 
		//         break; 
		//     case "image/jpeg": 
		//         echo "Image is a jpeg"; 
		//         break; 
		//     case "image/png": 
		//         echo "Image is a png"; 
		//         break; 
		//     case "image/bmp": 
		//         echo "Image is a bmp"; 
		//         break; 
		// } 
	}

	public function create_svg( $meta ) {

		$render = $this->pot;
		$render->loadImageFromFile( $meta['smaller_svg_filepath'] );
		$render->process();
		$output = $render->getSVG( 0.3 );

		$this->place_svg( $meta['file'], $output );

	}

	public function place_svg( $file_name, $data ) {
		$wp_upload_dir = wp_upload_dir();
		$upload_dir = $wp_upload_dir['path'] . '/';

		$path = pathinfo( $file_name );

		$name = $path['filename'] . '-phaser.svg';
		try{
		    file_put_contents( $upload_dir . $name, $data );
		}catch(Exception $ex){

		}
		
	}

}
