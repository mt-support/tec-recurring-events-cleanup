<?php
/**
 * Plugin Name: TEC Recurring Events Cleanup
 * Description: Cleans up recurring events data and deactivates itself after completion
 * Version: 1.0.0
 * Author: The Events Calendar
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Run the cleanup queries and deactivate plugin on success
 *
 * @return void
 */
function tec_recurring_events_cleanup(): void {
	global $wpdb;

	$prefix = $wpdb->prefix;
	$results = [];

	// Query 1: Delete recurrence queue meta
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$prefix}postmeta WHERE meta_key = %s ORDER BY meta_id ASC LIMIT 1000",
			'_TribeEventsPRO_RecurrenceQueue'
		)
	);
	$results['recurrence_queue'] = $wpdb->rows_affected;

	// Query 2: Delete post meta for recurring event instances
	$wpdb->query(
		"DELETE FROM {$prefix}postmeta
		WHERE post_id IN (
			SELECT ID FROM {$prefix}posts
			WHERE post_type = 'tribe_events'
			AND post_parent > 0 ORDER BY ID ASC LIMIT 1000 )"
	);
	$results['recurring_meta'] = $wpdb->rows_affected;

	// Query 3: Delete recurring event instance posts
	$wpdb->query(
		"DELETE FROM {$prefix}posts
		WHERE post_type = 'tribe_events'
		AND post_parent > 0 ORDER BY ID ASC LIMIT 1000"
	);
	$results['recurring_posts'] = $wpdb->rows_affected;

	// Log results
	do_action(
		'tribe_log',
		'info',
		'TEC Recurring Events Cleanup completed.', [
		'results' => $results
	] );

	$could_be_more_batches = array_filter( $results, static fn( $result ) => $result > 999 );

	if ( empty( $could_be_more_batches ) ) {
		// Deactivate the plugin
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}

add_action( 'shutdown', 'tec_recurring_events_cleanup' );
