 
<?php
$saludo = "Holakease";
$usuario = "rusben";
?>

 <p>
 <?php echo $saludo ?>
 </p>
 
 <form method="post">
  <label for="rows">Filas:</label><br>
  <input type="text" id="rows" name="rows"><br>
  <label for="cols">Columnas:</label><br>
  <input type="text" id="cols" name="cols"><br>
  <label for="letters">Letras:</label><br>
  <input type="text" id="letters" name="letters"><br>
  <input type="submit" value="Enviar">
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["rows"]) && isset($_POST["cols"])) {
  		$rows = htmlspecialchars($_POST["rows"]);
		$cols = htmlspecialchars($_POST["cols"]);

		$letters = 7;
		if (isset($_POST["letters"])) {
			$letters = $_POST["letters"];
		} 

		echo '<table border="1">';
		for ($i = 0; $i < $rows; $i++) {
			echo "<tr>";
			for ($j = 0; $j < $cols; $j++) {
				echo "<td>". generate_word($letters) ."</td>";	
			}
			echo "</tr>";
		}
		echo "</table>";

	}
}

function generate_word($num) {
	$alphabet = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$max = sizeof($alphabet) - 1;
	$word = "";
	$letter = "";
	for ($i = 0; $i < $num; $i++) {
		$letter = $alphabet[rand(0, $max)];
		$word .= $letter;
	}

	return $word;
}

?>

<h1>Puig Castellar</h1>

<h3>Hola, <?php echo $usuario ?></h3>
