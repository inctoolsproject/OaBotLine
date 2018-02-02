<?php
/*
copyright @ medantechno.com
Modified @ Farzain - zFz
2017

*/

require_once('./line_class.php');
require_once('./unirest-php-master/src/Unirest.php');

$channelAccessToken = 'oFvjMsdLtougQ8RfV0l2Rq/Pf3TFDs8PpHTRqyigzYh6wV/l70A1VGVlTPfgYB7JhIwgNHgOfVtj4X8YnGsZ25QvBkVojAjx3+IBdzB4/jMnYJj/la1aI7nN4bDk0Msvh0coOkp6GIezzVkcbO/DXgdB04t89/1O/w1cDnyilFU='; //sesuaikan 
$channelSecret = '6dbc81520633d4e5cdb173a4db22b8e3';//sesuaikan

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
	$parsed['loct1'] = $json['results']['0']['address_components']['0']['long_name'];
    return $parsed; 
}
function bitly($keyword) {
    $uri = "https://api-ssl.bitly.com/v3/shorten?access_token=497e74afd44780116ed281ea35c7317285694bf1&longUrl=" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Berhasil\nURL Asli: ";
	$result .= $json['data']['long_url'];
	$result .= "\nURL Pendek: ";
	$result .= $json['data']['url'];
    return $result;
}
function youtube($keyword) {
    $uri = "https://www.googleapis.com/youtube/v3/search?part=snippet&order=relevance&regionCode=lk&q=" . $keyword . "&key=AIzaSyB5cpL7DYDn_2c7QuExnGOZ1Wmg4AQmx8c&maxResults=10&type=video";
	

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $parsed = array();
    $parsed['a1'] = $json['items']['0']['id']['videoId'];
	$parsed['b1'] = $json['items']['0']['snippet']['title'];
	$parsed['c1'] = $json['items']['0']['snippet']['thumbnails']['high']['url'];
    $parsed['a2'] = $json['items']['1']['id']['videoId'];
	$parsed['b2'] = $json['items']['1']['snippet']['title'];
	$parsed['c2'] = $json['items']['1']['snippet']['thumbnails']['high']['url'];
    $parsed['a3'] = $json['items']['2']['id']['videoId'];
	$parsed['b3'] = $json['items']['2']['snippet']['title'];
	$parsed['c3'] = $json['items']['2']['snippet']['thumbnails']['high']['url'];
    $parsed['a4'] = $json['items']['3']['id']['videoId'];
	$parsed['b4'] = $json['items']['3']['snippet']['title'];
	$parsed['c4'] = $json['items']['3']['snippet']['thumbnails']['high']['url'];
    $parsed['a5'] = $json['items']['4']['id']['videoId'];
	$parsed['b5'] = $json['items']['4']['snippet']['title'];
	$parsed['c5'] = $json['items']['4']['snippet']['thumbnails']['high']['url'];
    $parsed['a6'] = $json['items']['5']['id']['videoId'];
	$parsed['b6'] = $json['items']['5']['snippet']['title'];
	$parsed['c6'] = $json['items']['5']['snippet']['thumbnails']['high']['url'];
    $parsed['a7'] = $json['items']['6']['id']['videoId'];
	$parsed['b7'] = $json['items']['6']['snippet']['title'];	
	$parsed['c7'] = $json['items']['6']['snippet']['thumbnails']['high']['url'];
    $parsed['a8'] = $json['items']['7']['id']['videoId'];
	$parsed['b8'] = $json['items']['7']['snippet']['title'];
	$parsed['c8'] = $json['items']['7']['snippet']['thumbnails']['high']['url'];
    $parsed['a9'] = $json['items']['8']['id']['videoId'];
	$parsed['b9'] = $json['items']['8']['snippet']['title'];
	$parsed['c9'] = $json['items']['8']['snippet']['thumbnails']['high']['url'];
    $parsed['a10'] = $json['items']['9']['id']['videoId'];
	$parsed['b10'] = $json['items']['9']['snippet']['title'];	
	$parsed['c10'] = $json['items']['9']['snippet']['thumbnails']['high']['url'];
    return $parsed;
}
function yt($keyword) { 
    $uri = "http://wahidganteng.ga/process/api/b967d83eed40cf9e17958b1dc85b1db7/youtube-downloader?url=https://www.youtube.com/watch?v=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['data']['1']['link']; 
    return $result; 
}
function yt_pict($keyword) { 
    $uri = "https://www.googleapis.com/youtube/v3/videos?key=AIzaSyB5cpL7DYDn_2c7QuExnGOZ1Wmg4AQmx8c&part=snippet,contentDetails,statistics,topicDetails&id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['items']['0']['snippet']['thumbnails']['high']['url']; 
    return $result; 
}
function wib($keyword) {
    $uri = "https://time.siswadi.com/timezone/?address=Jakarta";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
	$parsed = array(); 
	$parsed['time'] = $json['time']['time'];
	$parsed['date'] = $json['time']['date'];
    return $parsed;
}
function wit($keyword) {
    $uri = "https://time.siswadi.com/timezone/?address=jayapura";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
	$parsed = array(); 
	$parsed['time'] = $json['time']['time'];
	$parsed['date'] = $json['time']['date'];
    return $parsed;
}
function wita($keyword) {
    $uri = "https://time.siswadi.com/timezone/?address=manado";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
	$parsed = array(); 
	$parsed['time'] = $json['time']['time'];
	$parsed['date'] = $json['time']['date'];
    return $parsed;
}
function song($keyword) { 
    $uri = "http://ide.fdlrcn.com/workspace/yumi-apis/joox?songname=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $parsed = array(); 
    $parsed['judul'] = (string) $json['0']['0']; 
    $parsed['durasi'] = (string) $json['0']['1']; 
    $parsed['unduh'] = (string) $json['0']['4']; 
    return $parsed; 
} 

