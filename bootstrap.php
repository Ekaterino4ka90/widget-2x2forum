<?php
/*
Plugin Name: widget-2x2forum
Plugin URI:  https://github.com/Ekaterino4ka90/widget-2x2forum
Description: The plugin to show the latest threads from 2x2forum.ru or mywebforum.com platform.
Version:     0.1
Author:      Ekaterina Budoragina
Author URI:  https://github.com/Ekaterino4ka90
*/

CONST FORUM_VERSION = 0.1;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

define( 'FORUM_NAME',                 '2x2forum Latest Threads' );
define( 'FORUM_REQUIRED_PHP_VERSION', '5.6' );
define( 'FORUM_REQUIRED_WP_VERSION',  '3.1' );

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function forum_requirements_met() {
	global $wp_version;

	if ( version_compare( PHP_VERSION, FORUM_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}

	if ( version_compare( $wp_version, FORUM_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function forum_requirements_error() {
	global $wp_version;

	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
}

function forum_widget() {
    register_widget( 'WP_Forum_Instance' );
}

function register_script() {
    wp_register_script( 'forum_js', plugins_url('/javascript/forum-plugin.js', __FILE__), array('jquery'), FORUM_VERSION );

    wp_register_style( 'forum_css', plugins_url('/css/forum.css', __FILE__), false, FORUM_VERSION);
}

/**
 * Check requirements and load main class/hooks.
 */
if ( forum_requirements_met() ) {
	require_once( __DIR__ . '/classes/wp_forum-instance.php' );

	if ( class_exists( 'WP_Forum_Instance' ) ) {
        add_action('init', 'register_script');
        add_action( 'widgets_init', 'forum_widget' );
	}
} else {
	add_action( 'admin_notices', 'forum_requirements_error' );
}
