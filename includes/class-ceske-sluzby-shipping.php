<?php
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Ceske_Sluzby_Shipping {

	/**
	 * Get the lowest shipping price for a product.
	 *
	 * @param WC_Product $product
	 * @param string $country
	 * @return float|null
	 */
	public static function get_lowest_shipping_price( WC_Product $product, string $country = 'CZ' ): ?float {
		if ( ! class_exists( 'WC_Cart' ) ) {
			return null;
		}

		// Ensure we have a session for shipping calculations
		if ( ! WC()->session ) {
			WC()->session = new WC_Session_Handler();
		}

		$cart = new WC_Cart();
		$cart->add_to_cart( $product->get_id(), 1 );

		$customer = WC()->customer;
		$original_shipping_country = $customer ? $customer->get_shipping_country() : '';
		if ( $customer ) {
			$customer->set_shipping_country( $country );
		}

		$shipping = WC()->shipping();
		$packages = $cart->get_shipping_packages();
		$shipping->calculate_shipping( $packages );

		$lowest_cost = null;

		foreach ( $shipping->get_packages() as $package ) {
			if ( isset( $package['rates'] ) ) {
				foreach ( $package['rates'] as $rate ) {
					$cost = (float) $rate->cost;
					if ( is_null( $lowest_cost ) || $cost < $lowest_cost ) {
						$lowest_cost = $cost;
					}
				}
			}
		}

		// Restore customer state
		if ( $customer ) {
			$customer->set_shipping_country( $original_shipping_country );
		}

		return $lowest_cost;
	}
}
