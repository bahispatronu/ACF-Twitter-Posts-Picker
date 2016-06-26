<?php
/*
Plugin Name: Advanced Custom Fields: Twitter Posts Picker
Plugin URI: http://digitalcreative.asia/
Description: Search and select Posts on Twitter/Instagram
Version: 1.0.0
Author: DigitalCreative
Author URI: http://digitalcreative.asia/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'TwitterAPI.php';

require_once 'classes/TwitterPostsPicker.php';