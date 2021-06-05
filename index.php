<?php
	function getBaseURL() {
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	//Encode
	/**
	 * @param string $string
	 * @param string $key
	 * @return string
	 */
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
		return getBaseURL() . 'hash.php?' . split_link(encrypte($url_to_encrypt,'hashv1.00'));
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

	if(!empty($_POST['url']) && isRequestMethod('POST')){
		$url_to_encrypt = encryptURL(getPostedData('url'));
	}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>URL Encryption & Decryption</title>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=PT+Sans'>
	<style>
	:root {
	  background: #f5f6fa;
	  color: #9c9c9c;
	  font: 1rem "PT Sans", sans-serif;
	}

	html, body, .container {
	  height: 100%;
	}

	a {
	  color: inherit;
	}
	a:hover {
	  color: #7f8ff4;
	}

	.container {
	  display: flex;
	  flex-direction: column;
	  align-items: center;
	  justify-content: center;
	}

	.uppercase {
	  text-transform: uppercase;
	}

	.btn {
	  display: inline-block;
	  background: transparent;
	  color: inherit;
	  font: inherit;
	  border: 0;
	  outline: 0;
	  padding: 0;
	  transition: all 200ms ease-in;
	  cursor: pointer;
	}
	.btn--primary {
	  background: #7f8ff4;
	  color: #fff;
	  box-shadow: 0 0 10px 2px rgba(0, 0, 0, 0.1);
	  border-radius: 2px;
	  padding: 12px 36px;
	}
	.btn--primary:hover {
	  background: #6c7ff2;
	}
	.btn--primary:active {
	  background: #7f8ff4;
	  box-shadow: inset 0 0 10px 2px rgba(0, 0, 0, 0.2);
	}
	.btn--inside {
	  margin-left: -96px;
	}

	.form__field {
	  width: 460px;
	  background: #fff;
	  color: #a3a3a3;
	  font: inherit;
	  box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.1);
	  border: 0;
	  outline: 0;
	  padding: 22px 18px;
		padding-right: 100px;
	}
	.container__item--bottom {
		width: 900px;
		font-size: 16px;
    text-align: center;
		word-wrap: break-word;
	}
	.results {
		color: #7f8ff4;
	}
</style>
</head>

<body>

  <div class="container">
		<h1>URL Encryption & Decryption</h1>
  	<div class="container__item">
			<form class="form" method="POST">
				<input type="url" name="url" class="form__field" placeholder="Enter your URL"  />
				<button type="submit" class="btn btn--primary btn--inside uppercase">Submit</button>
			</form>
  	</div>

  	<div class="container__item container__item--bottom">
			<br />
			<div class="results"><a href="<?= $url_to_encrypt;?>"><?= $url_to_encrypt;?></a></div> <br />
  		<span>© Copyright Ardi Artani.</span><br  />
			<span>This project is licensed under the MIT License - see the <a href="LICENSE">LICENSE</a> file for details</span>
  	</div>
  </div>

</body>
</html>
