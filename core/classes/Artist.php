<?php
/**
 * Created by PhpStorm.
 * User: heinz
 * Date: 13/12/2018
 * Time: 19.25
 */
class artist {
	public $id;
	public $name;
	public $info;
	private $db;

	/**
	 * Artist constructor.
	 * Globaliserer db objekt og sætter det som class member
	 */
	public function __construct() {
		global $db;
		$this->db = $db;
	}

	/**
	 * Returnerer array med alle artister
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
	 * Henter en artist ud fra id og tildeler værdier til class properties
	 * @param $id
	 *
	 * @return mixed single array
	 */
	public function get($id) {
		$this->id = $id;
		$sql = "SELECT * FROM artist WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->name = $row["name"];
		$this->info = $row["info"];
	}

	/**
	 * @return mixed
	 */
	public function	save() {
		if($this->id > 0) {
			$sql = "UPDATE artist SET name = :name, info = :info WHERE id = :id";
			$stmt = $db->prepare();
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":info", $this->info);
			$stmt->bindParam(":id", $this->id);
			$stmt->execute();
		} else {
			$sql = "INSERT INTO artist(name, info) VALUES(:name, :info)";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":info", $this->info);
			$stmt->execute();
			$this->id = $db->lastInsertId();
		}
		return $this->id;
	}

	/**
	 * @param $id
	 */
	public function delete($id) {
		$this->id = $id;
		$sql = "DELETE FROM artist " .
		       "WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":id", $this->id);
		$stmt->execute();
	}
}