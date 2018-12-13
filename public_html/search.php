<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php";

$keyword = strip_tags($_GET["keyword"]);



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
       "WHERE (song.title LIKE :keyword) " .
       "OR (song.content LIKE :keyword)";

try {
	$stmt = $db->prepare( $sql );
	$stmt->bindParam( ":id", $id );
	$stmt->execute();
	$row = $stmt->fetch( PDO::FETCH_ASSOC );

	$accHtml = "<p><b>" . $row["song"] . "</b></p>";
	$accHtml .= "<p>" . $row["artist"] . " - " . $row["album"] . " - " . $row["genre"] . "</p>";
	echo $accHtml;
} catch(PDOException $error) {
	echo "Fejl i SQL: " . $error;
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/footer.php";
