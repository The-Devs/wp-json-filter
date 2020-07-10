<?php


function mjsonv_get_id_from_slug ( $post_name ) {
    global $wpdb;
    $row = $wpdb->get_row( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = '{$post_name}'", "ARRAY_A" );
    return $row[ "ID" ];
}

/**
 * Build App using MJsonV\View
 */
function mjsonv_build_app ( $a ) {
    $url = ( empty( $a[ "url" ] ) ) ? MJSONV_DEFAULT_URL : $a[ "url" ];
    $attrsString = ( empty( $a[ "display-attributes" ] ) ) ? MJSONV_DEFAULT_ATTRIBUTES : $a[ "display-attributes" ];
    $attrs = explode( ",", $attrsString );
    $idAttr = ( empty( $a[ "id-attribute" ] ) ) ? MJSONV_DEFAULT_SINGLE_URL_ATTRIBUTE : $a[ "id-attribute" ];

    $mjv = new MJsonV\View ( $url, $attrs, $idAttr );
    $loadingImage = $mjv->getLoaderImage();
    $loadingContainer = $mjv->createElement(
        "div",
        [ "v-if" => "loading" ],
        $loadingImage
    );
    $tableContents = $mjv->getTableHeader() . $mjv->getTableBody();
    $table = $mjv->createElement(
        "table",
        [ "v-if" => "items.length > 0" ],
        $tableContents
    );
    $noResults = $mjv->createElement(
        "div",
        [ "v-if" => "items.length == 0" ],
        "Nenhum resultado encontrado."
    );
    $notLoadingContainer = $mjv->createElement(
        "div",
        [ "v-if" => "! loading" ],
        $noResults . $table
    );
    return $mjv->createElement(
        "section",
        [
            "id" => MJSONV_FRONT_ID,
            "mjsonv-url" => $url,
            "mjsonv-id" => $idAttr,
        ],
        $loadingContainer . $notLoadingContainer
    );
}

/**
 * Create the custom endpoint (default is '/mjsonv' )
 */
function mjsonv_plugin_activation () {
	if ( empty( mjsonv_get_id_from_slug( MJSONV_FRONT_SLUG ) ) ) {
        $appContent = '[mjsonv url="' . MJSONV_DEFAULT_URL . '" id-attribute="' . MJSONV_DEFAULT_SINGLE_URL_ATTRIBUTE . '" display-attributes="' .MJSONV_DEFAULT_ATTRIBUTES. '"]';
        $currentUser = 1; // get current user id
		$frontPage = array(
			"post_title"    => wp_strip_all_tags( MJSONV_FRONT_TITLE ),
			"post_name" => MJSONV_FRONT_SLUG,
			"post_content"  => $appContent,
			"post_status"   => "publish",
			"post_author"   => $currentUser,
			"post_type"     => "page",
		);
		wp_insert_post( $frontPage );
	}
}

/**
 * Remove the custom endpoint.
 */
function mjsonv_plugin_deactivation () {
	wp_delete_post( mjsonv_get_id_from_slug( MJSONV_FRONT_SLUG ), true );
}

/**
 * Wrap dependencies: Vuejs and Vue app.
 */
function mjsonv_init() {
    wp_enqueue_script( "vuejs-dev", "https://cdn.jsdelivr.net/npm/vue/dist/vue.js" );
    wp_enqueue_script( "axios", "https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" );
    wp_enqueue_script(
        "mjson-app-js",
        MJSONV_VIEWS_PATH . "app.js",
        [ "vuejs-dev" ],
        null,
        true
    );
}