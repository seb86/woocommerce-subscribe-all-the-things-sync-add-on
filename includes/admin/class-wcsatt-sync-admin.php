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
		// Admin scripts and styles.
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_scripts' );

		// Adds to the default values for subscriptions schemes content.
		add_filter( 'wcsatt_default_subscription_scheme', __CLASS__ . '::subscription_schemes_content', 10, 1 );

		// Subscription scheme options displayed on the 'wcsatt_subscription_scheme_product_content' action.
		add_action( 'wcsatt_subscription_scheme_product_content', __CLASS__ . '::wcsatt_sync_fields', 15, 3 );

		// Filter the subscription scheme data to process the sign up and trial options on the ''wcsatt_subscription_scheme_process_scheme_data' filter.
		add_filter( 'wcsatt_subscription_scheme_process_scheme_data', __CLASS__ . '::wcsatt_sync_process_scheme_data', 10, 2 );
	}

	/**
	 * Load scripts and styles.
	 *
	 * @return void
	 */
	public static function admin_scripts() {
		global $post;

		// Get admin screen id.
		$screen      = get_current_screen();
		$screen_id   = $screen ? $screen->id : '';

		$add_scripts = false;
		$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( in_array( $screen_id, array( 'edit-product', 'product' ) ) ) {
			$add_scripts = true;
			$writepanel_dependencies = array( 'jquery', 'jquery-ui-datepicker', 'wc-admin-meta-boxes', 'wc-admin-product-meta-boxes' );
		}

		if ( $add_scripts ) {
			wp_register_script( 'wcsatt_sync_writepanel', WCSATT_SYNC()->plugin_url() . '/assets/js/wcsatt-sync-write-panels' . $suffix . '.js', $writepanel_dependencies, WCSATT_SYNC::VERSION );
		}

	} // END admin_scripts()

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
			// Set month as the default billing period.
			$subscription_period = isset( $scheme_data[ 'subscription_period' ] ) ? $scheme_data[ 'subscription_period' ] : 'month';

			// Determine whether to display the week/month sync fields or the annual sync fields.
			$display_week_month_select = ( ! in_array( $subscription_period, array( 'month', 'week' ) ) ) ? 'display: none;' : '';
			$display_annual_select     = ( 'year' != $subscription_period ) ? 'display: none;' : '';
			$payment_day               = isset( $scheme_data[ 'subscription_payment_sync_date' ] ) ? $scheme_data[ 'subscription_payment_sync_date' ] : 0;

			// Is the subscription period yearly?
			if ( 'year' == $subscription_period ) {
				$payment_month = isset( $scheme_data[ 'subscription_payment_sync_date_month' ] ) ? $scheme_data[ 'subscription_payment_sync_date_month' ] : date( 'm' );
				$payment_day   = isset( $scheme_data[ 'subscription_payment_sync_date_day' ] ) ? $scheme_data[ 'subscription_payment_sync_date_day' ] : 0;
			} else {
				$payment_month = date( 'm' );
			}

			echo '<div class="options_group subscription_scheme_product_data sync_scheme">';
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
					'day'   => isset( $posted_scheme[ 'subscription_payment_sync_date_day_variable' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_day_variable' ] : 0,
					'month' => isset( $posted_scheme[ 'subscription_payment_sync_date_month_variable' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_month_variable' ] : '01',
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
				'day'   => isset( $posted_scheme[ 'subscription_payment_sync_date_day' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_day' ] : 0,
				'month' => isset( $posted_scheme[ 'subscription_payment_sync_date_month' ] ) ? $posted_scheme[ 'subscription_payment_sync_date_month' ] : '01',
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
