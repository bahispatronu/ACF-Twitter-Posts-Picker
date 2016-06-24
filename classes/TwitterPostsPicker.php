<?php

if( !class_exists( 'TwitterPostsPicker' ) )
{

	class TwitterPostsPicker
  {

		public function __construct()
    {
			$this->settings = array(
				'version'  => '1.0.0',
				'url'      => plugin_dir_url( __FILE__ ),
				'path'     => plugin_dir_path( __FILE__ ),
			);

			add_action( 'acf/include_field_types', array($this, 'include_field_types' ) ); // v5
			add_action( 'acf/register_fields', array($this, 'include_field_types' ) ); // v4

		}

		public function include_field_types( $version )
    {

		}

	}

	new TwitterPostsPicker();

}
