<?php
/*
copyright @ medantechno.com
Modified @ Farzain - zFz
2017

*/

require_once('./line_class.php');
require_once('./unirest-php-master/src/Unirest.php');

$channelAccessToken = 'KSqC4L4DQB5o2uk3eZTIwSNQgUGKoMF451X9VIkgmlzzDTEw+yCoA1eknDJ8HQM/IK0aLhJYABpYBZZpInVA7tksnVvLen2MUIDGwR8MtG1DBnz9LFcip99gZJ0zqCaezrR3tQlGlK8Xhb7Gz3nPMAdB04t89/1O/w1cDnyilFU='; //sesuaikan 
$channelSecret = 'd735bbd21936b39f05224829eaed6f50';//sesuaikan

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$userId 	= $client->parseEvents()[0]['source']['userId'];
$groupId 	= $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp	= $client->parseEvents()[0]['timestamp'];
$type 		= $client->parseEvents()[0]['type'];

$message 	= $client->parseEvents()[0]['message'];
$messageid 	= $client->parseEvents()[0]['message']['id'];

$profil = $client->profil($userId);

$pesan_datang = explode(" ", $message['text']);

$command = $pesan_datang[0];
$options = $pesan_datang[1];
if (count($pesan_datang) > 2) {
    for ($i = 2; $i < count($pesan_datang); $i++) {
        $options .= '+';
        $options .= $pesan_datang[$i];
    }
}

#-------------------------[Function]-------------------------#
function cuaca($keyword) {
    $uri = "http://api.openweathermap.org/data/2.5/weather?q=" . $keyword . ",ID&units=metric&appid=e172c2f3a3c620591582ab5242e0e6c4";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Ramalan Cuaca ";
	$result .= $json['name'];
	$result .= " Dan Sekitarnya";
	$result .= "\n\nCuaca : ";
	$result .= $json['weather']['0']['main'];
	$result .= "\nDeskripsi : ";
	$result .= $json['weather']['0']['description'];
    return $result;
}
function shalat($keyword) {
    $uri = "https://time.siswadi.com/pray/" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Jadwal Shalat Sekitar ";
	$result .= $json['location']['address'];
	$result .= "\nTanggal : ";
	$result .= $json['time']['date'];
	$result .= "\n\nShubuh : ";
	$result .= $json['data']['Fajr'];
	$result .= "\nDzuhur : ";
	$result .= $json['data']['Dhuhr'];
	$result .= "\nAshar : ";
	$result .= $json['data']['Asr'];
	$result .= "\nMaghrib : ";
	$result .= $json['data']['Maghrib'];
	$result .= "\nIsya : ";
	$result .= $json['data']['Isha'];
    return $result;
}
function ig($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/ig.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $parsed = array(); 
    $parsed['username'] = $json['username']; 
    $parsed['nama'] = $json['full_name']; 
    $parsed['followers'] = $json['followed_by']['count']; 
    $parsed['following'] = $json['follows']['count']; 
    $parsed['dp'] = $json['profile_pic_url_hd']; 
    return $parsed; 
}
function ig_pict($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/media.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['shortcode_media']['display_resources']['1']['src']; 
    return $result; 
} 
function ig_vid($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/media.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['shortcode_media']['video_url']; 
    return $result; 
} 
function ig_dp($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/ig.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['profile_pic_url_hd']; 
    return $result; 
}
function apakah(){
    $list_jwb = array(
		'Ya',
		'Tidak',
		'Bisa jadi',
		'Mungkin',
		'Tentu tidak',
		'Coba tanya lagi'
		);
    $jaws = array_rand($list_jwb);
    $jawab = $list_jwb[$jaws];
    return($jawab);
}
function tts($keyword) { 
    $uri = "https://translate.google.com/translate_tts?ie=UTF-8&tl=id-ID&client=tw-ob&q=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 
    $result = $uri; 
    return $result; 
}
function quote($keyword) {
    $uri = "https://pesananmaskevin.herokuapp.com/data/quote.php";
	
	$hasil = file_get_contents($uri);
	
    $response = Unirest\Request::get("$uri");
	$result = str_replace("<br />","",$hasil); 
    return $result;
}
function lokasi($keyword) { 
    $uri = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $parsed = array(); 
    $parsed['lat'] = $json['results']['0']['geometry']['location']['lat']; 
    $parsed['long'] = $json['results']['0']['geometry']['location']['lng']; 
	$parsed['loct'] = $json['results']['0']['formatted_address']; 
    return $parsed; 
}
#-------------------------[Function]-------------------------#

