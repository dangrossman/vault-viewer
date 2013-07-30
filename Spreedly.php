<?php

class Spreedly {

	const ENDPOINT = 'https://spreedlycore.com/v1/';

	public function Spreedly($login, $secret) {
		$this->login = $login;
		$this->secret = $secret;
	}

	public function getGateways($since = '') {

		$url = self::ENDPOINT . '/gateways.xml';
		if (!empty($since)) {
			$url .= '/?since_token=' . urlencode($since);
		}

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
		if (!empty($since)) {
			$url .= '/?since_token=' . urlencode($since);
		}

		$payment_methods = array();

		$xml = $this->get($url);
		$obj = new SimpleXMLElement($xml);
		foreach ($obj->payment_method as $pm) {
			$pm->xml = $pm->asXML();
			$payment_methods[] = $pm;
		}

		return $payment_methods;

	}


	public function getTransactions($payment_method_token) {

		$url = self::ENDPOINT . '/payment_methods/' . $payment_method_token . '/transactions.xml';
		
		$xml = $this->get($url);
		$obj = new SimpleXMLElement($xml);
		while (count($obj->transaction) > 0) {
			foreach ($obj->transaction as $transaction) {
				$transaction->xml = $transaction->asXML();
				$transactions[] = $transaction;
			}
			$xml = $this->get($url . '/?since_token=' . urlencode($transactions[count($transactions)-1]->token));
			$obj = new SimpleXMLElement($xml);
		}

		return $transactions;

	}

	public static function retain($payment_token) {

		$url = self::ENDPOINT . 'payment_methods/' . $payment_token . '/retain.xml';

		$response = self::put($url);

		if ($response && (string)$response->succeeded == 'true')
			return $response;
		return false;

	}

	public static function redact($payment_token) {

		$url = self::ENDPOINT . 'payment_methods/' . $payment_token . '/redact.xml';

		$response = self::put($url);

		if ($response && (string)$response->succeeded == 'true')
			return $response;
		return false;		

	}

	public static function capture($transaction) {

		$url = self::ENDPOINT . 'transactions/' . $transaction . '/capture.xml';

		$response = self::post($url, $method, $data);

		if ($response && (string)$response->succeeded == 'true')
			return $response;
		return false;

	}

	public static function void($transaction) {

		$url = self::ENDPOINT . 'transactions/' . $transaction . '/void.xml';

		$response = self::post($url, $method, $data);

		if ($response && (string)$response->succeeded == 'true')
			return $response;
		return false;

	}

	private function get($url) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->secret);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$xml = curl_exec($ch);
		curl_close($ch);

		return $xml;

	}

	private function post($url, $data) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->secret);
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
		curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->secret);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml', 'Content-Length: 0'));

		$xml = curl_exec($ch);
		curl_close($ch);

		return $xml;

	}

}
