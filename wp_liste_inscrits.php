[php]
require_once("payment/connect.php");
require_once("payment/config.php");

$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());

$outputCsv = '';

// Nom du fichier final
$now = 	date("Ymd");
$fileName = 'inscrits/inscrits_'.$now.'.csv';

$requete = "SELECT * FROM `".$db_table."` ORDER BY date_modif";
$res = mysql_query($requete);
if(mysql_num_rows($res) > 0) {
	$i = 0;
	while($Row = mysql_fetch_assoc($res)) {
		$i++;
		if($i == 1) {
		foreach($Row as $clef => $valeur)
			$outputCsv .= trim($clef).';';
			$outputCsv = rtrim($outputCsv, ';');
			$outputCsv .= "\n";
		}
		foreach($Row as $clef => $valeur)
		$outputCsv .= trim($valeur).';';
		$outputCsv = rtrim($outputCsv, ';');
		$outputCsv .= "\n";
	}
	$fp=fopen($fileName, "w");
	fwrite($fp,$outputCsv);
	fclose ($fp);
} else
	exit('Aucun inscrit.');

	echo '<a href="'.$website_url.'/'.$fileName.'">Télécharger le fichier.</a>';
[/php]