<?php

class Spreedly {

	const ENDPOINT = 'https://spreedlycore.com/v1/';
	public $key = '';
	public $secret = '';

	public function Spreedly($key = '', $secret = '') {
		if (!empty($key))
			$this->key = $key;
		if (!empty($secret))
			$this->secret = $secret;
		if (empty($this->key) || empty($this->secret))
			throw new Exception("You must provide a Spreedly environment key and access secret to use this class");
	}

	public function getGateways($since = '') {

		$url = self::ENDPOINT . '/gateways.xml';
		if (!empty($since))
			$url .= '/?since_token=' . urlencode($since);

		$gateways = array();

		$xml = $this->get($url);
		$obj = new SimpleXMLElement($xml);
		foreach ($obj->gateway as $gateway) {
			$gateway->xml = $gateway->asXML();
			$gateways[] = $gateway;
		}

		return $gateways;

	}

	public function getPaymentMethods($since = '') {

		$url = self::ENDPOINT . '/payment_methods.xml';
		if (!empty($since))
			$url .= '/?since_token=' . urlencode($since);

		$payment_methods = array();

		$xml = $this->get($url);
		$obj = new SimpleXMLElement($xml);
		foreach ($obj->payment_method as $pm) {
			$pm->xml = $pm->asXML();
			$payment_methods[] = $pm;
		}

		return $payment_methods;

	}


	public function getTransactions($payment_method_token, $since = '') {

		$url = self::ENDPOINT . '/payment_methods/' . $payment_method_token . '/transactions.xml';
		if (!empty($since))
			$url .= '/?since_token=' . urlencode($since);

		$transactions = array();
		
		$xml = $this->get($url);
		$obj = new SimpleXMLElement($xml);
		foreach ($obj->transaction as $transaction) {
			$transaction->xml = $transaction->asXML();
			$transactions[] = $transaction;
		}

		return $transactions;

	}

	public function retainGateway($gateway_token) {

		$url = self::ENDPOINT . 'gateways/' . $gateway_token . '/retain.xml';

		$xml = $this->put($url);
		$response = new SimpleXMLElement($xml);

		if ($response && (string)$response->succeeded == 'true')
			return $xml;
		return false;		

	}	

	public function redactGateway($gateway_token) {

		$url = self::ENDPOINT . 'gateways/' . $gateway_token . '/redact.xml';

		$xml = $this->put($url);
		$response = new SimpleXMLElement($xml);

		if ($response && (string)$response->succeeded == 'true')
			return $xml;
		return false;		

	}

	public function redactPaymentMethod($payment_token) {

		$url = self::ENDPOINT . 'payment_methods/' . $payment_token . '/redact.xml';

		$xml = $this->put($url);
		$response = new SimpleXMLElement($xml);

		if ($response && (string)$response->succeeded == 'true')
			return $xml;
		return false;		

	}

	public function retainPaymentMethod($payment_token) {

		$url = self::ENDPOINT . 'payment_methods/' . $payment_token . '/retain.xml';

		$xml = $this->put($url);
		$response = new SimpleXMLElement($xml);

		if ($response && (string)$response->succeeded == 'true')
			return $xml;
		return false;

	}

	public static function authorize($payment_method_token, $gateway_token, $amount, $order_id = '', $description = '', $ip = '') {

		$url = self::ENDPOINT . 'gateways/' . $gateway_token . '/authorize.xml';

		$amount = round($amount * 100);

		$data = '<transaction>';
		$data .= '<payment_method_token>' . $payment_method_token . '</payment_method_token>';
		$data .= '<amount>' . $amount . '</amount>';
		$data .= '<currency_code>USD</currency_code>';
		$data .= '<order_id>' . $order_id . '</order_id>';
		$data .= '<description>' . $description . '</description>';
		$data .= '<ip>' . $ip . '</ip>';
		$data .= '</transaction>';

		$xml = $this->post($url, $data);
		$response = new SimpleXMLElement($xml);

		if ($response && (string)$response->succeeded == 'true')
			return $xml;
		return false;

	}	

	public function capture($transaction) {

		$url = self::ENDPOINT . 'transactions/' . $transaction . '/capture.xml';

		$xml = $this->post($url, $method, $data);
		$response = new SimpleXMLElement($xml);

		if ($response && (string)$response->succeeded == 'true')
			return $xml;
		return false;

	}

	public function void($transaction) {

		$url = self::ENDPOINT . 'transactions/' . $transaction . '/void.xml';

		$xml = $this->post($url, $method, $data);
		$response = new SimpleXMLElement($xml);

		if ($response && (string)$response->succeeded == 'true')
			return $xml;
		return false;

	}

	private function get($url) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->key . ':' . $this->secret);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$xml = curl_exec($ch);
		curl_close($ch);

		return $xml;

	}

	private function post($url, $data) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->key . ':' . $this->secret);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));			

		$xml = curl_exec($ch);
		curl_close($ch);

		return $xml;

	}

	private function put($url) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->key . ':' . $this->secret);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml', 'Content-Length: 0'));

		$xml = curl_exec($ch);
		curl_close($ch);

		return $xml;

	}

}
