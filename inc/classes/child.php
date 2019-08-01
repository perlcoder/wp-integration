<?php
namespace WP_Sync;
use WP_Query;

class Child {

	private $URL;
	private $posts;
	private $child_id;

	/**
	 * Getters
	 */
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	/**
	 * Setters
	 */
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
	}

	/**
	 * Constructor
	 *
	 * @param $url str. URL endpoint for getting posts
	 * @param $id int. Integer that declares the child id
	 * @return A new Child with all posts retrieved
	 *
	 */
	public function __construct ( $url ) {
		$this->URL = $url;
		$this->calculate_child_id();

		// Prepare to access remote child
		$args = array(
			'timeout'     => 10,
			'redirection' => 10,
			'httpversion' => '1.0',
			'user-agent'  => 'WordPress/; ' . home_url(),
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
			'body'        => null,
			'compress'    => false,
			'decompress'  => true,
			'sslverify'   => false,
			'stream'      => false,
			'filename'    => null
		); 

		// try to read and decode the remote json, rethrow any error
		$api_response = array();
		try { 
			$response = wp_remote_get( $url, $args );
			$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		} catch( Exception $e ) {
			throw $e;
		}

		// Save raw posts response
		$this->posts = $api_response;
	}

	/**
	 * Synch Child in master
	 *
	 * @return void
	 */

	public function sync() {
		
		// Search here if we have post from this child
		foreach ( $this->translate_ids() as $id ) {
			$wp_query = new WP_Query( array(
				'post_type'      	=> array('post'),
				'posts_per_page'	=> 1,
				'meta_query'	=> array(
					array(
						'key'	 	=> 'child_post_id',
						'value'	  	=> $id,
						'compare' 	=> 'LIKE',
					),
				)
			));

			// Post not here, we need to save it
			if( ! $wp_query->have_posts() ) {
				$this->save_post($id);
			}
			// TODO: Check for updates
			// else { }
			wp_reset_query();
		}
	}

	/**
	 * Calculate the id number given the url
	 *
	 * @return void
	 */
	private function calculate_child_id() {
		$hash = md5($this->URL, true);
		$num  = unpack('N2', $hash);
		$result = $num[1] & 0x000FFFFF;
		$this->child_id = $result;

		if ( WP_SYNC_DEBUG ) {
			print "Child Id is: " . $result . "\n";
		}
	}

	/**
	 * Save the post given its translated id
	 *
	 * @return void
	 *
	 */
	private function save_post($id) {
		$p = $this->get_post($id);

		if ( WP_SYNC_DEBUG ) {
			print "Saving the post with translated id: " . $id . "\n";
			print "Title is: " . $p['title']['rendered'] . "\n";
		}

		$new_post = array(
			'post_title' => wp_strip_all_tags($p['title']['rendered']),
			'post_status' => 'publish',
			'post_author' => $p['author'],
			'post_date'   => $p['date'],
			'post_date_gmt' => $p['date_gmt'],
		);

		// Save in wp
		$post_id = wp_insert_post($new_post);

		// now we create the metadata with tranlated post id
		update_post_meta( $post_id, 'child_post_id', $id );

		if ( WP_SYNC_DEBUG ) {
			print "Post was Saved with internal id as: " . $post_id . "\n";
		}
	}

	/**
	 * Get the post with translated id
	 * @return Array representing the Post
	 */

	private function get_post( $id ) {
		$posts = $this->posts;
		$r_id = $this->real_id($id);
		$key = array_search($r_id, array_column($posts, 'id'));
		return $posts[ $key ];
	}

	/**
	 * Determine the real id given the translated
	 *
	 * @return Int. The real id.
	 */
	private function real_id($id) {
		return str_replace($this->child_id, '', $id);
	}

	/**
	 * Translate post id, from child to master
	 *
	 * @return Array of Integers. The translated post id expected on this site
	 */
	public function translate_ids() {
		$f = function($val) { return $this->child_id . $val; };
		return array_map( $f,  $this->post_ids() );
	}

	/**
	 * Return list of posts ids
	 *
	 * @return Array of Integers.
	 */
	private function post_ids() {
		$f = function($val) { return $val['id']; };
		return array_map( $f, $this->posts );
	}
}
