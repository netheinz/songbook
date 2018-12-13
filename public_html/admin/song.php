<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/assets/incl/init.php";

$mode = isset($_GET["mode"]) && !empty($_GET["mode"]) ? $_GET["mode"] : "list";

$page_title = "Sange";

switch(strtoupper($mode)) {
	default:
	case "LIST":

		$arr_buttons = [
			getButton("Opret ny", "?mode=edit"),
		];

		sysHeader();

		echo getAdminHeader($page_title, "Oversigt", $arr_buttons);

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
		       "ORDER BY song.title";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$accHtml = "<div class='row'>";
		foreach($row as $rowData) {
			$accHtml .= "<div>" .
							"<a href=\"?mode=edit&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-pencil-alt\" title=\"Rediger\"></i></a>\n" .
							"<a href=\"?mode=details&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-eye\" title=\"Se detaljer\"></i></a>\n" .
							"<a href=\"?mode=delete&id=".$rowData["id"]."\">" .
                                "<i class=\"fas fa-trash-alt\" title=\"Slet\"></i></a>\n" .
						"</div>";
			$accHtml .= "<div>" . $rowData["song"] . "</div>\n";
			$accHtml .= "<div>" . $rowData["album"] . "</div>\n";
			$accHtml .= "<div>" . $rowData["artist"] . "</div>\n";

		}
		$accHtml .= "</div>";

		echo $accHtml;

		sysFooter();
		break;

    case "DETAILS":
        var_dump($_GET);
        echo $id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;

	    $arr_buttons = [
		    getButton("Oversigt", "?mode=list"),
		    getButton("Opret ny", "?mode=create")
	    ];

	    sysHeader();

	    echo getAdminHeader($page_title, "Se detaljer", $arr_buttons);

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
	           "WHERE song.id = :id";

        $stmt = $db->prepare( $sql );
        $stmt->bindParam( ":id", $id );
        $stmt->execute();
        $row = $stmt->fetch( PDO::FETCH_ASSOC);

        if($row) {
	        $accHtml = "";

	        foreach ( $row as $key => $value ) {
		        $accHtml .= "<li>" . $key . " - " . $value . "</li>\n";
	        }
	        echo $accHtml;
        }
	    sysFooter();
        break;

	case "EDIT":
		$id = isset($_GET["id"]) && !empty($_GET["id"]) ? (int)$_GET["id"] : 0;

		$arr_buttons = [
			getButton("Oversigt", "song.php"),
		];

		sysHeader();

		echo getAdminHeader($page_title, "Opret ny sang", $arr_buttons);

		?>
        <form method="post" action="?mode=save">
            <fieldset>
                <div>
                    <label for="title">Titel:</label>
                    <input name="title" id="title" placeholder="Indtast titel">
                </div>
                <div>
                    <label for="content">Tekst:</label>
                    <textarea name="content" id="content" placeholder="Indtast titel"></textarea>
                </div>
                <button type="submit">Send</button>
                <button type="reset">Nulstil </button>
            </fieldset>
        </form>
		<?php

		sysFooter();
		break;

    case "SAVE":
        echo "Hej hej";
        var_dump($_POST);
        break;
}