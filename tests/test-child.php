<?php
/**
 * Class ChildTest
 *
 * @package Wp_Sync
 */

use WP_Sync\Child;

/**
 * Tests all post retrieval functions
 */
class ChildTest extends WP_UnitTestCase {

	/**
	 * Setup
	 */
	public function setUp() {
		parent::setUp();  
	}    

	/**
	 * TearDown
	 */
	public function tearDown() {
		parent::tearDown();  
	}    

	/*
	 * Test Child creation and initialization with posts
	 *
	 * @return void
	 */

	public function test_child_init() {
		// Instantiate a child
		$y = new Child('https://child1.yhello.co/wp-json/wp/v2/posts');
		// This child has 3 posts

		$this->assertTrue( count($y->posts) > 0 );
	}

	public function test_child_post_saved() {

		// Instantiate a child
		$y = new Child('https://child1.yhello.co/wp-json/wp/v2/posts');

		// Synchronize it with parent
		$y->sync();

		// Test if all post was saved
		foreach ( $y->translate_ids() as $id ) {
			// search for post with translated id
			print "Searching for translated id: " . $id . "\n";
			$args = array(
				'post_type'      	=> array('post'),
				'posts_per_page'	=> 1,
				'meta_query'	=> array(
					array(
						'key'	 	=> 'child_post_id',
						'value'	  	=> $id,
						'compare' 	=> 'LIKE',
					),
				)
			);
			$child_posts = new WP_Query($args);

			// check it was found.
			$this->assertTrue($child_posts->have_posts());
		}
	}
}
