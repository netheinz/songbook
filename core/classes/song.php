<?php

class song {
	public $id;
	public $title;
	public $content;
	public $genre_id;

	private $db;

	public function __construct() {
		global $db;
		$this->db = $db;
	}

	public function getAll() {
		$sql = "SELECT song.id, song.title AS song, genre.title " .
		       "AS genre, album.title AS album, artist.name AS artist " .
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
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}