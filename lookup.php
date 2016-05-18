<?php
$apikey = "API_KEY_HERE";
$remaining = 1;

$fs = scandir("./codes");
$conn = new mysqli("localhost","echonest","echonest","echonest");
$stmt = $conn->prepare("INSERT INTO songs VALUES (?,?,?,?)");

//echo "Sizeof " . count($fs) . " " . $fs[250] . "\n";

$c = curl_init();
curl_setopt($c,CURLOPT_URL,"https://developer.echonest.com/api/v4/song/identify?api_key=$apikey");
curl_setopt($c,CURLOPT_POST, TRUE);
curl_setopt($c,CURLOPT_RETURNTRANSFER, TRUE);
foreach ($fs as $f) {
	if (strpos($f,".") !== FALSE) {
		continue;
	}
	$data = file_get_contents("./codes/$f");
	if (strpos($data,"code") === FALSE) {
		continue;
	}
	curl_setopt($c,CURLOPT_POSTFIELDS,array("query" => $data));
	while (TRUE) {
		$r = json_decode(curl_exec($c))->response;
		$retcode = curl_getinfo($c,CURLINFO_HTTP_CODE);
		if ($retcode == 429) {
			//hit rate limit
			echo "$retcode - Sleeeping for 10 seconds\n";
			sleep(10);
		} else {
			break;
		}
	}
	if ($r->status->message === "Success"){
		if (isset($r->songs[0]->title)) {
			$s = $r->songs[0];
			$stmt->bind_param("isss",$f,$s->title,$s->artist_name,$s->id);
			if (!$stmt->execute()) {
				echo $stmt->error;
			}
		} else {
			file_put_contents("./errors/$f",json_encode($r));
		}
	} else {
		file_put_contents("./errors/$f",json_encode($r));
	}
}
