<?php
/*
Plugin Name: Advanced Custom Fields: Twitter Posts Picker
Plugin URI: http://digitalcreative.asia/
Description: Search and select Posts from Twitter
Version: 1.0.0
Author: DigitalCreative
Author URI: http://digitalcreative.asia/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

//Plugin reference: https://github.com/elliotcondon/acf-field-type-template

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'classes/TwitterAPI.php';
require_once 'classes/TwitterPostsPicker.php';
