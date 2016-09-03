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
		// Adds to the subscription if synchronizing is enabled and is set to a certain day, week or month on the 'wcsatt_cart_item' filter.
		add_filter( 'wcsatt_cart_item', __CLASS__ . '::update_cart_item_sub_data', 10, 1 );
	}

	/**
	 * Adds to the cart item data for a subscription product that
	 * is syncronized to a certain day, week or month.
	 *
	 * @access public
	 * @static
	 * @param  array $cart_item
	 * @return array
	 */
	public static function update_cart_item_sub_data( $cart_item ) {
		$active_scheme = WCS_ATT_Schemes::get_active_subscription_scheme( $cart_item );

		if ( $active_scheme && $cart_item['data']->is_converted_to_sub == 'yes' ) {

			// Checks if the cart item is a supported bundle type child.
			$container_key = WCS_ATT_Integrations::has_bundle_type_container( $cart_item );

			$payment_day = isset( $active_scheme['subscription_payment_sync_date'] ) ? $active_scheme['subscription_payment_sync_date'] : 0;

			// If the cart item is a child item then reset the sign-up fee.
			if ( false !== $container_key ) { $payment_day = 0; }

			// If the payment day is not an array and is more than zero then set the conditions for the cart.
			if ( ! is_array( $payment_day ) && $payment_day > 0 ) {
				$cart_item['data']->subscription_payment_sync_date = $payment_day;
			} else {
				$cart_item['data']->subscription_payment_sync_date       = $payment_day;
				$cart_item['data']->subscription_payment_sync_date_day   = $payment_day['day'];
				$cart_item['data']->subscription_payment_sync_date_month = $payment_day['month'];
			}

		}

		return $cart_item;
	} // END update_cart_item_sub_data()

}

WCSATT_SYNC_Cart::init();
