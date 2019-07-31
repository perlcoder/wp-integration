<?php

require WP_SYNC_PATH . 'inc/classes/child.php';
use WP_Sync\Child;

/**
 *
 * Get all posts from a given child
 *
 * @param str $url child URL.
 * @return array $posts list of posts from child
 */
function get_all_posts( $url ) {
	$child = new Child($url);
	return $child->posts;
}

/**
 * Get all posts ids from a given child
 *
 *
 * @param str $url a child URL.
 * @return array $ids list of posts ids from child
 */

function get_posts_from_child( $url ) {
	$ids = array();
	$child = new Child($url);
	foreach ($child->posts as $post){
		array_push( $ids,  $post['id']);
	}
	return $ids;
}
