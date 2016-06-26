<?php
// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists( 'TwitterPostsPicker' ) )
{
	class TwitterPostsPicker
  {

		/*
		*  __construct
		*
		*  This function will setup the class functionality
		*  @param	n/a
		*  @return	n/a
		*/

		public function __construct()
    {
			// vars
			$this->settings = [
				'version'  => '1.0.0',
				'url'      => plugin_dir_url( __FILE__ ),
				'path'     => plugin_dir_path( __FILE__ ),
			];

			// set text domain
			// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
			load_plugin_textdomain('acf-twitterposts', false, plugins_url( '../lang', __FILE__ ));

      //for acf version 5
			add_action('acf/include_field_types', array($this, 'include_field_types' ));
      //for acf version 4
      add_action('acf/register_fields', array($this, 'include_field_types' ));
		}


		/*
		*  include_field_types
		*
		*  This function will include the field type class
		*
		*  @param	$version (int) major ACF version. Defaults to false
		*  @return	n/a
		*/

		public function include_field_types($version)
    {
      //select version
      if ( ! is_numeric( $version ) ) {
				$version = 4;
			}
      $this->settings['acf_version'] = absint( $version );
			require_once 'TwitterPostsPickerFields.php';
		}
	}

// initialize
new TwitterPostsPicker();
}
