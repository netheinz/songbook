<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php";

$mode = isset($_GET["mode"]) && !empty($_GET["mode"]) ? $_GET["mode"] : "list";

$page_title = "Sange";

switch(strtoupper($mode)) {
	default:
	case "LIST":

		$arr_buttons = [
			toolbox::getButton("Opret ny", "?mode=edit"),
		];

		toolbox::sysHeader();

		echo toolbox::getAdminHeader($page_title, "Oversigt", $arr_buttons);

		$song = new song();
		$rows = [];

        foreach($song->getAll() as $values) {
            $values["options"] = toolbox::getIcon("?mode=edit&id=" . $values["id"], "edit", ["id" => 3]) .
                                 toolbox::getIcon("?mode=details&id=" . $values["id"], "eye") .
                                 toolbox::getIcon("?mode=delete&id=" . $values["id"], "trash-alt");
            $rows[] = $values;
        }

        $p = new listPresenter($song->columns, $rows, "song");
        echo $p->presentlist();

		/*
        $output = "<div class='row rowheader song'>\n" .
                   "   <div>Handling</div>\n" .
                   "   <div>Titel</div>\n" .
                   "   <div>Album</div>\n" .
                   "   <div>Artist</div>\n" .
                   "</div>\n";

		$output .= "<div class='row song'>";
		foreach($row as $rowData) {
			$output .= "<div>" .
							"<a href=\"?mode=edit&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-pencil-alt\" title=\"Rediger\"></i></a>\n" .
							"<a href=\"?mode=details&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-eye\" title=\"Se detaljer\"></i></a>\n" .
							"<a onclick=\"remove(".$rowData["id"].")\">" .
                                "<i class=\"fas fa-trash-alt\" title=\"Slet\"></i></a>\n" .
						"</div>";
			$output .= "<div>" . $rowData["song"] . "</div>\n";
			$output .= "<div>" . $rowData["album"] . "</div>\n";
			$output .= "<div>" . $rowData["artist"] . "</div>\n";

		}
		$output .= "</div>";

		echo $output;
        */
		toolbox::sysFooter();
		break;

    case "DETAILS":
        $id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;

	    $arr_buttons = [
		    toolbox::getButton("Oversigt", "?mode=list"),
		    toolbox::getButton("Opret ny", "?mode=create")
	    ];

	    toolbox::sysHeader();

	    echo toolbox::getAdminHeader($page_title, "Se detaljer", $arr_buttons);

	    $output = "<div class='row rowheader details'>\n" .
	               "<div>Felt</div>\n" .
	               "<div>Værdi</div>\n" .
	               "</div>\n";

	    $song = new song();
	    $song->get($id);

	    $row = get_object_vars($song);
	    foreach($row as $value) {
	        var_dump($value);
        }


	    echo $output;
	    toolbox::sysFooter();
        break;

	case "EDIT":
	    //Henter ID fra GET - UPDATE hvis id er større end 0 ellers CREATE
		$id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;

		//Definerer sidetitel ud fra create/update
		$mode_title = ($id > 0) ? "Rediger" : "Opret ny sang";

		//Deklarerer variabler til felt værdier
		$title = "";
		$content = "";
		$genre_id = 0;

		//Henter eksisterende data i tilfælde af en update
		if($id > 0) {
		    $sql = "SELECT * FROM song " .
                   "WHERE id = :id";
		    $stmt = $db->prepare($sql);
		    $stmt->bindParam(":id", $id);
		    $stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
		    $title = $row["title"];
		    $content = $row["content"];
		    $genre_id = $row["genre_id"];

        }

        //Henter data fra genre tabel - bruges til selectbox
        $sql = "SELECT * FROM genre";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$row_genre = $stmt->fetchAll(PDO::FETCH_ASSOC);


		toolbox::sysHeader();

		//Sætter button panel
		$arr_buttons = [
			toolbox::getButton("Oversigt", "song.php"),
		];

		echo toolbox::getAdminHeader($page_title, "Opret ny sang", $arr_buttons);

		?>
        <form method="post" action="?mode=save">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <fieldset>
                <div>
                    <label for="title">Titel:</label>
                    <input name="title" id="title" placeholder="Indtast titel" value="<?php echo $title ?>">
                </div>
                <div>
                    <label for="content">Tekst:</label>
                    <textarea name="content" id="content" placeholder="Indtast titel"><?php echo $content ?></textarea>
                </div>
                <div>
                    <label for="genre">Genre:</label>
                    <select name="genre_id">
                        <?php
                            //Loop row_genre og lav options til select box
                            foreach ($row_genre as $data) {
								var_dump($key);
                                //Marker valgte hvis der er en
                                $selected = ($data["id"] === $genre_id) ? "selected" : "";
                                echo "<option value=\"".$data["id"]."\" ".$selected.">" . $data["title"] . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <button type="submit">Send</button>
                <button type="reset">Nulstil </button>
            </fieldset>
        </form>
		<?php

		toolbox::sysFooter();
		break;

    case "SAVE":
	    //Henter ID fra POST - UPDATE hvis id er større end 0 ellers CREATE
	    $id = isset($_POST["id"]) && !empty($_POST["id"]) ? (int)$_POST["id"] : 0;
	
	    //Henter vars fra POST
        $title = mysql_real_escape_string($_POST["title"]);
        $content = mysql_real_escape_string($_POST["content"]);
        $genre_id = (int)$_POST["genre_id"];

	    if($id > 0) {
		    //Updater hvis id er større end 0
            $sql = "UPDATE song SET " .
                    "title = :title, " .
                    "content = :content, " .
                    "genre_id = :genre_id " .
                    "WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":content", $content);
            $stmt->bindParam(":genre_id", $genre_id);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

        } else {
            //Indsætter record hvis id er lig 0
            $sql = "INSERT INTO song(title, content, genre_id) VALUES(:title, :content, :genre_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":content", $content);
            $stmt->bindParam(":genre_id", $genre_id);
            $stmt->execute();
            $id = $db->lastInsertId();

        }

        //Viderestiller til detalje mode
        header("Location: ?mode=details&id=" . $id);

	    break;

    case "DELETE":
	    $id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;
        $song = new song();
        $song->delete($id);
        header("Location: ?mode=list");
	    break;

}