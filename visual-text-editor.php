<?php
/*
Plugin Name: Visual Text Editor
Author: Govind Kumar
Description: This widget will make Wordpress Default Text Widget to Visual Text Widget.
Version: 1.0
Text Domain: visual-text-editor
*/
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

include 'includes/class-visual-editor.php';

global $visualtexteditor;

// intializing VisualTextEditor Class
$visualtexteditor = new VisualTextEditor;
