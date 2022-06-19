<?php
/*
Plugin Name: widget-2x2forum
Plugin URI:  https://github.com/Ekaterino4ka90/widget-2x2forum
Description: The plugin to show the latest threads from 2x2forum.ru or mywebforum.com platform.
Version:     1.0
Author:      Ekaterina Budoragina
Author URI:  https://github.com/Ekaterino4ka90
*/

CONST PREFIX = 'WP_FORUM_2x2';
CONST WP_FORUM2x2_VERSION = 1.0;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

define( PREFIX.'_NAME',                 	   '2x2forum Latest Threads' );
define( PREFIX.'_REQUIRED_PHP_VERSION',        '5.6' );
define( PREFIX.'_FORUM_REQUIRED_WP_VERSION',   '3.1' );

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function wp_forum2x2_requirements_met() {
	global $wp_version;

	if ( version_compare( PHP_VERSION, PREFIX.'_REQUIRED_PHP_VERSION', '<' ) ) {
		return false;
	}

	if ( version_compare( $wp_version, PREFIX.'_FORUM_REQUIRED_WP_VERSION', '<' ) ) {
		return false;
	}

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function wp_forum2x2_requirements_error() {
	global $wp_version;

	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
}

function wp_forum2x2_widget() {
    register_widget( 'WP_2x2Forum_Instance' );
}

function wp_forum2x2_register_script() {
    wp_register_script( 'wp_forum2x2_js', plugins_url('/javascript/forum-plugin.js', __FILE__), array('jquery'), WP_FORUM2x2_VERSION );

    wp_register_style( 'wp_forum2x2_css', plugins_url('/css/forum.css', __FILE__), false, WP_FORUM2x2_VERSION );
}

/**
 * Check requirements and load main class/hooks.
 */
if ( wp_forum2x2_requirements_met() ) {
	require_once( __DIR__ . '/classes/wp_2x2forum-instance.php' );

	if ( class_exists( 'WP_2x2Forum_Instance' ) ) {
        add_action( 'init', 'wp_forum2x2_register_script' );
        add_action( 'widgets_init', 'wp_forum2x2_widget' );
	}
} else {
	add_action( 'admin_notices', 'wp_forum2x2_requirements_error' );
}
