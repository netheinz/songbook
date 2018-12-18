<?php

/**
 * Class song
 * Created By Heinz K
 * 18. Dec 2018
 */
class song {
	public $id;
	public $title;
	public $content;
	public $genre;
	public $artist;
	public $albums = [];

	private $db;

	/**
	 * song constructor.
	 */
	public function __construct() {
		global $db;
		$this->db = $db;
	}

	/**
	 * @return array
	 */
	public function getAll() {
		$sql = "SELECT song.id, song.title AS song, genre.title " .
		       "AS genre, album.title AS album, artist.name AS artist " .
		       "FROM song " .
		       "LEFT JOIN genre " .
		       "ON song.genre_id = genre.id " .
		       "LEFT JOIN song_album_rel " .
		       "ON song.id = song_album_rel.song_id " .
		       "LEFT JOIN album " .
		       "ON song_album_rel.album_id = album.id " .
		       "LEFT JOIN artist " .
		       "ON album.artist_id = artist.id " .
		       "ORDER BY RAND()";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $id
	 */
	public function get($id) {
		$this->id = $id;
		$sql = "SELECT song.*, genre.title AS genre " .
		       "FROM song " .
		       "JOIN genre " .
		       "ON song.genre_id = genre.id " .
		       "WHERE song.id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->title = $row["title"];
		$this->content = $row["content"];
		$this->genre = $row["genre"];
		$this->artist = $this->getSongArtist($this->id);
		$this->albums = $this->getSongAlbums($this->id);

	}

	/**
	 * @param $id
	 *
	 * @return array
	 */
	public function getSongAlbums($id) {
		$this->id = $id;
		$sql = "SELECT a.title " .
		       "FROM album a " .
		       "JOIN song_album_rel x " .
		       "ON a.id = x.album_id " .
		       "WHERE x.song_id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getSongArtist($id) {
		$this->id = $id;
		$sql = "SELECT a.name " .
		       "FROM album al " .
		       "JOIN song_album_rel x " .
		       "ON al.id = x.album_id " .
		       "JOIN artist a " .
		       "ON a.id = al.artist_id " .
		       "WHERE x.song_id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}

}