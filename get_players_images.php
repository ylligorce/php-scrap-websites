<?php

	die('Go and delete me!');
	
	include("simple_html_dom.php");

	//base url (PGA TOUR)
	//https://www.pgatour.com/champions/players.html
	//https://www.pgatour.com/webcom/players.html
	
	$base = 'https://www.pgatour.com/webcom/players.html';
	$downloadDIR = "C:/xampp/htdocs/php-scrap/players_image/";

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_URL, $base);
	curl_setopt($curl, CURLOPT_REFERER, $base);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$str = curl_exec($curl);
	curl_close($curl);

	$html = new simple_html_dom();
	
	$html->load($str);

	//get all players url prefix
	$http_prefix = "https://www.pgatour.com";
	$iter = 0;
	foreach($html->find(".directory-item .name a") as $player){
		$iter++;
		//open each player
		$open_player = file_get_html($http_prefix.$player->href);
		
		//get player name		
		$player_name = $open_player->find("#playersListContainer .name")[0]->plaintext;
			//concat name, lowercase, xx_xx
			$player_name = str_replace(" ", "_", trim(strtolower($player_name)));
		
		//get player image url
		$player_image_url = $open_player->find('meta[property=og:image]', 0)->content;
		
		//save player image to folder with new name
		$content = file_get_contents($player_image_url);
		file_put_contents($downloadDIR . $player_name.'.png', $content);
	}

	$html->clear(); 
	unset($html);
	
	echo "Done(Players Image) \n Total: ".$iter." \n Date: ".date("F j, Y, g:i a");

?>