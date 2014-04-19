<?php

require_once("../payment/config.php");
require_once("../payment/connect.php");

function printsummary($paperid){
// Connection to db: note that table must be identical to user
$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
mysql_select_db($db_user,$db) or die('Erreur de selection '.mysql_error());

$sql = 'SELECT P.title, A.content , P.emailContact, U.affiliation '
        . ' FROM `Abstract` A, `Paper` P, `User` U '
        . ' WHERE A.id_paper=P.id AND P.emailContact=U.email AND A.id_paper=' . $paperid;
$result=mysql_query($sql) or die('Erreur SQL ! '.$sql.'<br/>'.mysql_error());

$sql2= 'SELECT U.last_name, U.first_name, U.email, U.affiliation, A.position '
				. ' FROM `Paper` P, `Author` A, `User` U '
        . ' WHERE P.id=A.id_paper AND A.id_user=U.id AND P.id=' . $paperid
        . ' ORDER BY A.position ASC';
$result2=mysql_query($sql2) or die('Erreur SQL ! '.$sql2.'<br/>'.mysql_error());
mysql_close();

echo "<hr/>Résumé ".$paperid." :<br/><br/>";
if(mysql_num_rows($result)){
	while ($row = mysql_fetch_array($result)){
		echo "<b>".$row['title']."</b><br/>";
		$nb_aut = mysql_num_rows($result2);
		while ($row2 = mysql_fetch_array($result2)){
			if ($row2['email']===$row['emailContact']) echo "<u>";
			echo $row2['last_name'].", ".$row2['first_name'];
			if ($row2['email']===$row['emailContact']) echo "</u>";
			$nb_aut = $nb_aut-1;
			if ($nb_aut==0)
				echo " <br/><em>".$row['affiliation']."</em><br/>";
			else
				echo " ; ";
		}
		echo "<br/>".htmlspecialchars_decode($row['content'])."<br/>";
	}
	$filepath = $url_papers."submission_".$paperid.".pdf";
	if (file_exists($filepath)){
		echo "<br/><center><a href='".$filepath."' target='_blank'>Résumé long au format PDF</a></center>";
	}
	echo "<hr/>";
}
else
	echo "No such entry in database<br/><hr/>";
}

$id=$_GET["id"];
printsummary($id);

?>