<!--
-------------------------------------------------------------
"page autoresponse"
(vous devez transmettre à la SFdS l'url de cette page)

Cette page n'est jamais affichée mais le résultat de l'opération de paiement est toujours transmis à cette page par le site de la SFdS
(même si l'utilisateur ne suit pas les liens pour revenir sur votre site après avoir effectué le paiement).

2 paramètres sont transmis à cette page par le site de la SFdS :
+ ref : la référence de la commande
+ res : 0/1, 0 si le paiement a échoué, 1 s'il a réussi
-------------------------------------------------------------
-->

<?php 
require_once('../inc/config.php');
require_once('../inc/connect.php');

// Test what is coming back from sg website
if (isset($_REQUEST['ref']) && isset($_REQUEST['res']) && (($_SERVER['REMOTE_ADDR']=="5.39.72.221")||($_SERVER['REMOTE_ADDR']=="178.32.97.133" ))) {

// Print "OK": This is the answer waited by the sogenactif form
// You must keep this because, otherwise, the sogenactif form repeats the script each 2 minutes or so during an hour... 
	print("OK");
	$ref = $_REQUEST['ref'];
	$res = $_REQUEST['res'];
	$now = date("Y/m/d H:i:s");

	$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
	mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());
	$current_user = "SELECT * FROM `".$db_table."` WHERE `ref`='".$ref."'";
	$user_res = mysql_query($current_user);
	$user_res = mysql_fetch_object($user_res);

	$destinataire = $user_res->email.", " . $email_inscription;
	$sujet = $subject_email_cb." ".$user_res->lname.", ".$user_res->fname;
	$entete = "From: " . $contact_email . "\n";
	$entete .= "Reply-to: " . $contact_email . "\n";
	$entete .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
	if($user_res->title==="mr"){
		$texte = "M. ".$user_res->fname." ".$user_res->lname.",\n\n";
	}elseif($user_res->title==="mme"){
		$texte = "Mme ".$user_res->fname." ".$user_res->lname.",\n\n";
	}

// Write it in the log file...
// Open log file (append mode)
	$fp=fopen("./sogenactif.log", "a");
	if ($res==1) {
		fwrite($fp,"$now : (".$_SERVER['REMOTE_ADDR'].") ".$_REQUEST['ref']." : paiement valide\n");
		$update_query = "UPDATE `".$db_table."` SET res='1' WHERE `".$db_table."`.`index`='".$user_res->index."'";
		mysql_query($update_query) or die('Erreur SQL ! '.$sql.'<br/>'.mysql_error());
		$texte .= "Votre paiement carte bancaire d'un montant de ".$user_res->fees." euros a été effectué correctement.\n\n";
		$texte .= "Le comité d'organisation";
	} else {
		fwrite($fp,"$now : (".$_SERVER['REMOTE_ADDR'].") ".$_REQUEST['ref']." : paiement refuse\n");
		$texte .= "Votre paiement carte bancaire n'a pas abouti : veuillez effectuer à nouveau votre inscription.\n\n";
		$texte .= "Le comité d'organisation";
	}
	fclose ($fp);
	mysql_close();

// Send an email
mail($user_res->email,'=?UTF-8?B?'.base64_encode($sujet).'?=',$texte,$entete);
}
?>