//show menu, saat join dan command /menu
if ($type == 'join' || $command == '/menu') {
    $text = "Halo Kak ^_^\nAku Bot Prediksi Cuaca, Kamu bisa mengetahui prediksi cuaca di daerah kamu sesuai dengan sumber BMKG";
    $balas = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}

//pesan bergambar
if($message['type']=='text') {
	    if ($command == '/cuaca') {
        $result = cuaca($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
		if ($command == '/shalat') {
        $result = shalat($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
	if ($command == '/ig-dp') { 
	
			$text = ig_dp($options); 
			$balas = array( 
				'replyToken' => $replyToken, 
				'messages' => array(         
					array ( 
						'type' => 'image', 
						'originalContentUrl' => $text, 
						'previewImageUrl' => $text 
					) 
				) 
			); 
	} 
	if ($command == '/ig') { 
			$result = ig($options); 
			$balas = array( 
				'replyToken' => $replyToken, 
				'messages' => array( 
					array( 
						'type' => 'template', 
						'altText' => 'Instagram', 
						'template' => array( 
							'type' => 'buttons', 
							'title' => $result['username'], 
							'thumbnailImageUrl' => $result['dp'], 
							'text' => $result['nama'], 
							'actions' => array( 
								array( 
									'type' => 'postback', 
									'label' => 'Lihat DP', 
									'data' => 'action=add&itemid=123', 
									'text' => '/ig-dp ' . $options 
								), 
								array( 
									'type' => 'postback', 
									'label' => 'Followers: ' . $result['followers'], 
									'data' => 'action=add&itemid=123', 
								),                             
								array( 
									'type' => 'postback', 
									'label' => 'Following: ' . $result['following'], 
									'data' => 'action=add&itemid=123', 
								) 
							) 
						) 
					) 
				) 
			); 
	} 
	if ($command == '/ig-pict') { 
	
			$result = ig_pict($options); 
			$balas = array( 
				'replyToken' => $replyToken, 
				'messages' => array(         
					array ( 
						'type' => 'image', 
						'originalContentUrl' => $result, 
						'previewImageUrl' => $result 
					) 
				) 
			); 
	}
	if ($command == '/ig-vid') { 
	
			$result = ig_vid($options); 
			$result2 = ig_pict($options); 
			$balas = array( 
				'replyToken' => $replyToken, 
				'messages' => array(         
					array ( 
					'type' => 'video', 
					'originalContentUrl' => $result, 
					'previewImageUrl' => $result2, 
					) 
				) 
			); 
	}
	    if ($command == '/apakah') {
        $result = apakah($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
	if ($command == '/say') {

        $result = tts($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
				array (
				'type' => 'audio',
				'originalContentUrl' => $result,
				'duration' => 10000,
				)
            )
        );
}
	if ($command == '/quote') {
        $result = quote($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
	if ($command == '/lokasi') {
        $result = lokasi($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
					'type' => 'location',
					'title' => substr($result['loct'], 0, 20),	
					'address' => $result['loct'],
					'latitude' => $result['lat'],
					'longitude' => $result['long'] 
                )
            )
        );
    }	
	
	
	
	}
if (isset($balas)) {
    $result = json_encode($balas);
//$result = ob_get_clean();

    file_put_contents('./balasan.json', $result);


    $client->replyMessage($balas);
}
?>
