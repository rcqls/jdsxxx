<html dir="ltr" lang="fr-FR">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Nathalie Villa-Vialaneix">
<?php
	// database connexion
	require_once("../inc/config.php");
	require_once("../inc/connect.php");
	echo "<title>Administration | ".$eventname."</title>";
?>
	<link rel="shortcut icon" href="../inc/img/favicon.ico">

	<!-- Bootstrap core CSS -->
	<link href="../inc/css/bootstrap.css" rel="stylesheet">
    
	<!--mathjax-->
	<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
</head>

<body>
	<div class="container">
		<div class="well">
			<div class="media">
				<a class="pull-left" href="#">
					<img class="media-object" src="../inc/img/sfds.png" alt="SFdS" width="96">
				</a>
				<div class="media-body">
	<?php
					echo "<h2>Administration des inscrits pour la manifestation ".$eventname."</h2>
					Retour sur le <a href='".$website_url."'>site principal de la manifestation</a>.";
	?>
				</div> <!-- media-body -->
			</div> <!-- media -->
		</div> <!-- well -->

<?php
// validation d'un paiement
$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());
if(isset($_POST['res'])) {
	if(isset($_POST['NameConfirm'])) $type="name"; else $type="ref";

	if ($type=="ref") {
		$ref=$_POST['ref'];
	} else {
		$ref=$_POST['thename'];
	}
	$qselected = "SELECT * FROM `".$db_table."` WHERE `ref`='".$ref."'";
	$selected = mysql_query($qselected);
	$selected = mysql_fetch_object($selected);
	$query="UPDATE `".$db_table."` SET `res`='1' WHERE `ref`='".$ref."'";
	mysql_query($query);
	echo "Paiment validé pour la référence ".$ref." (".$selected->fname." ".$selected->lname.").";
}

$all = "SELECT * FROM `".$db_table."` WHERE res!=1 ORDER BY lname";
$res = mysql_query($all);

echo "<h2>Valider par nom</h2>";
echo "<form method='POST' action='".$url_validpaiment."'>
<select name='thename'>";
while ($a_name = mysql_fetch_object($res)) {
	echo "<option value='".$a_name->ref."'>".$a_name->lname." ".$a_name->fname."</option>";
}
echo "</select>
<input type='hidden' name='res' value='1'>
<input type='submit' name='NameConfirm' value='Confirmer le paiement' onClick='' />";

$all = "SELECT * FROM `".$db_table."` WHERE res!=1";
$res = mysql_query($all);

echo "<h2>Valider par référence de paiement</h2>";
echo "<select name='ref' size='1'>";
while ($a_name = mysql_fetch_object($res)) {
	echo "<option>".$a_name->ref."</option>";
}
echo "</select>
<input type='hidden' name='res' value='1'>
<input type='submit' name='RefConfirm' value='Confirmer le paiement' onClick='' />
</form>";
mysql_close();

// liste des inscrits

echo "<h2>Fichier des inscrits (format CSV)</h2>";

$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());

$outputCsv = '';

// Nom du fichier final
$now = 	date("Ymd");
$fileName = '../inscrits/inscrits_'.$now.'.csv';

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

	echo '<a href="'.$fileName.'">Télécharger le fichier.</a>';
?>
		</div> <!-- container -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../inc/js/bootstrap.js"></script>
	</body>
</html>