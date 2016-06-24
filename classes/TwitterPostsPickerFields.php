<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TwitterPostsPickerFields' ) ) {

	class TwitterPostsPickerFields extends acf_field {

		public $settings = array();

    public $defaults = array();


    public function __construct( $settings ) {

      $this->name     = 'twitterposts';
      $this->label    = __( 'Twitter Posts', 'acf-twitterposts' );
      $this->category = 'jQuery';

      $this->defaults = array(
        'consumer_key' => 'ZAKa6tmDbQlB1BLGGPMJlGT4l',
        'consumer_secret' => 'H368DYLy6UGca9aJkmQDqnzhR48qaelWj9ac2aHQdo4ClXVICs',
        'access_token'  => '2833697756-pO1BkMKobuqRGbGEalb20wpD0a17vv3FrgJ9GO7',
        'access_secret' => 'vONB45UbwXv7Exi2NyCwXYqU4cP3cAbDdQNe3DzrhXI3G',
        'multiple'          => 0
      );
      parent::__construct();
      $this->settings = $settings;

    }

    public function render_all_fields($values, $field) {
      if(is_array($values)) {
        foreach ($values as $key => $value) {
          $this->_render_field_setting( $field, $value );
        }
      }
    }

    public function create_options( $field )
    {
      $field = array_merge( $this->defaults, $field );

      $fields = array();

      $fields['multiple'] = array(
				'label'   => __( 'Select multiple Posts?','acf-twitterposts' ),
				'type'    => 'radio',
				'name'    => 'multiple',
				'layout'  => 'horizontal',
				'choices' => array(
					1 => __( 'Yes', 'acf-twitterposts' ),
					0 => __( 'No', 'acf-twitterposts' ),
				),
			);

      $fields['consumer_key'] = array(
				'label'        => __( 'Consumer Key','acf-twitterposts' ),
				'type'         => 'text',
				'name'         => 'consumer_key',
				'wrapper' => array(
					'class' => 'field_advanced'
				),
			);

      $fields['consumer_secret'] = array(
				'label'        => __( 'Consumer Secret','acf-twitterposts' ),
				'type'         => 'text',
				'name'         => 'consumer_secret',
				'wrapper' => array(
					'class' => 'field_advanced'
				),
			);

      $fields['access_token'] = array(
				'label'        => __( 'Access Token','acf-twitterposts' ),
				'type'         => 'text',
				'name'         => 'access_token',
				'wrapper' => array(
					'class' => 'field_advanced'
				),
			);

      $fields['access_secret'] = array(
				'label'        => __( 'Access Secret','acf-twitterposts' ),
				'type'         => 'text',
				'name'         => 'access_secret',
				'wrapper' => array(
					'class' => 'field_advanced'
				),
			);

      $this->render_all_fields($fields, $field);

    }

    function render_field_settings( $field ) {
			self::create_options( $field );
		}

    protected function _render_field_setting( $field, $setting ) {
			if ( function_exists( 'acf_render_field_setting' ) ) {
				acf_render_field_setting( $field, $setting );
			} else {
				$setting['value'] = $field[$setting['name']];
				$setting['name']  = 'fields[' . $field['name'] . '][' . $setting['name'] . ']';
				$class = '';
				if ( isset( $setting['class'] ) && $setting['class'] ) {
					$class = ' ' . esc_attr( $setting['class'] );
				}

				if ( isset( $setting['wrapper']['class'] ) && $setting['wrapper']['class'] ) {
					$class .= ' ' . esc_attr( $setting['wrapper']['class'] );
				} ?>
				<tr class="field_option field_option_twitterposts<?php echo $class; ?>">
					<td class="label">
						<label><?php echo esc_attr( $setting['label'] ); ?></label>
						<?php if( isset( $setting['instructions'] ) && ! empty( $setting['instructions'] ) ) : ?>
						<p class="description"><?php esc_attr( $setting['instructions'] ); ?></p>
						<?php endif; ?>
					</td>
					<td><?php do_action( 'acf/create_field', $setting ); ?></td>
				</tr> <?php
			}
		}


  }

new TwitterPostsPickerFields($this->settings);
}
