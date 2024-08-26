<?php
/**
 * Main Class.
 *
 * @package cb-invoice
 */

use ChargeBee\ChargeBee\Environment;
use ChargeBee\ChargeBee\Models\Invoice;
use ChargeBee\ChargeBee\Models\Customer;

if ( ! class_exists( 'CB_Main' ) ) {

	/**
	 * Class CB_Main.
	 */
	class CB_Main {


		/**
		 * Register actions.
		 */
		public function __construct() {
			add_action( 'woocommerce_order_status_completed', array( $this, 'cb_create_invoice' ), 10, 1 );

		}


		/**
		 * Create inoice on chargebee.
		 *
		 * @param  int $order_id  from woocommerce.
		 *
		 * @access public
		 */
		public function cb_create_invoice( $order_id ) {

			$cb_invoice_id = get_post_meta( $order_id, 'cb_invoice_id', true );
			if ( ! empty( $cb_invoice_id ) ) {
				return;
			}
			$config = get_option( 'cb_invoice_config' );
			Environment::configure( $config['cb_api_key'], $config['cb_site'] );
			$invoice_post_data = $this->build_invoice( $order_id );

			if ( is_null( $invoice_post_data ) ) {
				return;
			}

			try {
				$result  = Invoice::create( $invoice_post_data );
				$invoice = $result->invoice();

				update_post_meta( $order_id, 'cb_invoice_id', $invoice->id );

			} catch ( Exception $e ) {
				return;
			}

		}


		/**
		 * Build input array for chargebee invoice.
		 *
		 * @param  int $order_id  from input.
		 *
		 * @return array
		 *
		 * @access private
		 */
		private function build_invoice( $order_id ) {
			$order       = wc_get_order( $order_id );
			$customer_id = $this->get_customer_id( $order );

			if ( is_null( $customer_id ) ) {
				return null;
			}
			$item_prices = array();

			$line_items = $order->get_items();

			foreach ( $line_items as $item_id => $item ) {
				$product_id   = $item->get_product_id();
				$product      = wc_get_product( $product_id );
				$item_price   = $product->get_price();
				$product_name = $item->get_name();
				$item_name    = str_replace( ' ', '_', $product_name );

				$item_prices[] = array(
					'itemPriceId' => strtolower( $item_name ),
					'unitPrice'   => $item_price,
				);

			}

			$invoice_post_data = array(
				'customerId'      => $customer_id,
				'shippingAddress' => array(
					'firstName' => $order->get_billing_first_name(),
					'lastName'  => $order->get_billing_last_name(),
					'city'      => $order->get_billing_city(),
					'state'     => $order->get_billing_state(),
					'zip'       => $order->get_billing_postcode(),
					'country'   => $order->get_billing_country(),
				),
				'itemPrices'      => array( $item_prices ),
			);

			return $invoice_post_data;
		}


		/**
		 * Get customer_id from chargebee.
		 *
		 * @param  object $order from input.
		 *
		 * @return string
		 *
		 * @access private
		 */
		private function get_customer_id( $order ) {

			$email      = $order->get_billing_email();
			$first_name = $order->get_billing_first_name();
			$last_name  = $order->get_billing_last_name();

			try {
				// Try to retrieve the customer by email.
				$result = Customer::list(
					array(
						'email[is]' => $email,
					)
				);

				// If the customer exists, return their ID.
				if ( ! empty( $result ) ) {
					$customer = $result[0]->customer();
					return $customer->id;
				}

				// If customer does not exist, create a new one.
				$result = Customer::create(
					array(
						'email'      => $email,
						'first_name' => $first_name,
						'last_name'  => $last_name,
					)
				);

				// Return the new customer ID.
				$customer = $result->customer();
				return $customer->id;

			} catch ( \Exception $e ) {

				return null;
			}
		}

	}

}

new CB_Main();
