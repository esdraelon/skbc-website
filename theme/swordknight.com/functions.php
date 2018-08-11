<?php

/* 
	Recaptcha:
	6LdENAkAAAAAADmbvZWn0onSYNOxP9EN-teqAPFF
	6LdENAkAAAAAAM9qvxslrWzFf94rIcgLLf_6OfVu
	
	require_once('theme/swordknight.com/lib.recaptcha.php');
	$publickey = "6LdENAkAAAAAADmbvZWn0onSYNOxP9EN-teqAPFF"; // you got this from the signup page
	
*/

include_once('startup.php');

function to_slug($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}

function array_pop_val(& $arr, $val) {
	unset($arr[$val]);
	return $arr;
}

function is_closure($t) {
	return is_object($t) && ($t instanceof Closure);
}
	
function valid_id($id) {
    return is_numeric($id) && $id > 0;
}

function trimlen($str) {
	return strlen(trim($str));
}

function pad_array($r, $count) {
	return array_merge(array_fill(0, $count, null), $r);
}

function strip_tags_r($val) {
    return is_array($val) ?
    	array_map('strip_tags_r', $val) :
		strip_tags($val);
}

function path_join() {
    $args = func_get_args();
    $paths = array();
    foreach ($args as $arg) {
        $paths = array_merge($paths, (array)$arg);
    }

    $paths = array_map(create_function('$p', 'return trim($p, "/");'), $paths);
    $paths = array_filter($paths);
    return join('/', $paths);
}

//http://www.codingforums.com/archive/index.php/t-180473.html
class shortScale {
	// Source: Wikipedia (http://en.wikipedia.org/wiki/Names_of_large_numbers)
	private static $scale = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion', 'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'noverndecillion', 'vigintillion');
	private static $digit = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
	private static $digith = array('', 'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelfth', 'thirteenth', 'fourteenth', 'fiftheenth', 'sixteenth', 'seventeenth', 'eighteenth', 'nineteenth');
	private static $ten = array('', '', 'twenty', 'thirty', 'fourty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');
	private static $tenth = array('', '', 'twentieth', 'thirtieth', 'fortieth', 'fiftieth', 'sixtieth', 'seventieth', 'eightieth', 'ninetieth');

	private static function floatToArray($number, &$int, &$frac) {
		// Forced $number as (string), effectively to avoid (float) inprecision
		@list(, $frac) = explode('.', $number);
		if ($frac || !is_numeric($number) || (strlen($number) > 60)) throw new Exception('Not a number or not a supported number type');
		// $int = explode(',', number_format(ltrim($number, '0'), 0, '', ',')); -- Buggy
		$int = str_split(str_pad($number, ceil(strlen($number)/3)*3, '0', STR_PAD_LEFT), 3);
	}

	/* in retrospect ... this function was pretty easy */
	public static function toDigith($number) {
		if ($number < 20) {
			return $number . substr(self::$digith[$number],-2);
		} else {
			self::floatToArray($number, $int, $frac);
			return $number . substr(self::$digith[substr($number,-1)],-2);
		}
	}
	
	private static function thousandToEnglish($number) {
		// Gets numbers from 0 to 999 and returns the cardinal English
		$hundreds = floor($number / 100);
		$tens = $number % 100;
		$pre = ($hundreds ? self::$digit[$hundreds].' hundred' : '');
		if ($tens < 20)
			$post = self::$digit[$tens];
		else
			$post = trim(self::$ten[floor($tens / 10)].' '.self::$digit[$tens % 10]);
		if ($pre && $post) return $pre.' and '.$post;
		return $pre.$post;
	}

	private static function cardinalToOrdinal($cardinal) {
		// Finds the last word in the cardinal arrays and replaces it with
		// the entry from the ordinal arrays, or appends "th"
		$words = explode(' ', $cardinal);
		$last = &$words[count($words)-1];
		if (in_array($last, self::$digit)) {
			$last = self::$digith[array_search($last, self::$digit)];
		} elseif (in_array($last, self::$ten)) {
			$last = self::$tenth[array_search($last, self::$ten)];
		} elseif (substr($last, -2) != 'th') {
			$last .= 'th';
		}
		return implode(' ', $words);
	}

	public static function toOrdinal($number) {
		// Converts a xth format number to English. e.g. 22nd to twenty-second.
		return trim(self::cardinalToOrdinal(self::toCardinal($number)));
	}

	public static function toCardinal($number) {
		// Converts a number to English. e.g. 22 to twenty-two.
		self::floatToArray($number, $int, $frac);
		$int = array_reverse($int);
		$english = array();
		for($i=count($int)-1; $i>-1; $i--) {
			$englishnumber = self::thousandToEnglish($int[$i]);
			if ($englishnumber) 
				$english[] = $englishnumber.' '.self::$scale[$i];
		}
		$post = array_pop($english);
		$pre = implode(', ', $english);
		if ($pre && $post) return trim($pre.' and '.$post);
		return trim($pre.$post);
	}
}

