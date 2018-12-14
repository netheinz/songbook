<?php
/**
 * Created by PhpStorm.
 * User: heinz
 * Date: 13/12/2018
 * Time: 19.25
 */

class Artist {
	public $id;
	public $name;
	public $info;

	private $db;

	/**
	 * Artist constructor.
	 */
	public function __construct() {
		global $db;
		$this->db = $db;
	}

	/**
	 * @return mixed multiple array
	 */
	public function getAll() {
		$sql = "SELECT * " .
		        "FROM artist";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $id
	 *
	 * @return mixed single array
	 */
	public function get($id) {
		$this->id = $id;
		$sql = "SELECT * " .
		       "FROM artist " .
		       "WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->name = $row["name"];
		$this->info = $row["info"];

	}

}