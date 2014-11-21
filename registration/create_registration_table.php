<?php
require_once("../inc/config.php");
require_once("../inc/connect.php");
$table_creation = "CREATE TABLE IF NOT EXISTS `".$db_table."` (";
$table_creation .="`index` int NOT NULL AUTO_INCREMENT,";
$table_creation .="`title` varchar(3) NOT NULL DEFAULT 'm',";
$table_creation .="`fname` varchar(50) NOT NULL,";
$table_creation .="`lname` varchar(40) NOT NULL,";
$table_creation .="`affiliation` varchar(75) NOT NULL,";
$table_creation .="`address` varchar(75) NOT NULL,";
$table_creation .="`address2` varchar(75) NOT NULL,";
$table_creation .="`zip` varchar(10) NOT NULL,";
$table_creation .="`city` varchar(40) NOT NULL,";
$table_creation .="`country` varchar(50) NOT NULL,";
$table_creation .="`email` varchar(50) NOT NULL,";
$table_creation .="`phone` varchar(25),";
$table_creation .="`fax` varchar(25),";
$table_creation .="`status` varchar(10) NOT NULL,";
$table_creation .="`nb_acc` tinyint(2) DEFAULT 0,";
$table_creation .="`gala` tinyint(1) DEFAULT 0,";
for ($i=0; $i<sizeof($lunchs); $i++) {
  $table_creation .="`".$lunchs_bd_names[$i]."` int DEFAULT '0',";
}
$table_creation .="`activity1` varchar(50) DEFAULT '0',";
$table_creation .="`activity2` varchar(50) DEFAULT '0',";
$table_creation .="`fees` int NOT NULL,";
for ($i=0; $i<sizeof($add_labels); $i++) {
	$table_creation .="`".$add_labels[$i]."` varchar(3) DEFAULT 'no',";
}
$table_creation .="`payment` varchar(10) NOT NULL,";
$table_creation .="`ref` varchar(40) NOT NULL,";
$table_creation .="`lang` varchar(2) DEFAULT 'fr',";
$table_creation .="`res` tinyint(1) DEFAULT 0,";
$table_creation .="`date_first` varchar(16),";
$table_creation .="`date_modif` varchar(16),";
$table_creation .="PRIMARY KEY  (`index`)";
$table_creation .=") ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());
mysql_query($table_creation);
echo "Table ".$db_table." cr&eacute;&eacute;e !";
mysql_close();
?>