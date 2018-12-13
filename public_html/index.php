<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php";

require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/header.php";

$sql = "SELECT song.id, song.title AS song, genre.title AS genre, album.title AS album, artist.name AS artist " .
       "FROM song " .
       "JOIN genre " .
       "ON song.genre_id = genre.id " .
       "JOIN song_album_rel " .
       "ON song.id = song_album_rel.song_id " .
       "JOIN album " .
       "ON song_album_rel.album_id = album.id " .
       "JOIN artist " .
       "ON album.artist_id = artist.id " .
       "ORDER BY RAND()";
$stmt = $db->prepare($sql);
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

$accHtml = "<ul>";

foreach($row as $rowData) {
	$accHtml .= "<li><a href=\"details.php?id=".$rowData["id"]."\">" . $rowData["song"] . "</a></li>\n";
}

$accHtml .= "</ul>";

echo $accHtml;

require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/footer.php";
