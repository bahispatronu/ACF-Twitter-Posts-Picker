<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TwitterPostsPickerFields' ) ) {

	class TwitterPostsPickerFields extends acf_field {

		// will hold info such as dir / path
		public $settings = array();

		// will hold default field options
		public $defaults = array();

		// holds the expected data from Twitter API call
		public $twitter_fields = array(
			'profile_image_url' => 'Image',
			'id' => 'ID',
			'name' => 'Name',
			'screen_name' => 'Screen Name',
			'description' => 'Description'
		);


		/*
		*  __construct
		*
		*  Set name / label needed for actions / filters
		*
		*/

		public function __construct( $settings ) {

			//modal box by wordpress
			//https://codex.wordpress.org/Javascript_Reference/ThickBox
			add_thickbox();

			// vars
      $this->name     = 'twitterposts';
      $this->label    = __( 'Twitter Post', 'acf-twitterposts' );
      $this->category = 'jQuery';

      $this->defaults = array(
        'consumer_key' => 'ZAKa6tmDbQlB1BLGGPMJlGT4l',
        'consumer_secret' => 'H368DYLy6UGca9aJkmQDqnzhR48qaelWj9ac2aHQdo4ClXVICs',
        'access_token'  => '2833697756-pO1BkMKobuqRGbGEalb20wpD0a17vv3FrgJ9GO7',
        'access_secret' => 'vONB45UbwXv7Exi2NyCwXYqU4cP3cAbDdQNe3DzrhXI3G',
				'initial_value'	=> '#twitter'
      );
      parent::__construct();
      $this->settings = $settings;

			//create a function,
			//and add it to the new hook that was created
			//method: ajax_callback
			add_action( 'wp_ajax_nopriv_getTweets', array($this, 'ajax_callback') );
			add_action( 'wp_ajax_getTweets', array($this, 'ajax_callback') );

    }

		/*
		*  create_options()
		*	 type: overloaded
		*  Create extra options for your field. This is rendered when editing a field.
		*  The value of $field['name'] can be used (like below) to save extra data to the $field
		*
		*
		*  @param	$field	- an array holding all the field's data
		*/

    public function create_options( $field )
    {
			//merger fields with default values
      $field = array_merge( $this->defaults, $field );

			//the fields we need in the ACF Fields Groups
			$fields = array();

			$fields['initial_value'] = array(
				'label'        => __( 'Initial Value','acf-twitterposts' ),
				'type'         => 'text',
				'name'         => 'initial_value',
				'wrapper' => array(
					'class' => 'field_advanced'
				),
			);

			//
      // $fields['multiple'] = array(
			// 	'label'   => __( 'Select multiple Posts?','acf-twitterposts' ),
			// 	'type'    => 'radio',
			// 	'name'    => 'multiple',
			// 	'layout'  => 'horizontal',
			// 	'choices' => array(
			// 		1 => __( 'Yes', 'acf-twitterposts' ),
			// 		0 => __( 'No', 'acf-twitterposts' ),
			// 	),
			// );

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
			//register all fields that will show in the Field Groups settings
			$this->render_all_fields($fields, $field);
    }


		/*
		*  render_field_settings()
		*
		*  Create extra settings for your field. These are visible when editing a field
		*
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/
    function render_field_settings( $field )
    {
			$this->create_options( $field );
		}

		/*
		*  create_field()
		*
		*  Create the HTML interface for your field like in Post, Page
		*
		*  @param	$field - an array holding all the field's data
		*/

    public function create_field( $field )
		{
			$field = array_merge( $this->defaults, $field);

			if ( 5 == $this->settings['acf_version'] ) {
				$field['value'] = $this->_format_value($field['value']);
			} ?>

			<div id="my-content-id" style="display:none;">
				<div class="media-content">
					<p>
						<input style="overflow: auto;" type="text" class="search-tweets"
						placeholder="Search Tweets"
						data-field-name="<?php echo $field['name']; ?>[]"
						data-consumer-key="<?php echo $field['consumer_key']; ?>"
						data-consumer-secret="<?php echo $field['consumer_secret']; ?>"
						data-access-token="<?php echo $field['access_token']; ?>"
						data-access-secret="<?php echo $field['access_secret']; ?>" >
						<button name="search" class="search button button-primary button-large">Search</button>
					</p>
					<div class="tweets-users modal clearfix">
						<div class="loading" style="display:none">
							<img align="center" src="<?php echo plugins_url( '../assets/images/loading.gif', __FILE__ ); ?>" />
						</div>
					 </div>
				</div>
				<div class="modal-footerbar">
					<p style="text-align:center"><input type="submit" class="button button-primary button-large modal-done" value="Done" onclick="tb_remove()"></p>
				</div>
			</div>
			<input alt="#TB_inline?height=500&width=800&inlineId=my-content-id" title="Select a Tweet" class="thickbox button button-primary button-large" type="button" value="Select" />
			<div class="selected-tweets">
				<div class="tweets-users clearfix">
					<?php
						$this->generateData($field['value'], $field['name']);
					 ?>
				</div>
			</div>
			<?php
		}

		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param	$field (array) the $field being rendered
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/

    public function render_field( $field ) {

			$this->create_field( $field );

		}

		/*
		*  input_admin_enqueue_scripts()
		*
		*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		*  Use this action to add CSS + JavaScript to assist your render_field() action.
		*
		*  @type	action (admin_enqueue_scripts)
		*
		*  @param	n/a
		*  @return	n/a
		*/

    public function input_admin_enqueue_scripts()
    {

      $url     = $this->settings['url'];
			$version = $this->settings['version'];

			wp_register_script( 'acf-twitterposts-js', plugins_url( '../assets/js/twitterposts.js', __FILE__ ), array('acf-input'), $version );
			wp_register_style( 'acf-input-twitterposts', plugins_url( '../assets/css/input.css', __FILE__ ), array( 'acf-input' ), $version );

			//for ajax calling named 'gettweet'
			wp_localize_script( 'acf-twitterposts-js', 'gettweet', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			));

			wp_enqueue_script(array('acf-twitterposts-js'));
			wp_enqueue_style(array('acf-input-twitterposts'));

    }

		/*
		*  format_value()
		*
		*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
		*
		*  @param	$value (mixed) the value which was loaded from the database
		*  @param	$post_id (mixed) the $post_id from which the value was loaded
		*  @param	$field (array) the field array holding all the field options
		*
		*  @return	$value (mixed) the modified value
		*/

		function format_value( $value, $post_id, $field )
		{

			if(is_array($value)) {
				$array = array();

				foreach ($value as $k => $v) {
					$array[$k] = json_decode( $v, true );
				}

				return $array;
			}

		}

		//format value for version 5 of acf
		public function _format_value($value)
		{

			$data = array();

			if ( is_array( $value ) && count( $value ) > 0 ) {
				foreach ( $value as $k => $v ) {
					$data[] = json_decode( $v, true );
				}
			}

			return $data;

		}

		/*
		*  ajax_callback()
		*
		*  for ajax callback
		*  @return	all tweets from API
		*/

		public function ajax_callback()
		{

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

				if(!empty($_POST)) {
					$keyword = $_POST['keyword'];
					$fieldName = $_POST['fieldName'];
					$consumerKey = $_POST['consumerKey'];
					$consumerSecret = $_POST['consumerSecret'];
					$accessToken = $_POST['accessToken'];
					$accessSecret = $_POST['accessSecret'];

					$twitter = new TwitterAPI(
						$consumerKey,
						$consumerSecret,
						$accessToken,
						$accessSecret
					);

					$parameters = array(
						"q" => urlencode($keyword),
						'lang' => 'en',
						'result_type' >= 'recent',
					);
					$posts = $twitter->getTweets($parameters);

					$html = '';
					foreach ($posts->statuses as $users) {
						$html .= '<div class="profile" data-unique="'.uniqid().'" data-img="'.$users->user->profile_image_url.'"
			        data-id="'.$users->user->id.'" data-name="'.$users->user->name.'"
			        data-screen="'.$users->user->screen_name.'" data-desc="'.substr($users->user->description, 0, 50).'"
			        data-field-name="'.$fieldName.'"
			        data-value="'.esc_attr( json_encode( $this->getValues($users->user, $this->twitter_fields) ) ).'" >';
						$html .= '<input type="hidden" name="'.esc_attr( $fieldName ).'" value="'.esc_attr( json_encode($this->getValues($users->user, $this->twitter_fields)) ).'">';
		        $html .= '<img width="80" src="'.$users->user->profile_image_url.'">';
		        $html .= '<p><strong>ID</strong>: '.$users->user->id.'</p>';
		        $html .= '<p><strong>Name</strong>: '.$users->user->name.'</p>';
		        $html .= '<p><strong>Screen Name</strong>: '.$users->user->screen_name.'</p>';
		        $html .= '<p><strong>Description</strong>: '.substr($users->user->description, 0, 50).'</p>';
		      	$html .= '</div>';

					}
					echo $html;
				}

			}
			die();

		}

		/*
		*  render_all_fields()
		*	 this method will render settings field
		*  in the Field Groups settings in ACF
		*
		*/
		public function render_all_fields($values, $field)
    {

      if(is_array($values)) {
        foreach ($values as $key => $value) {
          $this->_render_field_setting( $field, $value );
        }
      }

    }


		//custom method to generate data
		public function generateData($field, $name)
		{

			if(!empty($field)) {
				?>
				<input type="hidden" name="<?php echo esc_attr( $name );?>[]" value=""/>
				<?php
				foreach ($field as $key => $tweet) {
					if(!empty($tweet)) {
					 ?>
						<div class="profile current">
							<input type="hidden" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( json_encode($tweet) ); ?>">
							<img width="80" src="<?php echo $tweet['profile_image_url']; ?>">
							<p><strong>ID</strong>: <?php echo $tweet['id']; ?></p>
							<p><strong>Name</strong>: <?php echo $tweet['name']; ?></p>
							<p><strong>Screen Name</strong>: <?php echo $tweet['screen_name']; ?></p>
							<p><strong>Description</strong>: <?php echo substr($tweet['description'], 0, 50); ?></p>
						</div>
					<?php
					}
				}
			}

		}

		//it is called by render_field_settings()
		protected function _render_field_setting( $field, $setting )
    {

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

		//custom helper method
		public function getValues($values, $search)
		{

			if(is_array($search)) {
				$data = array();
				foreach ($search as $key => $value) {
					if(array_key_exists($key, $values)) {
						$data[$key] = htmlentities($values->$key);
					}
				}
				return $data;
			}
			
		}

  }

new TwitterPostsPickerFields($this->settings);

}
?>
