<?php
/**
 * Filters the synchroniser to support SATT.
 *
 * @class WCSATT_SYNC_Synchroniser
 * @since 1.0.0
 */

class WCSATT_SYNC_Synchroniser {

	/**
	 * Initialize the synchroniser.
	 *
	 * @access public
	 * @static
	 */
	public static function init() {
		// Removes the products first payment date if the product type is not a simple or variable subscription.
		add_action( 'woocommerce_single_product_summary', __CLASS__ . '::remove_products_first_payment_date', 32 );
		} // END init()

	/**
	 * Removes the products first payment date from the product summary
	 * if the product type is not any of the standard subscription product types.
	 *
	 * @access public
	 * @global WC_Product $product
	 * @return void
	 */
	public static function remove_products_first_payment_date() {
		global $product;

		if (
			! $product->is_type( 'subscription' ) ||
			! $product->is_type( 'variable-subscription' ) ||
			! $product->is_type( 'subscription_variation' )
		) {
			remove_action( 'woocommerce_single_product_summary', 'WC_Subscriptions_Synchroniser::products_first_payment_date', 31 );
			remove_action( 'woocommerce_subscriptions_product_first_renewal_payment_time', 'WC_Subscriptions_Synchroniser::products_first_renewal_payment_time', 10, 4 );
		}
	} // END remove_products_first_payment_date()

} // END class

WCSATT_SYNC_Synchroniser::init();
