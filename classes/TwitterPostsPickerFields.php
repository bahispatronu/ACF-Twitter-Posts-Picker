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

      $this->defaults = array('initial_value' => '');

      parent::__construct();

      $this->settings = $settings;
    }

    public function create_options( $field )
    {
      $field = array_merge($this->defaults, $field);
      $key = $field['name'];
      // Create Field Options HTML
      ?>
      <tr class="field_option field_option_<?php echo $this->name; ?>">
      <td class="label">
        <label><?php _e("Initial Value",'acf'); ?></label>
        <p class="description"><?php _e("The initial value of the country field",'acf'); ?></p>
      </td>
      <td>
        <?php
        do_action('acf/create_field', array(
          'type'		=>	'select',
          'name'		=>	'fields['.$key.'][initial_value]',
          'value'		=>	$field['initial_value'],
          'choices'	=>	array('A','B')
        ));

        ?>
      </td>
      </tr>
      <?php

    }

  }

new TwitterPostsPickerFields();
}
