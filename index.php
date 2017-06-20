<?php

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

 echo http://mydomainname.com/hash.php?'.split_link(encrypte('https://github.com/ArdiArtani/Google-Drive-Player-Script/','hashv1.00'));
?>
