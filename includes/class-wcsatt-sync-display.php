<?php
/**
 * Templating and styling functions.
 *
 * @class WCSATT_SYNC_Display
 * @since 1.0.0
 */

class WCSATT_SYNC_Display extends WCS_ATT_Display {

	/**
	 * Initialize the display.
	 *
	 * @access public
	 * @static
	 */
	public static function init() {
		// Adds the synchronised payment date data to the price html on the 'wcsatt_overridden_subscription_prices_product' filter.
		add_filter( 'wcsatt_overridden_subscription_prices_product', __CLASS__ . '::add_sub_scheme_data_price_html', 10, 3 );

		// Adds the extra subscription scheme data to the product object on the 'wcsatt_converted_product_for_scheme_option' filter.
		add_filter( 'wcsatt_converted_product_for_scheme_option', __CLASS__ . '::sub_product_scheme_option', 10, 2 );

		// Filters the price string to include the synchronised payment date to pass per scheme option on the 'wcsatt_single_product_subscription_scheme_price_html' filter.
		add_filter( 'wcsatt_single_product_subscription_scheme_price_html', __CLASS__ . '::get_price_string', 10, 2 );

		// Filters the lowest price subscription scheme data on the 'wcsatt_get_lowest_price_sub_scheme_data' filter.
		add_filter( 'wcsatt_get_lowest_price_sub_scheme_data', __CLASS__ . '::get_lowest_price_sub_scheme_data', 10, 2 );

		// Adds the synchronised payment date data to the subscription scheme prices on the 'wcsatt_subscription_scheme_prices' filter.
		add_filter( 'wcsatt_subscription_scheme_prices', __CLASS__ . '::add_subscription_scheme_prices', 10, 2 );
	}

	/**
	 * Adds the additional subscription scheme data for products with attached subscription schemes.
	 *
	 * @access public
	 * @static
	 * @param  object     $_product
	 * @param  array      $subscription_scheme
	 * @param  WC_Product $product
	 * @return string
	 */
	public static function add_sub_scheme_data_price_html( $_product, $subscription_scheme, $product ) {
		if ( 'year' == $subscription_scheme[ 'subscription_period' ] ) {
			$_product->subscription_payment_sync_date = array(
				'day'   => $subscription_scheme[ 'subscription_payment_sync_date_day' ],
				'month' => $subscription_scheme[ 'subscription_payment_sync_date_month' ]
			);
		} else {
			$_product->subscription_payment_sync_date = $subscription_scheme[ 'subscription_payment_sync_date' ];
		}

		return $_product;
	} // END add_sub_scheme_data_price_html()

	/**
	 * Adds the extra subscription scheme data to the product object.
	 *
	 * @access public
	 * @static
	 * @param  object $_cloned
	 * @param  array  $subscription_scheme
	 * @return object
	 */
	public static function sub_product_scheme_option( $_cloned, $subscription_scheme ) {
		if ( 'year' == $subscription_scheme[ 'subscription_period' ] ) {
			$_cloned->subscription_payment_sync_date = array(
				'day'   => $subscription_scheme[ 'subscription_payment_sync_date_day' ],
				'month' => $subscription_scheme[ 'subscription_payment_sync_date_month' ]
			);
		} else {
			$_cloned->subscription_payment_sync_date = $subscription_scheme[ 'subscription_payment_sync_date' ];
		}

		return $_cloned;
	} // END sub_product_scheme_option()

	/**
	 * Filters the price string to include the syncronize
	 * payment date to pass per subscription scheme option.
	 *
	 * @access public
	 * @static
	 * @param  array $prices
	 * @param  array $subscription_scheme
	 * @return array
	 */
	public static function get_price_string( $prices, $subscription_scheme ) {
		if ( 'year' == $subscription_scheme[ 'subscription_period' ] ) {
			$prices[ 'subscription_payment_sync_date' ] = array(
				'day'   => $subscription_scheme[ 'subscription_payment_sync_date_day' ],
				'month' => $subscription_scheme[ 'subscription_payment_sync_date_month' ]
			);
		} else {
			$prices[ 'subscription_payment_sync_date' ] = $subscription_scheme[ 'subscription_payment_sync_date' ];
		}

		return $prices;
	} // END get_price_string()

	/**
	 * Adds the synchronised payment date to the lowest subscription scheme option.
	 *
	 * @access public
	 * @static
	 * @param array $data
	 * @param array $lowest_scheme
	 * @return array
	 */
	public static function get_lowest_price_sub_scheme_data( $data, $lowest_scheme ) {
		if ( 'year' == $lowest_scheme[ 'subscription_period' ] ) {
			$data[ 'subscription_payment_sync_date' ] = array(
				'day'   => $lowest_scheme[ 'subscription_payment_sync_date_day' ],
				'month' => $lowest_scheme[ 'subscription_payment_sync_date_month' ]
			);
		} else {
			$data[ 'subscription_payment_sync_date' ] = $lowest_scheme[ 'subscription_payment_sync_date' ];
		}

		return $data;
	} // END get_lowest_price_sub_scheme_data()

	/**
	 * Adds the synchronised payment date data to the subscription scheme prices.
	 *
	 * @access public
	 * @static
	 * @param  array $prices
	 * @param  array $subscription_scheme
	 * @return array
	 */
	public static function add_subscription_scheme_prices( $prices, $subscription_scheme ) {
		if ( 'year' == $subscription_scheme[ 'subscription_period' ] ) {
			$prices[ 'subscription_payment_sync_date' ] = array(
				'day'   => $subscription_scheme[ 'subscription_payment_sync_date_day' ],
				'month' => $subscription_scheme[ 'subscription_payment_sync_date_month' ]
			);
		} else {
			$prices[ 'subscription_payment_sync_date' ] = $subscription_scheme[ 'subscription_payment_sync_date' ];
		}

		return $prices;
	} // END add_subscription_scheme_prices()

}

WCSATT_SYNC_Display::init();
