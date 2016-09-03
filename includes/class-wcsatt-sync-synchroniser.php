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
    add_filter( 'woocommerce_subscriptions_is_product_types_synced', __CLASS__ . '::add_supported_product_types', 10, 1 );

		// Overrides the subscriptions syncronized payment date if one exists on the 'woocommerce_subscriptions_get_products_payment_day' filter.
		add_filter( 'woocommerce_subscriptions_get_products_payment_day', __CLASS__ . '::set_sync_date', 10, 2 );

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
	 * Filters the syncronized payment date if syncing is enabled to
	 * get the day of the week, month or year on which the
	 * subscription's scheme payments should be synchronised to.
	 *
	 * @access public
	 * @param  int    $payment_date
	 * @param  object WC_Product $product
	 * @return int
	 */
	public static function set_sync_date( $payment_date, $product ) {
		if ( ! WC_Subscriptions_Synchroniser::is_syncing_enabled() ) {
			return $payment_date;
		}

		$product_id = $product->id;


		return 3;
	}

} // END class

WCSATT_SYNC_Synchroniser::init();
