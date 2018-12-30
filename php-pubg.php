<?php
class pubg {

	private $_key = null;
	private $_exceptions = true;
	private $_error = false;

	private $_playerArray = array(
		"season",
		"mode",
		"region"
	);

	function __construct($key = null){
		if(isset($key) && is_string($key) && !empty(trim($key))) {
			$this->_key = $key;
		}
	}

	function disableExceptions() {
		if($this->_exceptions == true) {
			$this->_exceptions = false;
		} else {
			$this->_exceptions = true;
		}
	}

	function nickname($player = null, $array = null) {
		if(is_string($player) || is_numeric($player)) {
			return self::_getPlayer($player, false, $array);
		} else {
			self::_exception("Invalid player argument");
		}
	}

	function steamid($player = null, $array = null) {
		if(is_numeric($player)) {
			return self::_getPlayer($player, true, $array);
		} else {
			self::_exception("Invalid player argument");
		}
	}

	function matches($id) {
		if(is_string($id)) {
			return self::_getMatches($id);
		} else {
			self::_exception("Invalid account id");
		}
	}

	private function _getMatches($id) {
		if(is_string($id)) {
			return self::_request("https://api.pubgtracker.com/v2/matches/pc/$id");
		}
		return false;
	}

	private function _getPlayer($player = null, $steamid = false, $array = null) {
		$url = "https://api.pubgtracker.com/v2/profile/pc/";
		if($steamid == true) {
			$url = "https://api.pubgtracker.com/v2/search/steam?steamId=";
		}
		$url = $url . $player;
		$arrayString = "";
		if(count($array) > 0) {
			$a = 0;
			if($steamid == true) {
				$a = 1;
			}
			foreach($array as $key => $value) {
				if(in_array($key, $this->_playerArray)) {
					$a++;
					$b = "&";
					if($a == 1) {
						$b = "?";
					}
					$b = $b . $key . "=" . $value;
					$arrayString = $arrayString . $b;
				}
			}
		}
		$url = $url . $arrayString;
		$request = self::_request($url);
		return $request;
	}

	private function _getKey() {
		if(isset($this->_key) && is_string($this->_key) && !empty(trim($this->_key))) {
			return $this->_key;
		} else {
			self::_exception("Invalid API key");
		}
		return false;
	}

	private function _request($url = null) {
		$object = new stdClass();
		if($key = self::_getKey()) {
			if(is_string($url) && !empty(trim($url))) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					"TRN-Api-Key: $key"
				));
				$output = curl_exec($ch);
				$info = curl_getinfo($ch);
				curl_close($ch);
				sleep(2);
				if($info["http_code"] == 200) {
					return json_decode($output);
				} else {
					$object->success = false;
					$object->code = $info["http_code"];
				}

			}
		} else {	
			self::_exception("Invalid API key");
		}
		return $object;
	}

	private function _exception($exception) {
		if($this->_exceptions == true && $this->_error == false) {
			throw new Exception($exception);
		}
	}
}