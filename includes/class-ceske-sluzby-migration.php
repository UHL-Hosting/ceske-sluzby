<?php
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Ceske_Sluzby_Migration {

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'check_migration' ) );
	}

	public static function check_migration() {
		$current_version = get_option( 'ceske_sluzby_version' );
		if ( version_compare( $current_version, '1.1.0', '<' ) ) {
			self::migrate_shipping_settings();
			update_option( 'ceske_sluzby_version', '1.1.0' );
		}
	}

	private static function migrate_shipping_settings() {
		$ulozenka_settings = get_option( 'woocommerce_ceske_sluzby_ulozenka_settings' );
		if ( $ulozenka_settings ) {
			// Find or create a zone with Ulozenka and migrate settings to first instance
			$zones = WC_Shipping_Zones::get_zones();
			$migrated = false;
			foreach ( $zones as $zone_data ) {
				$zone = new WC_Shipping_Zone( $zone_data['zone_id'] );
				$methods = $zone->get_shipping_methods();
				foreach ( $methods as $instance_id => $method ) {
					if ( $method->id === 'ceske_sluzby_ulozenka' ) {
						update_option( 'woocommerce_ceske_sluzby_ulozenka_' . $instance_id . '_settings', $ulozenka_settings );
						$migrated = true;
						break 2;
					}
				}
			}
		}

		$dpd_settings = get_option( 'woocommerce_ceske_sluzby_dpd_parcelshop_settings' );
		if ( $dpd_settings ) {
			$zones = WC_Shipping_Zones::get_zones();
			foreach ( $zones as $zone_data ) {
				$zone = new WC_Shipping_Zone( $zone_data['zone_id'] );
				$methods = $zone->get_shipping_methods();
				foreach ( $methods as $instance_id => $method ) {
					if ( $method->id === 'ceske_sluzby_dpd_parcelshop' ) {
						update_option( 'woocommerce_ceske_sluzby_dpd_parcelshop_' . $instance_id . '_settings', $dpd_settings );
						break 2;
					}
				}
			}
		}
	}
}
