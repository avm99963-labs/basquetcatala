<?php
require_once("core.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Bàsquet Català – Join several leagues</title>
	</head>
	<body>
		<?php
		if (isset($_GET["leagues"]) && !empty($_GET["leagues"])) {
			$leagues = explode(",", str_replace(" ", "", $_GET["leagues"]));
			$standings = array();
			foreach ($leagues as $league) {
				if ($standings == -1) {
					die("<p>An error ocurred.</p>");
				} else {
					$standings[] = standings($league, 0);
				}
			}

			$standing = array(
				"name" => "Join ".$_GET["leagues"],
				"jornada" => "0",
				"standings" => array()
			);
			foreach ($standings as $singlestanding) {
				foreach ($singlestanding["standings"] as $club) {
					unset($club["posicio"]);
					$standing["standings"][] = $club;
				}
			}

			usort($standing["standings"], function($a, $b) {
				if ($a["pts"] == $b["pts"]) {
					return ($b["tf"] - $b["tc"]) - ($a["tf"] - $a["tc"]);
				} else {
					return $b["pts"] - $a["pts"];
				}
			});

			foreach ($standing["standings"] as $i => &$standingsingle) {
				$standingsingle["posicio"] = $i + 1;
			}

			printst($standing, 0, true);
		} else {
		?>
		<h2>Join several leagues</h2>
		<form action="join.php" method="GET">
			<p>Leagues:</p>
			<p><textarea name="leagues" required style="width: 500px; height: 50px;"></textarea></p>
			<p><input type="submit" value="Submit"></p>
		</form>
		<?php
		}
		?>
	</body>
</html>