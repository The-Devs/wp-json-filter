<?php
/**
 * @package My JSON View
 */
/*
Plugin Name: My JSON View
Plugin URI: https://thedevs.com.br/utils/wordpress/mjsonv
Description: My JSON View allows you to fetch data from external REST API and render specific JSON data in your own wordpress website. 
Version: 1.0.0
Author: Enrique René
Author URI: https://thedevs.com.br/equipe/enriquerene
License: GPLv2 or later
Text Domain: json-vue
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

define( "MJSONV_VERSION", "1.0.0" );
define( "MJSONV_MINIMUM_WP_VERSION", "5.4" );
define( "MJSONV_ROOT_DIR", __FILE__ );
define( "MJSONV_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );
define( "MJSONV_FUNCTIONS_DIR", MJSONV_PLUGIN_DIR );

define( "MJSONV_ROOT_PATH", "/wp-content/plugins/my-json-view/" );
define( "MJSONV_VIEWS_PATH", MJSONV_ROOT_PATH . "views" . DIRECTORY_SEPARATOR );

define( "MJSONV_FRONT_SLUG", "mjsonv" );
define( "MJSONV_FRONT_TITLE", "My JSON View" );
define( "MJSONV_FRONT_ID", "mjsonv" );

define( "MJSONV_DEFAULT_URL", "https://jsonplaceholder.typicode.com/users" );
define( "MJSONV_DEFAULT_SINGLE_URL_ATTRIBUTE", "id" );
define( "MJSONV_DEFAULT_ATTRIBUTES", "id,name,username" );

require_once( MJSONV_PLUGIN_DIR . "class.mjsonv.php" );
require_once( MJSONV_FUNCTIONS_DIR . "functions.php" );

register_activation_hook( MJSONV_ROOT_DIR, "mjsonv_plugin_activation" );
register_deactivation_hook( MJSONV_ROOT_DIR, "mjsonv_plugin_deactivation" );

add_action( "wp_enqueue_scripts", "mjsonv_init" );
add_shortcode( 'mjsonv', 'mjsonv_build_app' );

if ( is_admin() || ( defined( "WP_CLI" ) && WP_CLI ) ) {
	// require_once( MJSONV_PLUGIN_DIR . "class.mjsonv-admin.php" );
	// add_action( "init", array( "Akismet_Admin", "init" ) );
}

if ( defined( "WP_CLI" ) && WP_CLI ) {
	// require_once( MJSONV__PLUGIN_DIR . "class.mjsonv-cli.php" );
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-json-filter/v1', '/blog/(?P<ID>\d+)', array(
    'methods' => 'GET',
    'callback' => 'idQuery',
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-json-filter/v1', '/blog', array(
    'methods' => 'GET',
    'callback' => 'noIdQuery',
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-json-filter/v1', '/consultation', array(
    'methods' => 'GET',
    'callback' => 'noIdQuery',
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-json-filter/v1', '/shop', array(
    'methods' => 'GET',
    'callback' => 'noIdQuery',
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-json-filter/v1', '/shop/(?P<ID>\d+)', array(
    'methods' => 'GET',
    'callback' => 'idQuery',
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-json-filter/v1', '/shop/(?P<ID>\d+)/reviews', array(
    'methods' => 'GET',
    'callback' => 'idQueryR',
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-json-filter/v1', '/shop/(?P<ID>\d+)/reviews/(?P<IDr>\d+)', array(
    'methods' => 'GET',
    'callback' => 'idQueryRID',
  ) );
} );

function idQuery( $data ) {
  $prefix = 'dev';
  $username = 'plugin';
  $password = 'plugin';
  $pdo = new PDO('mysql:host=localhost;dbname=dev_plugin', $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $post_ID = $data['ID'];
  $sql = "SELECT * FROM {$prefix}posts WHERE ID = $post_ID";
  $prepare = $pdo->prepare($sql);
  $prepare->execute();
  $result = $prepare->fetchall();
  return $result;
}

function idQueryR( $data ) {
  $prefix = 'dev';
  $username = 'plugin';
  $password = 'plugin';
  $pdo = new PDO('mysql:host=localhost;dbname=dev_plugin', $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $post_ID = $data['ID'];
  $sql = "SELECT * FROM {$prefix}comments WHERE comment_post_ID = $post_ID";
  $prepare = $pdo->prepare($sql);
  $prepare->execute();
  $result = $prepare->fetchall();
  return $result;
}

function idQueryRID( $data ) {
  $prefix = 'dev';
  $username = 'plugin';
  $password = 'plugin';
  $pdo = new PDO('mysql:host=localhost;dbname=dev_plugin', $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $comment_ID = $data['ID'];
  $r_ID = $data['IDr'];
  $sql = "SELECT * FROM {$prefix}comments WHERE comment_ID = $r_ID AND comment_post_ID = $comment_ID";
  $prepare = $pdo->prepare($sql);
  $prepare->execute();
  $result = $prepare->fetchall();
  return $result;
}

function noIdQuery( $data ) {
  $prefix = 'dev';
  $username = 'plugin';
  $password = 'plugin';
  $pdo = new PDO('mysql:host=localhost;dbname=dev_plugin', $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM {$prefix}posts";
  $prepare = $pdo->prepare($sql);
  $prepare->execute();
  $result = $prepare->fetchall();
  return $result;
}