#-------------------------[Function]-------------------------#

//show menu, saat join dan command /menu
if ($type == 'join' || $command == '/keyword') {
    $text = "●▬▬▬▬▬ஜ۩۞۩ஜ▬▬▬▬▬●\n                 •MENU PANDA•\n●▬▬▬▬▬ஜ۩۞۩ஜ▬▬▬▬▬●\n\n/shalat\n/cuaca\n/youtube\n/song\n/say\n/ig\n/ig-vid\n/ig-pict\n/ig-dp\n/apakah\n/quote\n/pendekin\n/jam";
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
					'title' => str_replace("+"," ",$options),
					'address' => $result['loct1'],
					'latitude' => $result['lat'],
					'longitude' => $result['long'] 
                )
            )
        );
    }	
	if ($command == '/pendekin') {

        $result = bitly($options);
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
	if ($command == '/youtube') {

        $result = youtube($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
				array (
				  'type' => 'template',
				  'altText' => 'Youtube',
				  'template' => 
				  array (
				    'type' => 'carousel',
				    'columns' => 
				    array (
				      0 => 
				      array (
				        'thumbnailImageUrl' => $result['c1'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b1'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a1'],
				          ),
				        ),
				      ),
				      1 => 
				      array (
				        'thumbnailImageUrl' => $result['c2'],
				        'imageBackgroundColor' => '#000000',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b2'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a2'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a2'],
				          ),
				        ),
				      ),	
				      2 => 
				      array (
				        'thumbnailImageUrl' => $result['c3'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b3'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a3'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a3'],
				          ),
				        ),
				      ),					  
				      3 => 
				      array (
				        'thumbnailImageUrl' => $result['c4'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b4'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a4'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a4'],
				          ),
				        ),
				      ),
				      4 => 
				      array (
				        'thumbnailImageUrl' => $result['c5'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b5'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a5'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a5'],
				          ),
				        ),
				      ),
				      5 => 
				      array (
				        'thumbnailImageUrl' => $result['c6'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b6'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a6'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a6'],
				          ),
				        ),
				      ),					  
				      6 => 
				      array (
				        'thumbnailImageUrl' => $result['c7'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b7'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a7'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a7'],
				          ),
				        ),
				      ),					  
				      7 => 
				      array (
				        'thumbnailImageUrl' => $result['c8'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b8'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Stream Video',
				            'text' => '/yt-video '.$result['a8'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Youtube',
				            'uri' => 'https://youtu.be/'.$result['a8'],
				          ),
				        ),
				      ),					  
				    ),
				    'imageAspectRatio' => 'rectangle',
				    'imageSize' => 'cover',
				  ),
				)		
            )
        );
}
if ($command == '/yt-video') {

        $result = yt($options);
		$results = yt_pict($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                'type' => 'video', 
                'originalContentUrl' => $result, 
                'previewImageUrl' => $results, 
                )
            )
        );
}
if ($command == '/jam') { 
     
        $result = wib($options); 
		$result2 = wit($options); 
		$result3 = wita($options); 
        $balas = array( 
            'replyToken' => $replyToken, 
            'messages' => array( 
                array ( 
                  'type' => 'template', 
                  'altText' => 'Jam Indonesia', 
                  'template' =>  
                  array ( 
                    'type' => 'carousel', 
                    'columns' =>  
                    array ( 
                      0 =>  
                      array ( 
                        'thumbnailImageUrl' => 'https://image.prntscr.com/image/K0b2P-S6RO6fzFqOVwkgtw.jpg', 
                        'imageBackgroundColor' => '#FFFFFF', 
                        'title' => 'WIB', 
                        'text' => 'Jam Indonesia', 
                        'actions' =>  
                        array ( 
                          0 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result['time'], 
                            'data' => $result['time'], 
                          ), 
                          1 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result['date'],
                            'data' => $result['date'],
                          ), 
                        ), 
                      ), 
                      1 =>  
                      array ( 
                        'thumbnailImageUrl' => 'https://image.prntscr.com/image/K0b2P-S6RO6fzFqOVwkgtw.jpg', 
                        'imageBackgroundColor' => '#000000', 
                        'title' => 'WIT', 
                        'text' => 'Jam Indonesia', 
                        'actions' =>  
                        array ( 
                          0 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result2['time'], 
                            'data' => $result2['time'], 
                          ), 
                          1 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result2['date'],
                            'data' => $result2['date'],
                          ), 
                        ), 
                      ), 
					  2 =>  
                      array ( 
                        'thumbnailImageUrl' => 'https://image.prntscr.com/image/K0b2P-S6RO6fzFqOVwkgtw.jpg', 
                        'imageBackgroundColor' => '#000000', 
                        'title' => 'WITA', 
                        'text' => 'Jam Indonesia', 
                        'actions' =>  
                        array ( 
                          0 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result3['time'], 
                            'data' => $result3['time'], 
                          ), 
                          1 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result3['date'],
                            'data' => $result3['date'],
                          ), 
                        ),  
                      ),
                    ), 
                  ), 
                ) 
            ) 
        ); 
}
if ($command == '/song-unduh') { 

        $result = song($options); 
        $balas = array( 
            'replyToken' => $replyToken, 
            'messages' => array( 
                array( 
                    'type' => 'text', 
                    'text' => $result['unduh'], 
                ), 
                array( 
                    'type' => 'text', 
                    'text' => 'Silahkan kakak Salin URL diatas lalu Tempel di Browser kakak ^_^', 
                ) 
            ) 
        ); 
    }
    if ($command == '/song') { 
     
        $result = song($options); 
        $balas = array( 
            'replyToken' => $replyToken, 
            'messages' => array( 
                array ( 
                        'type' => 'template', 
                          'altText' => 'Info Musik', 
                          'template' =>  
                          array ( 
                            'type' => 'buttons', 
                            'thumbnailImageUrl' => 'https://image.prntscr.com/image/K0b2P-S6RO6fzFqOVwkgtw.jpg', 
                            'imageAspectRatio' => 'rectangle', 
                            'imageSize' => 'cover', 
                            'imageBackgroundColor' => '#FFFFFF', 
                            'title' => $result['judul'], 
                            'text' => 'Durasi: ' . $result['durasi'], 
                            'actions' =>  
                            array ( 
                              0 =>  
                              array ( 
                                'type' => 'uri', 
                                'label' => 'Dengarkan', 
                                'uri' => $result['unduh'], 
                              ), 
							   1 =>  
                              array ( 
								'type' => 'message', 
								'label' => 'Unduh',
								'text' => '/song-unduh '.$options,
                              ), 
                            ), 
                          ), 
                        ) 
            ) 
        ); 
    }
    if ($command == '/owner') { 
     
        $balas = array( 
            'replyToken' => $replyToken, 
            'messages' => array( 
                array ( 
                        'type' => 'template', 
                          'altText' => 'About Owner', 
                          'template' =>  
                          array ( 
                            'type' => 'buttons', 
                            'thumbnailImageUrl' => 'https://image.prntscr.com/image/K0b2P-S6RO6fzFqOVwkgtw.jpg', 
                            'imageAspectRatio' => 'rectangle', 
                            'imageSize' => 'cover', 
                            'imageBackgroundColor' => '#FFFFFF', 
                            'title' => 'About Owner', 
                            'text' => 'Panda Bot', 
                            'actions' =>  
                            array ( 
                              0 =>  
                              array ( 
                                'type' => 'uri', 
                                'label' => 'Contact', 
                                'uri' => 'https://line.me/ti/p/~kevin15072003juli', 
                              ), 
                            ), 
                          ), 
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
