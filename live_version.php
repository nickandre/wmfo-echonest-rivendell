<?php
$conn = new mysqli("localhost","echonest","echonest","echonest");
//$rd = new mysqli("duke","rduser","letmein","Rivendell");
$echonest_songs = $conn->query("SELECT * FROM songs WHERE rdtitle IS NOT NULL");
//$update = $rd->prepare("UPDATE CART SET TITLE = ? WHERE NUMBER = ?");
$update = $conn->prepare("UPDATE songs SET status = 1 WHERE id = ?");


while ($r = $echonest_songs->fetch_assoc()) {
	if (strpos(strtolower($r['rdtitle']),"live") === false && strpos(strtolower($r['name']),"live") !== false) {
		echo $r['id'] . ": " . $r['rdtitle'] . " :: " . $r['name'] . "\n";
		$update->bind_param("i",$r['id']);
		$update->execute();
	}
}
