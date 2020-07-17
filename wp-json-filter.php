<?php
/**
 * @package WP JSON Filter
 */
/*
Plugin Name: WP JSON Filter
Plugin URI: https://github.com/The-Devs/wp-json-filter
Description: WP JSON Filter is a WordPress plugin that adds new endpoints to the WP REST API. The goal of WP JSON Filter is to simplify the JSON interface of default WordPress routes, such as reading posts, for example.
Version: 1.0.0
Author: Enrique René
Author URI: https://enriquerene.com.br
Developer: Yago Gomes
Developer URI: https://github.com/yagocgomes
License: GPLv3
Text Domain: 
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; 

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

define( "WPJSONFILTER_VERSION", "1.0.0" );
define( "WPJSONFILTER_MINIMUM_WP_VERSION", "5.4" );
define( "WPJSONFILTER_ROOT_DIR", __FILE__ );
define( "WPJSONFILTER_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );

define( "WPJSONFILTER_ROOT_PATH", "/wp-content/plugins/wp-json-filter/" );

function endpointsFactory () {
	// Convocar uma add_action
  // Essa add_action convoca todas as register_rest_route 
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
      'callback' => 'noIdQueryC',
    ) );
  } );
  
  add_action( 'rest_api_init', function () {
    register_rest_route( 'wp-json-filter/v1', '/shop', array(
      'methods' => 'GET',
      'callback' => 'noIdQueryS',
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
}
function wpjsonfilter_plugin_activation () {
	// O que estiver aqui acontece durante a ativação do plugin
  // As endpoints devem ser criadas aqui
  endpointsFactory();
}
function wpjsonfilter_plugin_deactivation () {
	// O que estiver aqui acontece durante a desativação do plugin
	// As endpoints devem ser destruídas aqui
}

// Registrando hooks de ativação e desativação
register_activation_hook( WPJSONFILTER_ROOT_DIR, "wpjsonfilter_plugin_activation" );
register_deactivation_hook( WPJSONFILTER_ROOT_DIR, "wpjsonfilter_plugin_deactivation" );


if ( is_admin() || ( defined( "WP_CLI" ) && WP_CLI ) ) {
	// For customizitaion page in admin area
}
if ( defined( "WP_CLI" ) && WP_CLI ) {
	// For customizitaion page via CLI interface
}

/*
	SUGESTÃO
	uma função que faz todos os add_actions de uma vez só.
	Chamei essa função de endpointsFactory
*/




/*
	SUGESTÃO
	É muito mais seguro e estável usar a interface de banco de dados do próprio WP.
	Veja um exemplo de como fazer isso no arquivo functions.php (só mantive o arquivo para voce ver como usar a interface. Depois pode apagar.)
*/

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
  $tags = get_the_tags( $post_ID );
  $img = get_the_post_thumbnail_url( $post_ID );
  foreach ($tags as $hashtags){
    $tag[] = array(
      "id" => $hashtags->term_id,
      "name" => $hashtags->name
    );
  };
  $res = array(
    "status" => 200,
    "data" => array (
      "id" => $post_ID,
      "date" => $result[0]["post_date"],
      "content" => $result[0]["post_content"],
      "title" => $result[0]["post_title"],
      "name" => $result[0]["post_name"],
      "excerpt" => $result[0]["post_excerpt"],
      "img" => $img,
      "hashtags" => $tag
    )
  );
  return $res;
}

