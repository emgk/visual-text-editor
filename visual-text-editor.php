<?php
/*
Plugin Name: WP Visual Text Widget
Author: Govind Kumar
Author URI: http://github.com/emgk
Description: Replaces the default functionality of Text Widget editor with the WordPress visual editor, allowing you to use HTML in Widget and write them in rich text.
Version: 1.1
Text Domain: visual-text-editor
*/
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'WPVE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPVE_URL', plugin_dir_url( __FILE__ ) );
define( 'TEXTDOMAIN', 'visual-text-editor' );

include 'includes/class-visual-editor.php';
include 'includes/class-admin.php';
include 'includes/class-widget.php';

global $visualtexteditor;


if ( class_exists( 'VisualTextEditor' ) ) {

	// intializing VisualTextEditor Class
	$visualtexteditor = new VisualTextEditor;
}

register_activation_hook(__FILE__, 'initOption');
function initOption(){
	add_option('editorheight','300');
	add_option('overlaycolor','rgba(0,0,0,.5)');
	add_option('autop','on');
	add_option('mediabuttons','on');
	add_option('dragndrop','on');
}