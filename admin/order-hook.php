<?php
if (!defined('WPINC')) {
	die;
}

add_action('woocommerce_new_order', 'nobi_connect_woocommer_thankyou', 10, 2);
function nobi_connect_woocommer_thankyou($order_id, $order)
{

	if (!$order)
		return;

	$options = get_option('nobi_connect_options');

	if (!isset($options) || !isset($options['domain']) || !isset($options['apikey']))
		return;

	$homeUrl = get_option('home');

	$billing_email = trim($order->get_billing_email());
	$billing_phone = trim($order->get_billing_phone());
	$billing_first_name = $order->get_billing_first_name();
	$billing_last_name = $order->get_billing_last_name();
	$billing_address = $order->get_formatted_billing_address();
	$notes = $order->get_customer_note();
	$payment_method = $order->get_payment_method_title();
	// update address 
	$fullname = $billing_last_name . ' ' . $billing_first_name;
	$billing_address = str_replace($fullname . "<br/>", "", $billing_address);
	$billing_address = str_replace("<br/>", ", ", $billing_address);

	if (empty($billing_email)) {
		$billing_email = null;
	}

	$total = $order->get_total();
	;

	$products = array();

	foreach ($order->get_items() as $item_id => $item) {
		$products[] = $item->get_quantity() . ' ' . $item->get_name();
	}

	$lead = new NobitaLead(array(
		"firstName" => $billing_first_name,
		"lastName" => $billing_last_name,
		"address" => $billing_address,
		"phone" => $billing_phone,
		"email" => $billing_email,
		"link" => $homeUrl,
		"utm_source" => "website",
		"utm_campaign" => "woocommerce",
		"products" => join(", ", $products),
		"notes" => $notes,
		"total" => $total,
		"payment_method" => $payment_method,
		"_wc_order_id" => $order_id
	));

	//add wordpress custom filters for $lead

	$lead = apply_filters('woo_nobi_connect_payload', $lead);

	$callback = 'https://' . $options['domain'] . '/public-api/leads/createLead';

	wp_remote_post($callback, array(
		'headers' => array(
			'Content-Type' => 'application/json; charset=utf-8',
			'ApiKey' => $options['apikey']
		),
		'body' => $lead->to_json(),
		'method' => 'POST',
		'data_format' => 'body'
	));
}