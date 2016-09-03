<?php
/**
 * Filters the product meta data in the admin for subscription schemes.
 *
 * @class WCSATT_SYNC_Admin
 * @since 1.0.0
 */

class WCSATT_SYNC_Admin extends WCS_ATT_Admin {

	/**
	 * Initialize the product meta.
	 *
	 * @access public
	 * @static
	 */
	public static function init() {
		// Adds to the default values for subscriptions schemes content.
		add_filter( 'wcsatt_default_subscription_scheme', __CLASS__ . '::subscription_schemes_content', 10, 1 );

		// Subscription scheme options displayed on the 'wcsatt_subscription_scheme_product_content' action.
		add_action( 'wcsatt_subscription_scheme_product_content', __CLASS__ . '::wcsatt_sync_fields', 15, 3 );

		// Filter the subscription scheme data to process the sign up and trial options on the ''wcsatt_subscription_scheme_process_scheme_data' filter.
		add_filter( 'wcsatt_subscription_scheme_process_scheme_data', __CLASS__ . '::wcsatt_sync_process_scheme_data', 10, 2 );
	}

	/**
	 * Adds the default values for subscriptions schemes content.
	 *
	 * @access public
	 * @static
	 * @param  array $defaults
	 * @return void
	 */
	public static function subscription_schemes_content( $defaults ) {
		$new_defaults = array(
			'subscription_payment_sync_date' => 0,
		);

		return array_merge( $new_defaults, $defaults );
	} // END subscription_schemes_content()

	/**
	 * Adds the syncronize date fields under the subscription section.
	 *
	 * @access public
	 * @static
	 * @param  int   $index
	 * @param  array $scheme_data
	 * @param  int   $post_id
	 * @global $wp_locale
	 * @return void
	 */
	public static function wcsatt_sync_fields( $index, $scheme_data, $post_id ) {
		global $wp_locale;

		if ( WC_Subscriptions_Synchroniser::is_syncing_enabled() ) {
			// Set month as the default billing period
			$subscription_period = isset( $scheme_data[ 'subscription_period' ] ) ? $scheme_data[ 'subscription_period' ] : 'month';

			// Determine whether to display the week/month sync fields or the annual sync fields
			$display_week_month_select = ( ! in_array( $subscription_period, array( 'month', 'week' ) ) ) ? 'display: none;' : '';
			$display_annual_select     = ( 'year' != $subscription_period ) ? 'display: none;' : '';
			$payment_day               = isset( $scheme_data[ 'subscription_payment_sync_date' ] ) ? $scheme_data[ 'subscription_payment_sync_date' ] : 0;

			// An annual sync date is already set in the form: array( 'day' => 'nn', 'month' => 'nn' ), create a MySQL string from those values (year and time are irrelvent as they are ignored)
			if ( is_array( $payment_day ) ) {
				$payment_month = $payment_day['month'];
				$payment_day   = $payment_day['day'];
			} else {
				$payment_month = date( 'm' );
			}

			echo '<div class="options_group subscription_scheme_sync">';
			echo '<div class="subscription_sync" style="' . esc_attr( $display_week_month_select ) . '">';

			woocommerce_wp_select( array(
				'id'          => '_subscription_payment_sync_date',
				'class'       => 'wc_input_subscription_payment_sync',
				'label'       => WC_Subscriptions_Synchroniser::$sync_field_label . ':',
				'options'     => WC_Subscriptions_Synchroniser::get_billing_period_ranges( $subscription_period ),
				'description' => WC_Subscriptions_Synchroniser::$sync_description,
				'desc_tip'    => true,
				'name'        => 'wcsatt_schemes[' . $index . '][subscription_payment_sync_date]',
				'value'       => $payment_day, // Explicity set value in to ensure backward compatibility
			) );

			echo '</div>';

			echo '<div class="subscription_sync_annual" style="' . esc_attr( $display_annual_select ) . '">';

			woocommerce_wp_text_input( array(
				'id'          => '_subscription_payment_sync_date_day',
				'class'       => 'wc_input_subscription_payment_sync',
				'label'       => WC_Subscriptions_Synchroniser::$sync_field_label . ':',
				'placeholder' => _x( 'Day', 'input field placeholder for day field for annual subscriptions', 'woocommerce-subscriptions' ),
				'name'        => 'wcsatt_schemes[' . $index . '][subscription_payment_sync_date_day]',
				'value'       => $payment_day,
				'type'        => 'number',
			) );

			woocommerce_wp_select( array(
				'id'          => '_subscription_payment_sync_date_month',
				'class'       => 'wc_input_subscription_payment_sync',
				'label'       => '',
				'options'     => $wp_locale->month,
				'description' => WC_Subscriptions_Synchroniser::$sync_description_year,
				'desc_tip'    => true,
				'name'        => 'wcsatt_schemes[' . $index . '][subscription_payment_sync_date_month]',
				'value'       => $payment_month, // Explicity set value in to ensure backward compatibility
			) );

			echo '</div>';
			echo '</div>';
		}

	} // END wcsatt_sync_fields()

	/**
	 * Filters the subscription scheme data to pass the
	 * syncronized payment date options when saving.
	 *
	 * @access public
	 * @static
	 * @param  ini    $posted_scheme
	 * @param  string $product_type
	 * @return void
	 */
	public static function wcsatt_sync_process_scheme_data( $posted_scheme, $product_type ) {
		// Copy variable type fields.
		if ( 'variable' == $product_type ) {

			if ( 'year' == $posted_scheme['subscription_period_variable'] ) { // save the day & month for the date rather than just the day

				$posted_scheme[ 'subscription_payment_sync_date_variable' ] = array(
					'day'    => isset( $posted_scheme[ 'subscription_payment_sync_date_day_variable' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_day_variable' ] : 0,
					'month'  => isset( $posted_scheme[ 'subscription_payment_sync_date_month_variable' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_month_variable' ] : '01',
				);

				$posted_scheme[ 'subscription_payment_sync_date_day' ]   = $posted_scheme[ 'subscription_payment_sync_date_day_variable' ];
				$posted_scheme[ 'subscription_payment_sync_date_month' ] = $posted_scheme[ 'subscription_payment_sync_date_month_variable' ];

			} else {

				if ( ! isset( $posted_scheme[ 'subscription_payment_sync_date_variable' ] ) ) {
					$posted_scheme[ 'subscription_payment_sync_date_variable' ] = 0;
				}

			}

			$posted_scheme[ 'subscription_payment_sync_date' ] = $posted_scheme[ 'subscription_payment_sync_date_variable' ];
		}

		if ( 'year' == $posted_scheme['subscription_period'] ) { // save the day & month for the date rather than just the day

			$posted_scheme[ 'subscription_payment_sync_date' ] = array(
				'day'    => isset( $posted_scheme[ 'subscription_payment_sync_date_day' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_day' ] : 0,
				'month'  => isset( $posted_scheme[ 'subscription_payment_sync_date_month' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_month' ] : '01',
			);

		} else {

			if ( ! isset( $posted_scheme[ 'subscription_payment_sync_date' ] ) ) {
				$posted_scheme[ 'subscription_payment_sync_date' ] = 0;
			}

		}

		return $posted_scheme;
	} // END wcsatt_sync_process_scheme_data()

}

WCSATT_SYNC_Admin::init();
