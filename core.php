<?php
// Core!
function standings($league, $jornada) {
	$return = array(
		"name" => "UNKNOWN",
		"jornada" => 0,
		"standings" => array()
	);

	if ($jornada == 0) {
		$league .= "";
	} else {
		$league .= "/".$jornada;
	}

	$html = file_get_contents("http://basquetcatala.cat/competicions/resultats/".$league);

	// a new dom object
	$dom = new domDocument; 

	libxml_use_internal_errors(true);

	// load the html into the object
	$dom->loadHTML($html); 
	 
	// discard white space
	$dom->preserveWhiteSpace = false;

	$return["name"] = str_replace("Competició: ", "", trim($dom->getElementById("continguts")->getElementsByTagName("strong")[0]->textContent));

	$classificacio = $dom->getElementById("classificacio");

	if ($classificacio == null && $jornada == 0) {
		$jornada = (int)str_replace(array("Jornada ", " (Última jornada juagada)"), array("", ""), $dom->getElementById("resultats")->getElementsByTagName("th")[0]->textContent) - 1;

		$html = file_get_contents("http://basquetcatala.cat/competicions/resultats/".$league."/".$jornada);

		// a new dom object
		$dom = new domDocument; 

		libxml_use_internal_errors(true);

		// load the html into the object
		$dom->loadHTML($html); 
		 
		// discard white space
		$dom->preserveWhiteSpace = false;

		$classificacio = $dom->getElementById("classificacio");
	} elseif ($jornada != 0) {
		return -1;
	}

	$return["jornada"] = (int)str_replace(array("Jornada ", " (Última jornada juagada)"), array("", ""), $dom->getElementById("resultats")->getElementsByTagName("th")[0]->textContent);

	$equips = $classificacio->getElementsByTagName("tr");
	
	for ($i = 2; $i < $equips->length; $i++) {
		$valors = $equips[$i]->getElementsByTagName("td");
		$return["standings"][] = array(
			"posicio" => $valors[0]->textContent,
			"equip" => $valors[1]->textContent,
			"j" => $valors[2]->textContent,
			"g" => $valors[3]->textContent,
			"p" => $valors[4]->textContent,
			"np" => $valors[5]->textContent,
			"tf" => $valors[6]->textContent,
			"tc" => $valors[7]->textContent,
			"pts" => $valors[8]->textContent,
			"league" => $league,
			"leaguename" => $return["name"],
			"posicioleague" => $valors[0]->textContent
		);
	}

	return $return;
}

function printst($standings, $jornada, $join=FALSE) {
?>
	<h2><?=$standings["name"]?></h2>
	<table>
		<thead>
			<tr>
				<th colspan="9" scope="col">
				<?php if ($standings["jornada"] == 0) {
				?>
				Última jornada jugada
				<?php
				} else {
				?>Jornada <?=$standings["jornada"]?><?php if ($jornada == $standings["jornada"] || $jornada == 0) { echo " (última jornada jugada)"; } ?></th>
				<?php
				}
				?>
			</tr>
			<tr>
				<th></th>
				<th>Equip</th>
				<?php
				if ($join == true) {
				?>
				<th>Lliga</th>
				<?php
				}
				?>
				<th>J</th>
				<th>G</th>
				<th>P</th>
				<th>NP</th>
				<th>TF</th>
				<th>TC</th>
				<th>Pts</th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach ($standings["standings"] as $standing) {
			?>
			<tr>
				<td><?=$standing["posicio"]?></td>
				<td><?=$standing["equip"]?></td>
				<?php
				if ($join == true) {
				?>
				<td><a href="http://basquetcatala.cat/competicions/resultats/<?=$standing["league"]?>">#<?=$standing["posicioleague"]?> en <?=explode(" - ", $standing["leaguename"])[1]?></a></td>
				<?php
				}
				?>
				<td><?=$standing["j"]?></td>
				<td><?=$standing["g"]?></td>
				<td><?=$standing["p"]?></td>
				<td><?=$standing["np"]?></td>
				<td><?=$standing["tf"]?></td>
				<td><?=$standing["tc"]?></td>
				<td><?=$standing["pts"]?></td>
			</tr>
			<?php
		}
	?>
		</tbody>
	</table>
<?php
}
?>