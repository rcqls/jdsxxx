<?php
// make html invoice
ob_start();
?>
<page>
<div style="text-align:center; width: 150 mm;">
<img style="width: 116px; height: 116px;" alt="SFdS" src="<?php print dirname(__FILE__); ?>/sfds.png"><br>
<h1><span style="font-weight: bold;"><?php echo $eventname; ?></span></h1>
<br>
<br>
<div style="text-align: right; width:50%;"><?php echo $place; ?>, le <?php echo date('d/m/Y'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<br>
</div>
<br>
<table style="width:100%;" border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="text-align: left;vertical-align: top; width:50%;">
      <div style="text-align: center;">
      <h3>Service &eacute;metteur</h3>
<?php print $emetteur; ?><br>
<?php print $em_adr1; ?><br>
<?php print $em_adr2; ?><br>
<?php print $em_adr3; ?><br>
      </div>
<br>
      <div style="text-align: left;"><span style="font-weight: bold; ">SIRET</span> : <?php print $siret; ?><br>
Tr&eacute;sorier : <?php print $tresorier; ?><br>
T&eacute;l/Fax : <?php print $telfax; ?><br>
Courriel : <?php print $tres_email; ?><br>
      </div>
      </td>
      <td style="vertical-align: top; text-align: center; width:50%;">
      <h3>Nom et adresse du client</h3>
<?php print $fname; ?>&nbsp;<?php print $lname; ?><br>
<?php print $affiliation; ?><br>
<?php print $address; ?><br>
<?php print $address2; ?><br>
<?php print $zip; ?>&nbsp;<?php print $city; ?><br>
<?php print $country; ?><br>
      <br>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table style="width: 100%;" border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: left; width: 50%;">
      <div style="font-weight: bold; text-align:center; font-size:14pt;">Num&eacute;ro de facture<br><?php print $ref; ?></div>
      </td>
    </tr>
  </tbody>
</table><br>
<table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: center; font-weight: bold; width: 65%;">D&eacute;signation de la prestation<br>
      </td>
      <td style="vertical-align: top; text-align: center; font-weight: bold; width: 10%;">Quantit&eacute;<br>
      </td>
      <td style="vertical-align: top; text-align: center; font-weight: bold; width: 10%;">Prix unitaire<br>
      </td>
      <td style="vertical-align: top; text-align: center; font-weight: bold; width: 15%;">Total<br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Inscription <?php print $eventname; ?><br>
<?php
 if ($status==$guest_status) {
  print "Exon&eacute;r&eacute; (gratuit)";
} else {
  for ($i=0; $i<sizeof($code_status); $i++) {
    if ($i==$status) {
      print $text_status[$i];
    }
  }
}
?><br>
      <br>
Accompagnant(s)<br>
      <br>
D&eacute;jeuner(s)<br>
<?php if ($lunch_nb>0) {
  print "(";
  for ($i=0; $i < sizeof($lunchs_bd_names); $i++) {
    if ($want_lunch[$i]==1) echo $lunchs[$i]." ";
  }
  print ")";
}
?><br>
Repas de gala<br>
      </td>
      <td style="vertical-align: top; text-align: center;">1<br>
      <br>
      <br>
<?php print $nb_acc; ?><br>
      <br>
<?php print $lunch_nb; ?><br>
      <br>
<?php if ($gala==1) print "1"; else print "0";?><br>
      </td>
      <td style="vertical-align: top; text-align: center;"><?php print $rgl_insc; ?> &#8364;<br>
      <br>
      <br>
<?php print $acc_price; ?> &#8364;<br>
      <br>
<?php if ($rgl_insc!=0) {
  print $lunch_price;
} else print $lunch_exo;?> &#8364;<br>
      <br>
<?php if ($rgl_insc!=0) {
  print $gala_diner_price;
} else print $gala_exo;?> &#8364;<br>
      <br>
      </td>
      <td style="vertical-align: top; text-align: center;"><?php print $rgl_insc; ?> &#8364;<br>
      <br>
      <br>
<?php print $rgl_acc; ?> &#8364;<br>
      <br>
<?php print $rgl_midi; ?> &#8364;<br>
      <br>
<?php print $rgl_gala; ?> &#8364;<br>
      <br>
      </td>
    </tr>
  </tbody>
</table>
<div style="text-align: right;"><span style="font-style: italic;"><span style="font-weight: bold;">TOTAL : <?php print $total; ?></span></span> &#8364;&nbsp;&nbsp;&nbsp;&nbsp;<br>
<span style="font-style: italic;"></span></div>
<span style="font-style: italic;">Exon&eacute;ration de TVA en vertu de l'article 261 du CGI</span><br>
<br>
<table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="vertical-align: top; width: 50%;">
      <h3>R&egrave;glement pour le compte de</h3>
      <span style="font-weight: bold;">Titulaire : <?php print $bankowner; ?></span><br>
<?php print $adr_titulaire; ?><br>
      <span style="font-weight: bold;">Banque : <?php print $the_bank ?></span><br>
Code Banque : <?php print $bank_code; ?><br>
Code Guichet : <?php print $bank_guichet; ?><br>
Num. de compte : <?php print $bankaccount; ?><br>
Cl&eacute; RIB : <?php print $rib; ?><br>
IBAN : <?php print $iban; ?><br>
BIC : <?php print $bic; ?><br>
      <span style="font-weight: bold;">&Agrave; indiquer en r&eacute;f&eacute;rence du paiement : <?php print $ref; ?></span><br>
      </td>
      <td style="vertical-align: center; text-align: center; width: 50%;">
      <h3><?php print $pres_co; ?></h3>
Pr&eacute;sident(e) du comit&eacute; d''organisation<br>
      <br>
      <img style="width: 150px; " alt="" src="<?php print dirname(__FILE__); ?>/signature.jpg"><br>
      </td>
    </tr>
  </tbody>
</table>
</div>
</page>

<?php
$content=ob_get_clean();

// send to html2pdf
require_once('/homez.353/sfdsmqrk/html2pdf/html2pdf.class.php');
try {
  $html2pdf = new HTML2PDF('P', 'A4', 'fr');
  //$html2pdf->pdf->SetDisplayMode('fullpage');
  $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  $html2pdf->Output(dirname(__FILE__).'/docs/facture_'.$ref.'.pdf','F');
}

catch(HTML2PDF_exception $e) {
  echo $e;
  exit;
}
?>