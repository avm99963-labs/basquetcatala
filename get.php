<?php
require_once("core.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Bàsquet Català – League's classification</title>
	</head>
	<body>
		<?php
		if (isset($_GET["league"]) && !empty($_GET["league"])) {
			if (!isset($_GET["jornada"])) {
				$jornada = 0;
			} else {
				$jornada = (int)$_GET["jornada"];
			}
			$league = $_GET["league"];

			$standings = standings($league, $jornada);

			if ($standings == -1) {
				die("<p>That <i>jornada</i> doesn't have any standings associated with it.</p>");
			} else {
				printst($standings, $jornada);
			}
		} else {
		?>
		<h2>League's classification</h2>
		<form action="get.php" method="GET">
			<p>League: <input type="text" name="league" required></textarea></p>
			<p>Jornada: <input type="number" name="jornada" min="0" step="1" required> <span style="color: gray;">(enter 0 if you want to get the last one)</span></p>
			<p><input type="submit" value="Submit"></p>
		</form>
		<?php
		}
		?>
	</body>
</html>