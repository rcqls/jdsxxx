<html dir="ltr" lang="fr-FR">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Nathalie Villa-Vialaneix">
		<meta name="description" content="Programme JdS 2014">
		<title>Programme | 46èmes Journées de Statistique</title>
    <link rel="shortcut icon" href="img/favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <!--mathjax-->
    <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
  </head>
<body>

<div class="container">

<h1>Programme détaillé des 46èmes Journées de Statistique de la SFdS</h1>

<div class="well">
	<div class="media">
  <a class="pull-left" href="#">
    <img class="media-object" src="img/sfds.png" alt="SFdS" width="96">
  </a>
  <div class="media-body">
Le programme détaillé par jour des JdS 2014 est disponible ici. Vous avez également accès aux résumés courts et longs (à partir de la date limite de soumission des versions révisées). Les résumés des conférenciers invités sont disponibles à <a href="http://jds2014.sfds.asso.fr/programme/conferenciers-invites/">cette page</a>. Le programme complet au format PDF est disponible <a href="http://jds2014.sfds.asso.fr/wp-content/uploads/2014/05/brochure_jds2014.pdf">ici</a>.<br><br>
Retour sur le site principal des <a href="http://jds2014.sfds.asso.fr">Journées de Statistique 2014</a>
	</div>
</div>
</div>

<?php
// functions to deal with the date in English
function day_in_french($the_day) {
  $all_days = array("Monday" => "Lundi","Tuesday" => "Mardi","Wednesday" => "Mercredi","Thursday" => "Jeudi","Friday" => "Vendredi","Saturday" => "Samedi");
  return $all_days[$the_day];
}
function month_in_french($the_month) {
  $all_months = array("April" => "avril","May" => "mai","June" => "juin");
  return $all_months[$the_month];
}

require_once("/homez.353/sfdsmqrk/jds2014/payment/config.php");
require_once("/homez.353/sfdsmqrk/jds2014/payment/connect.php");
$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());

$url_showabstract=$website_url."/prog/showabstract.php";
if(isset($_POST['day'])) $sel_day=$_POST['day']; else $sel_day=0;

$dayquery = 'SELECT DISTINCT slot_date FROM `Slot` ORDER BY slot_date ASC';
$dayres = mysql_query($dayquery) or die('Erreur SQL !'.$dayquery.'</br>'.mysql_error());
$dayres2 = mysql_query($dayquery) or die('Erreur SQL !'.$dayquery.'</br>'.mysql_error());

echo '<ul class="pagination">';
while ($the_day=mysql_fetch_array($dayres2)) {
	$sel_day=$the_day['slot_date'];
	echo '<li><a href="#'.$sel_day.'">'.day_in_french(date('l',strtotime($sel_day))).'</a></li>';
}
echo '</ul>';

while ($the_day=mysql_fetch_array($dayres)) {
	$sel_day=$the_day['slot_date'];
  $slotquery = 'SELECT C.id, C.slot_date, C.begin, C.end FROM `Slot` C' . ' WHERE C.slot_date="' . $sel_day . '" ORDER BY C.begin ASC';
  $slotres = mysql_query($slotquery) or die('Erreur SQL !'.$slotquery.'</br>'.mysql_error());
  echo "<a name='".$sel_day."'></a><center><h2>Programme du ".day_in_french(date('l',strtotime($sel_day)))." ".date('d',strtotime($sel_day))." ".month_in_french(date('F',strtotime($sel_day)))."</h2></center>";

if (mysql_num_rows($slotres)) {
while ($the_slot=mysql_fetch_array($slotres)) {
  $slotid = $the_slot['id'];
  echo "<h4>".date('H',strtotime($the_slot['begin']))."h".date('i',strtotime($the_slot['begin']))."-"
  .date('H',strtotime($the_slot['end']))."h".date('i',strtotime($the_slot['end']))."</h4>";

  $sessionquery= 'SELECT S.name, S.room, S.chair, S.id FROM `ConfSession` S' . ' WHERE S.id_slot=' . $slotid . ' ORDER BY S.room ASC';
  $sessionres=mysql_query($sessionquery) or die('Erreur SQL !'.$sessionquery.'</br>'.mysql_error());

  if (mysql_num_rows($sessionres)) {
  while ($the_session=mysql_fetch_array($sessionres)) {
    $sessionid = $the_session['id'];
    echo "<center><b>".utf8_encode($the_session['name'])."</b> (".utf8_encode($the_session['room']).")<br />";
    echo "Modérateur : <em>".utf8_encode($the_session['chair'])."</em></center>";
  
    $papersquery = 'SELECT P.id, P.title, P.isUploaded FROM `Paper` P' . ' WHERE P.id_conf_session=' . $sessionid . ' ORDER BY P.position_in_session ASC';
    $papersres = mysql_query($papersquery) or die('Erreur SQL !'.$papersquery.'</br>'.mysql_error());
    $nb_paper = mysql_num_rows($papersres);
    $the_slot['begin'];
    $currentime = strtotime($the_slot['begin']);
    if ($nb_paper>0) {
      $duration = (date('H',strtotime($the_slot['end']))*60-date('H',$currentime)*60+date('i',strtotime($the_slot['end']))-date('i',$currentime))/$nb_paper;
    } else $duration=0;
    $curnb = $nb_paper;

    if (mysql_num_rows($papersres)) {
    while ($the_paper=mysql_fetch_array($papersres)) {
      if ($curnb==$nb_paper) {
        echo "<font size=2>";
      }
      $paperid = $the_paper['id'];
      echo "<em>".date('H',$currentime)."h".date("i",$currentime)."</em> : <b>".utf8_encode($the_paper['title'])."</b><br/>";
      $currentime = $currentime+$duration*60;
    
      $authorquery = 'SELECT U.last_name, U.first_name, U.email, U.affiliation, A.position, P.emailContact '
                     . ' FROM `Paper` P, `Author` A, `User` U '
                     . ' WHERE P.id=A.id_paper AND A.id_user=U.id AND P.id=' . $paperid
                     . ' ORDER BY A.position ASC';
      $authorres = mysql_query($authorquery) or die('Erreur SQL ! '.$authorquery.'<br/>'.mysql_error());

      $nb_aut = mysql_num_rows($authorres);
      while ($the_author = mysql_fetch_array($authorres)) {
        if ($the_author['email']===$the_author['emailContact']) echo "<b>";
        echo utf8_encode($the_author['first_name'])." ".utf8_encode($the_author['last_name']);
        if ($the_author['email']===$the_author['emailContact']) echo "</b>";
        echo " (".utf8_encode($the_author['affiliation']).")";
        $nb_aut = $nb_aut-1;
        if ($nb_aut==0) {
          echo "<br/>";
        } else {
          echo " ; ";
        }
      }
      if (time() >= strtotime($deadline_papers)) {
        if ($the_paper['isUploaded']=='Y') {
          $filepath = $url_papers."submission_".$paperid.".pdf";
          echo "<a href='".$filepath."' target='_blank'>Résumé long au format PDF</a> - ";
        }
        $abstractpath = $url_showabstract."?id=".$paperid;
        echo "<a href='".$abstractpath."' target='_blank'>Résumé court</a><br />";
      }
      $curnb=$curnb-1;
      if ($curnb==0) {
        echo "</font>";
      }
    }}
  }}
}}}
mysql_close();
?>
</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
</body>
</html>