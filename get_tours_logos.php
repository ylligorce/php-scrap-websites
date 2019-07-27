<?php 

die('Go and delete me!');

include("simple_html_dom.php");

$web = "https://www.pgatour.com/tournaments/schedule.html";

$downloadDIR = "C:/xampp/htdocs/php-scrap/tours_img/";

$html = file_get_html($web);

foreach($html->find(".tournament-text a") as $name){
	$has_http = substr($name->href, 0,1);
	$tour_name = str_replace(" ", "_", trim(strtolower($name->plaintext)));
	$tour_link = $name->href;
	if($has_http != 'h'){
		$tour_link = "https://www.pgatour.com".$name->href;
	}
	if(isset($tour_link)){
		$img_url = file_get_html($tour_link);
		if(!$img_url){
			continue;
		}
		foreach($img_url->find(".logos .logo img") as $image){
			try{
				$last_part = substr(strrchr('https://www.pgatour.com'.$image->src, "/"), 1);
				$last_part = $tour_name;
				$img_type = substr(strrchr($image->src, "."), 1);
				$content = file_get_contents('https://www.pgatour.com'.$image->src);
				file_put_contents($downloadDIR . $last_part.'.'.$img_type, $content);				
			}catch (Exception $e) { 
				echo 'Caught exception: ', $e->getMessage(), "\n"; 
			} 
		}
	}
}

echo "Done: ".date("F j, Y, g:i a");

?>