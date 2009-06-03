<?php
/*
Plugin Name: First/last Links
Plugin URI: http://singpolyma.net/plugins/firstlast-links/
Description: Adds functions to get first and last post or paginated page
Version: 0.1
Author: Stephen Paul Weber
Author URI: http://singpolyma.net
License: MIT license (http://www.opensource.org/licenses/mit-license.php)
*/

function get_first_or_last_post($first_or_last) {
	global $wpdb, $wp_query;

	if($first_or_last == 'first') {
		$order = 'ASC';
	} else {
		$order = 'DESC';
	}

	$q = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_status='publish' ORDER BY post_date $order LIMIT 1");
	$q = $q[0];
	return array('post_title' => $q->post_title, 'permalink' => get_permalink($q));

}

function get_first_or_last_pageinate($first_or_last) {
	if($first_or_last == 'first') {
		return get_pagenum_link(1);
	} else {
		global $wp_query;
		return get_pagenum_link($wp_query->max_num_pages);
	}
}

function add_first_last_links() {
	global $paged;
	if($paged || is_archive() || is_category() || is_search()) {
		echo '<link rel="first" type="text/html" title="First archive page" href="'.htmlspecialchars(get_first_or_last_pageinate('first')).'" />'."\n";
		echo '<link rel="last" type="text/html" title="Last archive page" href="'.htmlspecialchars(get_first_or_last_pageinate('last')).'" />'."\n";
	} else if(is_single()) {
		$first = get_first_or_last_post('first');
		echo '<link rel="first" type="text/html" title="'.htmlspecialchars($first['post_title']).'" href="'.htmlspecialchars($first['permalink']).'" />'."\n";
		$last = get_first_or_last_post('last');
		echo '<link rel="last" type="text/html" title="'.htmlspecialchars($last['post_title']).'" href="'.htmlspecialchars($last['permalink']).'" />'."\n";
	}
}
add_action('wp_head', 'add_first_last_links');

?>
