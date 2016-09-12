<?php
/**
 * Cart functionality for synchronizing subscriptions in the cart items.
 *
 * @class   WCSATT_SYNC_Cart
 * @version 1.0.0
 */

class WCSATT_SYNC_Cart extends WCS_ATT_Cart {

	/**
	 * Initialize the cart.
	 *
	 * @access public
	 * @static
	 */
	public static function init() {
		// Clean display formatted totals
		add_filter( 'woocommerce_cart_product_subtotal', __CLASS__ . '::clean_formatted_product_subtotal', 11, 4 );

		// Adds to the subscription if synchronizing is enabled and is set to a certain day, week, month or year on the 'wcsatt_cart_item' filter.
		add_filter( 'wcsatt_cart_item', __CLASS__ . '::update_cart_item_sub_data', 10, 1 );
	}

	/**
	 * Stops the formatted product subtotal string from repeating again.
	 *
	 * @access public
	 * @static
	 * @param   string $product_subtotal
	 * @param   object $product
	 * @param   int $quantity
	 * @param   $cart
	 * @returns string $product_total
	 */
	public static function clean_formatted_product_subtotal( $product_subtotal, $product, $quantity, $cart ) {

		if ( WC_Subscriptions_Product::is_subscription( $product ) && ! wcs_cart_contains_renewal() ) {

			$billing_interval = WC_Subscriptions_Product::get_interval( $product );
			$billing_period   = WC_Subscriptions_Product::get_period( $product );

			if ( WC_Subscriptions_Synchroniser::is_product_synced( $product ) && in_array( $billing_period, array( 'week', 'month', 'year' ) ) ) {

				$payment_day = WC_Subscriptions_Synchroniser::get_products_payment_day( $product );

				switch ( $billing_period ) {
					case 'week':
						$payment_day_of_week = WC_Subscriptions_Synchroniser::get_weekday( $payment_day );

						if ( 1 == $billing_interval ) {
							// translators: 1$: day of the week (e.g. "every Wednesday")
							$string = sprintf( __( 'every %s', 'wc-satt-sync' ), $payment_day_of_week );
						} else {
							// translators: 1$: period, 2$: day of the week (e.g. "every 2nd week on Wednesday")
							$string = sprintf( __( 'every %1$s on %2$s', 'wc-satt-sync' ), wcs_get_subscription_period_strings( $billing_interval, $billing_period ), $payment_day_of_week );
						}
						break;

					case 'month':
						if ( 1 == $billing_interval ) {
							if ( $payment_day > 27 ) {
								$string = __( 'on the last day of each month', 'wc-satt-sync' );
							} else {
								// translators: 1$: day of the month (e.g. "23rd") (e.g. "every 23rd of each month")
								$string = sprintf( __( 'on the %s of each month', 'wc-satt-sync' ), WC_Subscriptions::append_numeral_suffix( $payment_day ) );
							}
						} else {
							if ( $payment_day > 27 ) {
								// translators: 1$: interval (e.g. "3rd") (e.g. "on the last day of every 3rd month")
								$string = sprintf( __( 'on the last day of every %s month', 'wc-satt-sync' ), WC_Subscriptions::append_numeral_suffix( $billing_interval ) );
							} else {
								// translators: 1$: <date> day of every, 2$: <interval> month (e.g. "on the 23rd day of every 2nd month")
								$string = sprintf( __( 'on the %1$s day of every %2$s month', 'wc-satt-sync' ), WC_Subscriptions::append_numeral_suffix( $payment_day ), WC_Subscriptions::append_numeral_suffix( $billing_interval ) );
							}
						}
						break;

					case 'year':
						if ( 1 == $billing_interval ) {
							// translators: 1$: <date>, 2$: <month> each year (e.g. "on March 15th each year")
							$string = sprintf( __( 'on %1$s %2$s each year', 'wc-satt-sync' ), $wp_locale->month[ $payment_day['month'] ], WC_Subscriptions::append_numeral_suffix( $payment_day['day'] ) );
						} else {
							// translators: 1$: month (e.g. "March"), 2$: day of the month (e.g. "23rd") (e.g. "on March 15th every 3rd year")
							$string = sprintf( __( 'on %1$s %2$s every %3$s year', 'wc-satt-sync' ), $wp_locale->month[ $payment_day['month'] ], WC_Subscriptions::append_numeral_suffix( $payment_day['day'] ), WC_Subscriptions::append_numeral_suffix( $billing_interval ) );
						}
						break;
				}

				$product_subtotal = str_replace( $string, '', $product_subtotal );
			}

		}

		return $product_subtotal;
	} // END clean_formatted_product_subtotal()

	/**
	 * Adds to the cart item data for a subscription product that
	 * is syncronized to a certain day, week, month or year.
	 *
	 * @access public
	 * @static
	 * @param  array $cart_item
	 * @return array
	 */
	public static function update_cart_item_sub_data( $cart_item ) {
		$active_scheme = WCS_ATT_Schemes::get_active_subscription_scheme( $cart_item );

		if ( $active_scheme && $cart_item['data']->is_converted_to_sub == 'yes' ) {

			$payment_day = isset( $active_scheme['subscription_payment_sync_date'] ) ? $active_scheme['subscription_payment_sync_date'] : 0;

			// Is the subscription period set yearly?
			if ( 'year' == $active_scheme['subscription_period'] ) {
				$cart_item['data']->subscription_payment_sync_date     = $payment_day;
				$cart_item['data']->subscription_payment_sync_date_day = $payment_day;

				$payment_month = isset( $active_scheme['subscription_payment_sync_date_month'] ) ? $active_scheme['subscription_payment_sync_date_month'] : date( 'm' );

				$cart_item['data']->subscription_payment_sync_date_month = $payment_month;
			} else {
				$cart_item['data']->subscription_payment_sync_date = $payment_day;
			}

		}

		return $cart_item;
	} // END update_cart_item_sub_data()

}

WCSATT_SYNC_Cart::init();
