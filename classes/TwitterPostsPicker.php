<?php

if( !class_exists( 'TwitterPostsPicker' ) )
{

	class TwitterPostsPicker
  {

		public function __construct()
    {
			$this->settings = [
				'version'  => '1.0.0',
				'url'      => plugin_dir_url( __FILE__ ),
				'path'     => plugin_dir_path( __FILE__ ),
			];

      //for acf version 5
			add_action('acf/include_field_types', [$this, 'include_field_types' ]);
      //for acf version 4
      add_action('acf/register_fields', [$this, 'include_field_types' ]);

		}

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

	new TwitterPostsPicker();

}
