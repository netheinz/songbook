<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php";

$mode = isset($_GET["mode"]) && !empty($_GET["mode"]) ? $_GET["mode"] : "list";

$page_title = "Artister";

switch(strtoupper($mode)) {
	default:
	case "LIST":

		$arr_buttons = [
			getButton("Opret ny", "?mode=edit"),
		];

		sysHeader();

		echo getAdminHeader($page_title, "Oversigt", $arr_buttons);

		$obj = new Artist();
		$row = $obj->getAll();

    	$accHtml = "<div class='row rowheader artist'>\n" .
                   "<div>Handling</div>\n" .
                   "<div>Navn</div>\n" .
                   "</div>\n";

		$accHtml .= "<div class='row artist'>";

		foreach($row as $rowData) {
			$accHtml .= "<div>" .
							"<a href=\"?mode=edit&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-pencil-alt\" title=\"Rediger\"></i></a>\n" .
							"<a href=\"?mode=details&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-eye\" title=\"Se detaljer\"></i></a>\n" .
							"<a href=\"?mode=delete&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-trash-alt\" title=\"Slet\"></i></a>\n" .
						"</div>";
			$accHtml .= "<div>" . $rowData["name"] . "</div>\n";

		}
		$accHtml .= "</div>";

		echo $accHtml;

		sysFooter();
		break;

    case "DETAILS":
        $id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;

	    $arr_buttons = [
		    getButton("Oversigt", "?mode=list"),
		    getButton("Opret ny", "?mode=create")
	    ];

	    $obj = new Artist();
	    $row = $obj->get($id);


	    sysHeader();

	    echo getAdminHeader($page_title, "Se detaljer", $arr_buttons);

	    $accHtml = "<div class='row rowheader details'>\n" .
	               "<div>Felt</div>\n" .
	               "<div>Værdi</div>\n" .
	               "</div>\n";


        if($row) {
	        $accHtml .= "<div class=\"row details\">";
	        foreach ( $row as $key => $value ) {
		        $accHtml .= "<div>" . $key . "</div>\n";
		        $accHtml .= "<div>" . $value . "</div>\n";
	        }
	        $accHtml .= "</div>\n";
	        echo $accHtml;
        }
	    sysFooter();
        break;

	case "EDIT":
	    //Henter ID fra GET - UPDATE hvis id er større end 0 ellers CREATE
		$id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;

		//Deklarerer variabler til felt værdier
		$name = "";
		$info = "";

		//Henter eksisterende data i tilfælde af en update
		if($id > 0) {
			$obj = new Artist();
			$obj->get($id);

		    $name = $obj->name;
		    $info = $obj->info;
        }

        //Henter data fra genre tabel - bruges til selectbox
        $sql = "SELECT * FROM genre";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$row_genre = $stmt->fetchAll(PDO::FETCH_ASSOC);

		sysHeader();

		//Definerer sidetitel ud fra create/update
		$mode_title = ($id > 0) ? "Rediger" : "Opret ny artist";		

		//Sætter button panel
		$arr_buttons = [
			getButton("Oversigt", "artist.php"),
		];

		echo getAdminHeader($page_title, $mode_title, $arr_buttons);

		?>
        <form method="post" action="?mode=save">
            <fieldset>
	            <input type="hidden" name="id" value="<?php echo $id ?>">
                <div>
                    <label for="title">Titel:</label>
                    <input name="name" id="name" placeholder="Indtast navn" value="<?php echo $name ?>">
                </div>
                <div>
                    <label for="content">Tekst:</label>
					<textarea name="content" id="content" placeholder="Indtast titel"><?php echo $info ?></textarea>
                </div>
                <div>
                    <label for="genre">Genre:</label>
                    <select name="genre_id">
                        <?php
                            //Loop row_genre og lav options til select box
                            foreach ($row_genre as $key => $data) {
                                //Marker valgte hvis der er en
                                $selected = ($data["id"] === $id) ? "selected" : "";
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

		sysFooter();
		break;

    case "SAVE":
	    //Henter ID fra POST - UPDATE hvis id er større end 0 ellers CREATE
	    $id = isset($_POST["id"]) && !empty($_POST["id"]) ? (int)$_POST["id"] : 0;

	    //Henter vars fra POST
        $title = $_POST["title"];
        $content = $_POST["content"];
        $genre_id = $_POST["genre_id"];

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
	    //Henter ID fra GET
	    $id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;

	    //Henter sang
	    if($id > 0) {
		    $sql  = "SELECT * FROM song " .
		            "WHERE id = :id";
		    $stmt = $db->prepare( $sql );
		    $stmt->bindParam( ":id", $id );
		    $stmt->execute();
		    $row = $stmt->fetch( PDO::FETCH_ASSOC );
	    }
	    sysHeader();

	    //Sætter button panel
	    $arr_buttons = [
		    getButton("Oversigt", "song.php"),
	    ];

	    echo getAdminHeader($page_title, "Slet sang", $arr_buttons);

	    ?>
        <form method="post" action="?mode=dodelete">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <p>Vil du virkelig slette sangen <i><?php echo $row["title"] ?></i></p>
            <button type="submit">Slet</button>
            <button type="button" onclick="document.location.href='?mode=list'">Annuller</button>
        </form>
	    <?php

	    sysFooter();
        break;

    case "DODELETE":
	    //Henter ID fra GET
	    $id = isset($_POST["id"]) && !empty($_POST["id"]) ? (int)$_POST["id"] : 0;

	    if($id) {
	        $sql = "DELETE FROM song WHERE id = :id";
	        $stmt = $db->prepare($sql);
	        $stmt->bindParam(":id", $id);
	        $stmt->execute();
	        header("Location: ?mode=list");
        }


        break;
}