class JsonClient {
    
    var $__server_route;
    var $__parameters;
    var $cache;
    var $CACHE;
	var $client_id = 0;
    
    function __construct($server_route = null, $client_id = 0, $CACHE=false) {
        $this->__server_route = $server_route;
        $this->__parameters = array();
        $this->CACHE = $CACHE;
		if ($this->CACHE) {
			$this->cache = new Yapo(Lib::$Sys->Database, DBPRE . 'cache');
		}
    }
    
    public function __set($key, $value) {
        $this->__parameters[$key] = $value;
    }
    
    private function _request($request) {	
		$split = explode('&', $request);
		$cacheline = array();
		foreach ($split as $k => $pair) {
			$keys = explode('=', $pair);
			if (stripos($keys[0], 'token') === false) {
				$cacheline[] = $pair;
			}
		}
		asort($cacheline);
		$request = implode('&', $cacheline);
		return $request;
	}
	
    private function _is_cached($request) {
        if (!$this->CACHE) return false;
        $this->cache->clear();
        $this->cache->request = $this->_request($request);
        if ($this->cache->find() == 1) {
            if (strtotime($this->cache->last_pull) > (time() - 60 * 60 * 8)) {
                return json_decode($this->cache->response, true);
            }
        }
        return false;
    }
    
    private function _cache($request, $response) {
        if (!$this->CACHE) return false;
		
		$request = $this->_request($request);
		
        $this->cache->clear();
        $this->cache->request = $request;
        if ($this->cache->find() == 0) {
            $this->cache->clear();
            $this->cache->request = $request;
        }		
		$this->cache->client_id = $this->client_id;
        $this->cache->response = json_encode($response);
        $this->cache->last_pull = date('Y-m-d H:i:s');
        $cache_id = $this->cache->save();
    }
    
    private function _get_url( $endpoint, $parameters = array())
    {
        $fields = array();
        if (isset($this->Token))
            $parameters['Token'] = $token;
            
        if (is_array($this->__parameters)) foreach ($this->__parameters as $k => $v)
            $fields[urlencode(trim($k))] = urlencode(trim($v));
        if (is_array($parameters)) foreach ($parameters as $k => $v)
            $fields[urlencode(trim($k))] = urlencode(trim($v));
        
        unset($fields['PHPSESSID']);
        
        ksort($fields);
        
        $post = implode('&', array_map(function($k, $v) {
                return "$k=$v";
            }, array_keys($fields), $fields));
        
        $response = $this->_is_cached($post);
        if ($response) return $response;
        
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "mo.agent", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 3,      // timeout on connect
            CURLOPT_TIMEOUT        => 30,      // timeout on response
            CURLOPT_MAXREDIRS      => 3,       // stop after 10 redirects
            CURLOPT_POST           => count($parameters),
            CURLOPT_POSTFIELDS     => $post,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        );
    
        $url = path_join($this->__server_route, $endpoint );
        
        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
		
		$perf_1 = microtime(true);
		
        $content = curl_exec( $ch );
		
		$ms_delay = microtime(true) - $perf_1;
		
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );
    
        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
		$header['ms_delay'] = $ms_delay;
		$header['length'] = strlen($content);
        $header['content'] = $content;
        
		if ($err == 0 && (strlen($content) > 1024 * 64 || $ms_delay > 0.5))		
			$this->_cache($post, $header);
		
        return $header;
    }
    
    function call($call, $parameters = array()) {
        return $this->_get_url($call, $parameters);
    }
}


?>