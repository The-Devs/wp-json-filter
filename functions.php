<?php


function mjsonv_get_id_from_slug ( $post_name ) {
    global $wpdb;
    $row = $wpdb->get_row( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = '{$post_name}'", "ARRAY_A" );
    return $row[ "ID" ];
}
