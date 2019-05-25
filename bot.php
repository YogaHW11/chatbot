<?php
/*
Modified @ Farzain - zFz
2019
*/

require_once('./line_class.php');

$channelAccessToken = 'YOUR-CHANNEL-ACCESS-TOKEN'; //sesuaikan 
$channelSecret = 'YOUR-CHANNEL-SECRET-CODE';//sesuaikan

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

if ($type == 'join' || $command == '/menu') {
    $text = "Assalamualaikum Kakak, aku adalah bot Pencari Arah Kiblat digital, Silahkan kakak kirim lokasi tempat kakak, nanti aku cariin arah kiblat untuk tempat kakak ;)";
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

if($message['type']=='text') {

}
if ($message['type']=='location') {
	$lat = $message['latitude'];
    $long = $message['longitude'];
	$maps = "http://qib.la/embed/?lat=".$lat."&lng=".$long."&zoom=18&type=terrain";
	$satellite = "http://qib.la/embed/?lat=".$lat."&lng=".$long."&zoom=18&type=satellite";
		$balas = array(
            'replyToken' => $replyToken, 
            'messages' => array( 
                array(
                    'type' => 'template', 
                    'altText' => 'Kiblat', 
                    'template' => array( 
                        'type' => 'buttons', 
                        'title' => 'Kiblat', 
                        'thumbnailImageUrl' => 'https://www.kiblat.net/files/2017/03/kabah.jpg',
                        'text' => 'Ini Arah Kiblat Untuk Lokasi Kamu',
                        'actions' => array( 
                            array(
                                'type' => 'uri',
								'label' => 'Maps',
								'uri' => $maps,
                            ),
                            array(
                                'type' => 'uri',
								'label' => 'Satellite',
								'uri' => $satellite,
                            )
                        ) 
                    ) 
                ) 
            ) 
        );
}
if(isset($balas)) {
    $client->replyMessage($balas);
    file_put_contents("tes",json_encode($balas));
}
?>
