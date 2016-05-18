<?php
$conn = new mysqli("localhost","echonest","echonest","echonest");
$rd = new mysqli("duke","rduser","letmein","Rivendell");
$echonest_songs = $conn->query("SELECT id FROM songs");

$update = $conn->prepare("UPDATE songs SET rdtitle = ?, rdartist = ? WHERE id = ?");
$lookup = $rd->prepare("SELECT TITLE, ARTIST FROM CART WHERE NUMBER = ?");

while ($r = $echonest_songs->fetch_assoc()) {
	$lookup->bind_param('i',$r['id']);
	$lookup->bind_result($rdtitle, $rdartist);
	$lookup->execute();
	$lookup->fetch();
	$update->bind_param('ssi',$rdtitle, $rdartist, $r['id']);
	$update->execute();
}
