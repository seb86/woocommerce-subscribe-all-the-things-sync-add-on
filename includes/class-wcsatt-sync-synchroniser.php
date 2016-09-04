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
		// Adds the SATT supported product types to allow them to be synced using the 'woocommerce_subscriptions_is_product_types_synced' filter.
		add_filter( 'woocommerce_subscriptions_is_product_types_synced', __CLASS__ . '::add_supported_product_types', 10, 1 );

		// Removes the products first payment date if the product type is not a simple or variable subscription.
		add_action( 'woocommerce_single_product_summary', __CLASS__ . '::remove_products_first_payment_date', 32 );
		} // END init()

	/**
	 * Adds the SATT supported product types to 'woocommerce_subscriptions_is_product_types_synced'
	 *
	 * @access public
	 * @param  array $product_types
	 * @return array
	 */
	public static function add_supported_product_types( $product_types ) {
		$satt_product_types = WCS_ATT()->get_supported_product_types();
		$product_types = array_merge( $satt_product_types, $product_types );

		return $product_types;
	} // END add_supported_product_types()

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