// Para as funções que com instância PDO
// mudar $pdo = new ...
// para $pdo = getPDOInstance()
//
// function getPDOInstance () return new PDO ...
//
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
  foreach ($result as $response){
    $tags = get_the_tags( $post_ID );
    foreach ($tags as $hashtags){
      $tag[] = array(
        "id" => $hashtags->term_id,
        "name" => $hashtags->name
      );
    };
    $res[] = array(
      "status" => 200,
      "pageSize"=> 10,
      "page"=> 1,
      "data" => array (
      "id" => $response["comment_ID"],
      "date" => $response["comment_date"],
      "content" => $response["comment_content"],
      "name" => $response["comment_author"],
      "hashtags" => $tag
      )
    );
  }
  return $res;
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
  foreach ($result as $response){
    $tags = get_the_tags( $comment_ID );
    foreach ($tags as $hashtags){
      $tag[] = array(
        "id" => $hashtags->term_id,
        "name" => $hashtags->name
      );
    };
    $res[] = array(
      "status" => 200,
      "pageSize"=> 10,
      "page"=> 1,
      "data" => array (
      "id" => $response["comment_ID"],
      "date" => $response["comment_date"],
      "content" => $response["comment_content"],
      "name" => $response["comment_author"],
      "hashtags" => $tag
      )
    );
  }
  return $res;
}

function noIdQuery( $data ) {
  $args = array( 'category_name' => 'dicas' );
  $myposts = get_posts( $args );
  foreach($myposts as $posts){
    $post_ID = $posts->ID;
    $img = get_the_post_thumbnail_url( $post_ID );
    $tags = get_the_tags($post_ID);
    $posts->tags = $tags;
    $posts->img = $img;
    $result[] = $posts;
  };
  foreach ($result as $response){
    $nHashtag = $response->tags;
    foreach ($nHashtag as $hashtags){
      $tag[] = array(
        "id" => $hashtags->term_id,
        "name" => $hashtags->name
      );
    };
    $res[] = array(
      "status" => 200,
      "pageSize"=> 10,
      "page"=> 1,
      "data" => array (
      "id" => $response->ID,
      "date" => $response->post_date,
      "content" => $response->post_content,
      "title" => $response->post_title,
      "name" => $response->post_name,
      "excerpt" => $response->post_excerpt,
      "img" => $response->img,
      "hashtags" => $tag
      )
    );
  }
  return $res;
}

function noIdQueryC( $data ) {
  $args = array( 'category_name' => 'Questionario' );
  $myposts = get_posts( $args );
  foreach($myposts as $posts){
    $post_ID = $posts->ID;
    $tags = get_the_tags($post_ID);
    $img = get_the_post_thumbnail_url( $post_ID );
    $posts->img = $img;
    $posts->tags = $tags;
    $result[] = $posts;
  };
  foreach ($result as $response){
    $nHashtag = $response->tags;
    foreach ($nHashtag as $hashtags){
      $tag[] = array(
        "id" => $hashtags->term_id,
        "name" => $hashtags->name
      );
    };
  $res[] = array(
    "status" => 200,
    "data" => array (
    "id" => $response->ID,
    "title" => $response->post_title,
    "name" => $response->post_name,
    "img" => $response->img,
    "IsMulti" => 0,
    "hashtags" => $tag
    )
  );
}
  return $res;
}

function noIdQueryS( $data ) {
  $args = array( 'category_name' => 'Produto' );
  $myposts = get_posts( $args );
  foreach($myposts as $posts){
    $post_ID = $posts->ID;
    $tags = get_the_tags($post_ID);
    $posts->tags = $tags;
    $img = get_the_post_thumbnail_url( $post_ID );
    $posts->img = $img;
    $result[] = $posts;
  };
  foreach ($result as $response){
    $nHashtag = $response->tags;
    foreach ($nHashtag as $hashtags){
      $tag[] = array(
        "id" => $hashtags->term_id,
        "name" => $hashtags->name
      );
    };
    $res[] = array(
      "status" => 200,
      "pageSize"=> 10,
      "page"=> 1,
      "data" => array (
      "id" => $response->ID,
      "date" => $response->post_date,
      "content" => $response->post_content,
      "title" => $response->post_title,
      "name" => $response->post_name,
      "excerpt" => $response->post_excerpt,
      "img" => $response->img,
      "hashtags" => $tag
      )
    );
  }
  return $res;
}
