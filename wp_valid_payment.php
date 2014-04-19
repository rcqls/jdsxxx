[php]
// Load configuration file
require_once('payment/config.php');
require_once('payment/connect.php');

if(isset($_POST['res'])) {
	$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
	mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());
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
} else {
	$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
	mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());
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
}
[/php]