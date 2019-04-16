<?php

class BoondManager {

	private $key;
	private $appToken;
	private $userToken;
	private $baseURL = 'https://ui.boondmanager.com/api';

	/**
	 * BoondManager constructor.
	 * @param $key
	 */
	public function __construct($key)
	{
		$this->key = $key;
	}

	/**
	 * @param string $token
	 */
	public function setAppToken($token)
	{
		$this->appToken = $token;
	}

	/**
	 * @param $url
	 */
	public function setBaseURL($url) {
		$this->baseURL = $url;
	}

	/**
	 * @param $token
	 */
	public function setUserToken($token) {
		$this->userToken = $token;
	}

	/**
	 * @param $signedRequest
	 * @return bool|mixed
	 */
	public function signedRequestDecode($signedRequest) {
		list($encodedSignature, $payload) = explode('.', $signedRequest, 2);

		$signedRequest = self::base64UrlDecode($encodedSignature) == hash_hmac('SHA256', $payload, $this->key)
			? json_decode(self::base64UrlDecode($payload), true)
			: false;

		if(!$signedRequest)
			return false;

		return $signedRequest;
	}

	/**
	 * @param $payload
	 * @return string
	 */
	public function jwtEncode($payload)
	{
		$header = ['typ' => 'JWT', 'alg' => 'HS256'];

		$segments = [];
		$segments[] = self::base64UrlEncode(json_encode($header));
		$segments[] = self::base64UrlEncode(json_encode($payload));
		$signing_input = implode('.', $segments);

		$signature = hash_hmac('SHA256', $signing_input, $this->key, true);
		$segments[] = self::base64UrlEncode($signature);

		return implode('.', $segments);
	}

	/**
	 * @param $api
	 * @return bool|mixed
	 */
	public function callApi($api) {
		$s = curl_init();

		$payload = [
			"userToken" => $this->userToken,
			"appToken" => $this->appToken,
			"time" => time(),
			"mode" => "normal"
		];

		curl_setopt($s,CURLOPT_URL, $this->baseURL . '/' . $api);
		curl_setopt($s,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($s,CURLOPT_HTTPHEADER, [
			'X-Jwt-App-Boondmanager:' . $this->jwtEncode($payload)
		]);

		$response = curl_exec($s);
		$status = curl_getinfo($s,CURLINFO_HTTP_CODE);

		curl_close($s);

		if($status === 200) {
			return json_decode($response);
		} else {
			return false;
		}
	}

	/**
	 * @param $input
	 * @return mixed
	 */
	public static function base64UrlEncode($input) {
		return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
	}

	/**
	 * @param $input
	 * @return bool|string
	 */
	public static function base64UrlDecode($input) {
		$remainder = strlen($input) % 4;
		if ($remainder) {
			$padlen = 4 - $remainder;
			$input .= str_repeat('=', $padlen);
		}

		return base64_decode(strtr($input, '-_', '+/'));
	}
}