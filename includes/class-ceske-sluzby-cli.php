<?php
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * WP-CLI commands for Ceske Sluzby.
 */
class Ceske_Sluzby_CLI {

	/**
	 * Generates XML feed and saves it to a file.
	 *
	 * ## OPTIONS
	 *
	 * <feed>
	 * : The type of feed to generate (heureka, zbozi, google, glami, heureka-availability).
	 *
	 * [--file=<file>]
	 * : The file path to save the XML feed. If not provided, it will be saved in the uploads directory.
	 *
	 * ## EXAMPLES
	 *
	 *     wp ceske-sluzby generate-feed google --file=/var/www/html/wp-content/uploads/google.xml
	 *
	 * @when after_wp_load
	 */
	public function generate_feed( $args, $assoc_args ) {
		$feed_type = $args[0];
		$file_path = $assoc_args['file'] ?? '';

		if ( ! in_array( $feed_type, array( 'heureka', 'zbozi', 'google', 'glami', 'heureka-availability' ) ) ) {
			WP_CLI::error( "Invalid feed type: $feed_type" );
		}

		if ( empty( $file_path ) ) {
			$upload_dir = wp_upload_dir();
			$file_path = $upload_dir['basedir'] . '/' . $feed_type . '.xml';
		}

		WP_CLI::log( "Generating $feed_type feed..." );

		// Capture XML output
		ob_start();
		switch ( $feed_type ) {
			case 'heureka':
				heureka_xml_feed_zobrazeni();
				break;
			case 'zbozi':
				zbozi_xml_feed_zobrazeni();
				break;
			case 'google':
				google_xml_feed_zobrazeni();
				break;
			case 'glami':
				glami_xml_feed_zobrazeni();
				break;
			case 'heureka-availability':
				heureka_availability_xml_feed_zobrazeni();
				break;
		}
		$xml_content = ob_get_clean();

		if ( empty( $xml_content ) ) {
			WP_CLI::error( "Failed to generate $feed_type feed content." );
		}

		if ( file_put_contents( $file_path, $xml_content ) ) {
			WP_CLI::success( "Feed saved to $file_path" );
		} else {
			WP_CLI::error( "Failed to save feed to $file_path" );
		}
	}
}
