<?php
set_time_limit(0);
error_reporting(0);

function http_request($url) {
	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getLink($imei,$country){

$__uid = 'ab2d-e'.mt_rand(10, 99).'1-i'.mt_rand(10, 99).'k-a' . mt_rand(100, 999);

if($country == "Malaysia"){
	$__setting = ['my', '-', 'Malaysia', 'https://hd.c.mi.com/my/eventapi/api/aptcha/index?type=netflix&uid='.$__uid];
}else if($country == "Thailand"){
	$__setting = ['th', '-', 'Thailand', 'https://hd.c.mi.com/th/eventapi/api/aptcha/index?type=netflix&uid='.$__uid];
}else if($country == "Philippines"){
	$__setting = ['ph', '-', 'Philippines', 'https://hd.c.mi.com/ph/eventapi/api/aptcha/index?type=netflix&uid='.$__uid];
}


	echo 'Buka link dibawah, terus input captchanya yak!' . PHP_EOL;
	echo '[' . $__setting[2] . '] Challenge the captcha => ' . $__setting[3] . PHP_EOL;
	echo 'Input captcha : ';
	$input = fopen("php://stdin","r");
	$answer = trim(fgets($input));
	$__setting[1] = $answer;

	$data = http_request('https://hd.c.mi.com/'.$__setting[0].'/eventapi/api/netflix/gettoken?uid='.$__uid.'&vcode='.$__setting[1].'&imei='.$imei);
	$data = json_decode($data, true);
	if (isset($data['msg']) && $data['msg'] == 'Success') {
		$__if_valid = $data['data']['redirect_url'] . '|' . $imei . '|' . $__setting[2] . PHP_EOL;
		file_put_contents("valid.txt", $__if_valid, FILE_APPEND);
		echo 'VALID => ' . $__if_valid;
	} else if(isset($data['code']) && $data['code'] == '800706') {
		echo 'Please challenge the captcha => ' . $__setting[2] . PHP_EOL;
	} else {
		echo 'INVALID => ' . $imei . '|' . $__setting[2] . PHP_EOL;
	}

}

function xiaomiCountry($imei)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://buy.mi.co.id/id/registration",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "imei=" . $imei,
    CURLOPT_HTTPHEADER => [
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
        "Accept-Encoding: gzip, deflate, br",
        "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
        "Cache-Control: max-age=0",
        "Connection: keep-alive",
        "Content-Length: 20",
        "Content-Type: application/x-www-form-urlencoded",
        "Host: buy.mi.co.id",
        "Origin: https://buy.mi.co.id",
        "Referer: https://buy.mi.co.id/id/registration",
        "Sec-Fetch-Dest: document",
        "Sec-Fetch-Mode: navigate",
        "Sec-Fetch-Site: same-origin",
        "Sec-Fetch-User: ?1",
        "Upgrade-Insecure-Requests: 1",
        "User-Agent: Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36",
        'sec-ch-ua: "Google Chrome";v="87", "\"Not;A\\Brand";v="99", "Chromium";v="87"',
        "sec-ch-ua-mobile: ?1"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        preg_match_all('/<p><span class="info-type">Negara pembelian: <\/span><span>(.*)<\/span>/', $response, $imei_country);
        if($imei_country[1][0] != ""){
        	$result = $imei . " | " . $imei_country[1][0]."\n";
        	
        	if($imei_country[1][0] == "Malaysia" || $imei_country[1][0] == "Thailand" || $imei_country[1][0] == "Philippines"){
        		//kalo ga mau pake fitur check imei atau ribet captha 
        		//di comment line dibawah ini yg     getLink($imei,$imei_country[1][0]);
        		getLink($imei,$imei_country[1][0]);
        		$file = fopen('output-imei[MY-PH-TH].txt', 'a+');
        		fwrite($file, "$result");
				fclose($file);
        	}else{
        		$file = fopen('output-imei[another].txt', 'a+');
        		fwrite($file, "$result");
				fclose($file);

        	}
        	
        	return $result;
			
        }
        else
        	return $imei . " | DIE".$imei_country[1][0] . "\n";


    }
}

function generateRandomString($length = 10) {
$characters = '0123456789';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
function getStr($string,$start,$end){
$str = explode($start,$string);
$str = explode($end,$str[1]);
return $str[0];
}
echo "============================\n";
echo "       BOT VALID IMEI MI 10 T WITH COUNTRY\n";
echo "Create by : Charles Giovanni x Wahyu Arif Purnomo x Fadhiil Rachman\n";
echo "=============================\n";
//eras yang gabungin

echo 'JUMLAH : ';
$jumlah = trim(fgets(STDIN)); 
$i=1;
while($i <= $jumlah){
$ime = generateRandomString(6);
$imei = '866228052'.$ime;
echo xiaomiCountry($imei);
$i++;
}
