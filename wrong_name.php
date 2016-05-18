<?php
$conn = new mysqli("localhost","echonest","echonest","echonest");
$echonest_songs = $conn->query("SELECT * FROM songs WHERE rdtitle IS NOT NULL");

while ($r = $echonest_songs->fetch_assoc()) {
	$words = explode(" ",$r['rdtitle']);
	foreach ($words as $word) {
		//if (strpos($word,'b
		if (strpos(strtolower($r['name']),strtolower($word)) === false) {
			echo $r['id'] . ": " . $r['rdtitle'] . " :: " . $r['name'] . "\n";
			continue;
		}
	}
}
