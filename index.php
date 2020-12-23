<?php

function getBaseURL() {
	return "http://mydomainname.com/hash.php";
}

//Encode
function encrypte($string,$key){
    $returnString = "";
    $charsArray = str_split("e7NjchMCEGgTpsx3mKXbVPiAqn8DLzWo_6.tvwJQ-R0OUrSak954fd2FYyuH~1lIBZ");
    $charsLength = count($charsArray);
    $stringArray = str_split($string);
    $keyArray = str_split(hash('sha256',$key));
    $randomKeyArray = array();
    while(count($randomKeyArray) < $charsLength){
        $randomKeyArray[] = $charsArray[rand(0, $charsLength-1)];
    }
    for ($a = 0; $a < count($stringArray); $a++){
        $numeric = ord($stringArray[$a]) + ord($randomKeyArray[$a%$charsLength]);
        $returnString .= $charsArray[floor($numeric/$charsLength)];
        $returnString .= $charsArray[$numeric%$charsLength];
    }
    $randomKeyEnc = '';
    for ($a = 0; $a < $charsLength; $a++){
        $numeric = ord($randomKeyArray[$a]) + ord($keyArray[$a%count($keyArray)]);
        $randomKeyEnc .= $charsArray[floor($numeric/$charsLength)];
        $randomKeyEnc .= $charsArray[$numeric%$charsLength];
    }
    return $randomKeyEnc.hash('sha256',$string).$returnString;
}

//Split link
function split_link($link) {
	$spilt = chunk_split($link, 500, "=");
	$array = explode('=', $spilt);
	foreach($array as $i => $data) {
		$list .= 'link'.($i+1).'='.$data.'&';
	}
	$split_link = rtrim($list, '&');
	return $split_link;
}

/**
 * @param string $url_to_encrypt
 * @return string
 */
function encryptURL($url_to_encrypt) {
	echo getBaseURL() . '?' . split_link(encrypte($url_to_encrypt,'hashv1.00'));
}

/**
 * @param string $method GET|POST|DELETE
 * @return bool
 */
function isRequestMethod($method) {
	$is_equal = $_SERVER['REQUEST_METHOD'] === $method;
	return $is_equal;
}

/**
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function getPostedData($name, $default="") {
	$var = $default;
	
	if(isset($_POST) && array_key_exists($name, $_POST)) {
		$var = $_POST[$name];
	}
	
	return $var;
}

if (isRequestMethod('POST')){
	$url_to_encrypt = getPostedData('url');
	print encryptURL($url_to_encrypt);
}

?>

<form action="" method="POST">
	<label for="input_url">URL para encriptar</label>
	<input id="input_url" type="text" name="url" />
	<button type="submit">Enviar</button>
</form>
