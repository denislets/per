<?php
// Curl Class for navigate to sites

class cUrlClass {
	private $browser = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36';
	private $cookies_path = '/';
	private $main_url = 'http://pernatsk.ru/';
	private $intf = '';
	protected $ch = null;
	protected $reqInfo = null;

	function __construct() {
		$this->ch = curl_init();
	}

	public function setCookiesPath($path) {
		$this->cookies_path = $path;   
	}

	public function setMainUrl($url) {
		$this->main_url = $url;
	}

	public function setInterface($intf) {
		$this->intf = $intf;
	}

	public function setBrowser($browser) {
		$this->browser = $browser;
	}

	private function checkSettings() {
		if (strlen($this->browser) == 0 or strlen($this->cookies_path) == 0) {
			echo "cUrl class settings error !";
			return;
		}
	}

	public function goToPage($url) {
		$this->checkSettings();

		if(BOT_DEBUG) {
			echo "GET: ".$url."\n";
			curl_setopt($this->ch,CURLINFO_HEADER_OUT,true);
			curl_setopt($this->ch,CURLOPT_VERBOSE,true);
			curl_setopt($this->ch,CURLOPT_HEADER,1);
		}
		
		curl_setopt($this->ch,CURLOPT_URL,$this->main_url.$url);
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($this->ch,CURLOPT_REFERER,$this->main_url);
		curl_setopt($this->ch,CURLOPT_USERAGENT,$this->browser);
		curl_setopt($this->ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл 
    	curl_setopt($this->ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');
		//curl_setopt($this->ch,CURLOPT_COOKIE,$this->parseCookiesFile());
		curl_setopt($this->ch,CURLOPT_CONNECTTIMEOUT, 15);
		if(strlen($this->intf) > 0) {
			curl_setopt($this->ch,CURLOPT_INTERFACE,$this->intf);
		}
		$tmpPage = curl_exec($this->ch);
		return $tmpPage;
	}

	public function sendPostData($url, $data)
	{
		$this->checkSettings();

		//$post_data = http_build_query($data);

		if(BOT_DEBUG) {
			echo "POST: ".$url."\n";
			curl_setopt($this->ch,CURLINFO_HEADER_OUT,true);
			curl_setopt($this->ch,CURLOPT_VERBOSE,true);
			curl_setopt($this->ch,CURLOPT_HEADER,1);
		}

		//curl_setopt($this->ch,CURL_HTTP_VERSION_1_1,true);
		curl_setopt($this->ch, CURLOPT_URL, $this->main_url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_REFERER, $this->main_url);
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->browser);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->ch, CURLOPT_POST, true);
		curl_setopt($this->ch, CURLOPT_HEADER, true);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл 
    	curl_setopt($this->ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');
		//curl_setopt($this->ch,CURLOPT_COOKIE,$this->parseCookiesFile());
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 15);
		if (strlen($this->intf) > 0) {
		   curl_setopt($this->ch, CURLOPT_INTERFACE, $this->intf);
		}
/*
		curl_setopt($this->ch,CURLOPT_HTTPHEADER,array(
			//"Content-Type" => "application/x-www-form-urlencoded",
		));
*/

		$tmpPage = curl_exec($this->ch);
		return $tmpPage;
	}

	public function getRequestInfo() {
		return curl_getinfo($this->ch);
	}

	public function close() {
		curl_close($this->ch);
	}

	public function parseCookiesFile() {
    	$cookies = array();
    
	    $lines = explode("\n", file_get_contents(realpath('.').$this->cookies_path));
 
	    // iterate over lines
	    foreach ($lines as $line) {
 
    	    // we only care for valid cookie def lines
 	       if (isset($line[0]) && substr_count($line, "\t") == 6) {
 
    	        // get tokens in an array
	            $tokens = explode("\t", $line);
 
	            // trim the tokens
            	$tokens = array_map('trim', $tokens);
    	        $cookie = array();
 
	            // Extract the data
            	$cookie['domain'] = $tokens[0];
        	    $cookie['flag'] = $tokens[1];
    	        $cookie['path'] = $tokens[2];
	            $cookie['secure'] = $tokens[3];
 
        	    // Convert date to a readable format
    	        $cookie['expiration'] = date('Y-m-d h:i:s', $tokens[4]);
 
	            $cookie['name'] = $tokens[5];
    	        $cookie['value'] = $tokens[6];
 
	            // Record the cookie.
	            $cookies[] = $cookie;
	        }
	    }

		$r = array();
		foreach ($cookies as $cookie) {
			$r[]="{$cookie['name']}={$cookie['value']}";
		}
		$r = implode('; ', $r);

	    return $r;
	}

}
