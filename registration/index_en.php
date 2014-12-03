
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
	require_once("../inc/config-en.php");
	echo "<meta name='description' content='Registration ".$eventname."'>";
	echo "<title>Registration | ".$eventname."</title>";
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
					echo "<h2>Registration form for ".$eventname."</h2>
					Back to the <a href='".$website_url."'>conference's main website</a>.";
	?>
				</div> <!-- media-body -->
			</div> <!-- media -->
		</div> <!-- well -->

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

$db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());

// A useful function to check wether a particular value has been selected
function IsChecked($myarray,$value) {
  foreach($myarray as $chkval) {
    if($chkval == $value) {
      return "yes";
    }
  }
  return "no";
}

//Setting up variables for form
//These lines check if the form is complete
$lang='en';
if(isset($_POST['title']))  $title=$_POST['title']; else $title='';
if(isset($_POST['fname']))  $fname=ucwords(strtolower($_POST['fname'])); else $fname='';
if(isset($_POST['lname']))  $lname=ucwords(strtolower($_POST['lname'])); else $lname='';
if(isset($_POST['affiliation']))  $affiliation=str_replace(array("\'","'")," ",$_POST['affiliation']); else $affiliation='';
if(isset($_POST['address']))  $address=str_replace(array("\'","'",";")," ",$_POST['address']); else $address='';
if(isset($_POST['address2']))  $address2=str_replace(array("\'","'",";")," ",$_POST['address2']); else $address2='';
if(isset($_POST['zip']))    $zip=$_POST['zip'];     else $zip='';
if(isset($_POST['city']))   $city =str_replace(array("\'","'",";")," ",$_POST['city']);  else $city='';
if(isset($_POST['country']))  $country=strtoupper($_POST['country']); else $country='';
if(isset($_POST['email']))  $email=$_POST['email']; else $email='';
if(isset($_POST['email2'])) $email2=$_POST['email2']; else $email2='';
if(isset($_POST['phone']))  $phone=$_POST['phone']; else $phone='';
if(isset($_POST['fax']))    $fax  =$_POST['fax'];   else $fax='';
if(isset($_POST['status']))   $status  =$_POST['status'];   else $status=0;
if(isset($_POST['fcode']))     $fcode    =$_POST['fcode'];     else $fcode='';
if(isset($_POST['nbacc']))     $nb_acc    =$_POST['nbacc'];     else $nb_acc=0;
if(isset($_POST['gala']))   $gala =$_POST['gala'];  else $gala=0;
if(isset($_POST['activity1'])) $activity1=$_POST['activity1']; else $activity1='0';
if(isset($_POST['activity2'])) $activity2=$_POST['activity2']; else $activity2='0';
if(isset($_POST['meeting'])) $meeting=$_POST['meeting']; else $meeting='';
if(isset($_POST['atelier'])) $atelier=$_POST['atelier']; else $atelier='';
if(isset($_POST['total'])) $total=$_POST['total']; else $total='';
if(isset($_POST['payment'])) $payment=$_POST['payment']; else $payment='';

//Verifying status of registration:
// - submitted(user has clicked the submit button at least once),
// - verified(user has filled all required fields),
// - confirmed(user has confirmed that the filled contents are correct).
if(isset($_POST['editBtn']))    $edit=true;      else $edit=false;
if(isset($_POST['submitBtn']))  $submitted=true; else $submitted=false;
if(isset($_POST['confirmBtn'])) $confirmed=true; else $confirmed=false;

$guest_status = sizeof($code_status)+1;

if ($submitted) {
  //if form has been submitted but not yet confirmed, verification of entered data
  if (empty($title) or empty($fname) or empty($lname) or empty($affiliation) or empty($address) or empty($zip) or empty($city) or empty($country) or empty($email) or empty($email2)) {
    $verified=false;
    $reason="empty";
  } elseif (!($email===$email2)) {
    $verified=false;
    $reason="email";
  } elseif ($status==$guest_status and $fcode!=$free_code) {
    $verified=false;
    $reason="notfree";
  } else $verified=true;
}

// Initialisation des variables pour le déjeuner
if (isset($_POST['wantlunch'])) {
  $wantlunch=$_POST['wantlunch'];
  $want_lunch=array();
  for ($i=0; $i< sizeof($lunchs); $i++) {
    if (IsChecked($wantlunch,$lunchs_bd_names[$i])=="yes") {
      $want_lunch[$i]=1;
    } else $want_lunch[$i]=0;
  }
} elseif (($submitted) or ($confirmed) or ($edit)) {
  $wantlunchtext=$_POST['wantlunchtext'];
  $wantlunch= explode("-",$wantlunchtext);
  $want_lunch=array();
  for ($i=0; $i< sizeof($lunchs); $i++) {
    if (IsChecked($wantlunch,$lunchs_bd_names[$i])=="yes") {
      $want_lunch[$i]=1;
    } else $want_lunch[$i]=0;
  }
} else {
  $want_lunch=array();
  for ($i=0; $i< sizeof($lunchs); $i++) {
    $want_lunch[$i] = 0;
  }
}

// The following lines initialized the array of open questions; if status is not "confirmed", the array is set empty
if (!$confirmed) {
  $addquestions = array();
} else {if (isset($_POST['addquestions'])) $addquestions=$_POST['addquestions']; else $addquestions=array();}

if (($submitted and $verified) or $confirmed) {
  // Registration is ok: calculate the price to pay
  $db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
  mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());
  $email=mysql_real_escape_string($email);
  $check = "SELECT * FROM `".$db_table."` WHERE `email`='".$email."'";
  $res = mysql_query($check);
  $exist_email = 0;

  if ($one_res=mysql_fetch_object($res)) {
    $reg_day = strtotime($one_res->date_first);
    $exist_email=1;
  } else $reg_day = time();
  
  $rgl_gala=0;
  $lunch_nb=0;
  for ($i=0; $i< sizeof($lunchs); $i++) {
    $lunch_nb = $lunch_nb+$want_lunch[$i];
  }
  if ($status==$guest_status) {
    $rgl_insc=0;
    $rgl_midi=$lunch_nb*$lunch_exo;
    if ($gala==1) {
      $rgl_gala=$gala_exo;
    }
  } else {
    $rgl_midi=$lunch_nb*$lunch_price;
    if ($gala==1) {
      $rgl_gala=$gala_diner_price;
    }
    if ($reg_day < strtotime($deadline_fees)) {
      for ($i=0; $i<sizeof($code_status); $i++) {
        if ($i==$status) {
          $rgl_insc=$fees_before[$i];
        }
      }
    } else {
      for ($i=0; $i<sizeof($code_status); $i++) {
        if ($i==$status) {
          $rgl_insc=$fees_after[$i];
        }
      }
    }
  }
  $rgl_acc=$nb_acc*$acc_price;
  $total=$rgl_insc+$rgl_midi+$rgl_gala+$rgl_acc;
  mysql_close();
}

// FIX IT: Améliorer la forme (tableau ?)

// L'utilisateur a soumis le formulaire mais le contenu était incomplet
if ($submitted and !$verified) {
  // Message d'alerte en haut de l'écran
  if($reason==="empty") {
    echo "<div class='alert alert-danger' role='alert'>Please fill all fields indicated in yellow.</div>";
  } elseif($reason==="email") {
    echo "<div class='alert alert-danger' role='alert'>The two emails must be the same.</div>";
  } elseif ($reason==="notfree") {
    echo "<div class='alert alert-danger' role='alert'>The code for a free registration was not correct.</div>";
  }
}

// If the form was found incomplete or the user chose to edit something or the form still hasn't been confirmed
if (!$confirmed) {
  // Display the form with indications on missing entries and previously entered data if any
  if (!$submitted) {
    echo "<div class='alert alert-info' role='alert'>Your registration can be updated at any moment be filling this form again with the <strong>same email</strong>. The fees corresponding to your first registration will be automatically applied if you modify your registration.".$welcome_message_en."<br>
    <a href='".$current_url."'><img src='../inc/img/flag_fr.png' alt='francais' width='60'>&nbsp; Registration form in French</a></div>";
  }
  if ($verified) {
    if ($exist_email) echo "Your email is already registered in our database. Your registration is about to be <strong>modified</strong>. If you intended to make a new registration, please, provide another email.";
  }
  echo '<form role="form" class="form-inline" name="main" action="'. $current_url_en .'" method="post">';
  
  echo "<h3>Contact information</h3>";
  // Civilité
  if($verified) {
    if ($title==="mr") echo "Mr.&nbsp;<input type='hidden' name='title' value='mr'/>";
    elseif ($title==="mme") echo "Ms.&nbsp;<input type='hidden' name='title' value='mme'/>";
  } else {
		echo '<div class="form-group">';
			echo '<label for="title" class="col-sm-4 control-label">Title* &nbsp;</label>';
			echo '<div class="col-sm-4">';
				echo "<select class='form-control' name='title'>";
					echo "<option value='mr'";
					if ($title==="mr") echo "selected";
					echo ">Mr.</option>";
					echo "<option value='mme'";
					if($title==="mme") echo "selected";
					echo ">Ms.</option>";
				echo "</select>";
			echo "</div>";
		echo '</div>';
  }
  
  // Nom et prénom
  if ($verified) {
    echo $fname."&nbsp;<input type='hidden' name='fname' style='font-size:small' value='".$fname."'/>";
  } else {
		echo '<div class="form-group">';
			if (empty($fname) & $submitted) echo "&nbsp;<div class='col-sm-2'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="fname" class="col-sm-2">First name* &nbsp;</label>';
			echo '<div class="col-sm-6">';
				echo '<input class="form-control" name="fname" placeholder="First name" size="50" type="text" value="'.$fname.'">';
			echo '</div>';
    echo '</div>';
  }

  if ($verified) {
    echo $lname."&nbsp;<input type='hidden' name='lname' style='font-size:small' value='".$lname."'/><br />";
  } else {
		echo '<div class="form-group">';
			if (empty($lname) & $submitted) echo "&nbsp;<div class='col-sm-2'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="lname" class="col-sm-2">Last name* &nbsp;</label>';
			echo '<div class="col-sm-6">';
				echo '<input class="form-control" name="lname" placeholder="Last name" size="50" type="text" value="'.$lname.'">';
			echo '</div>';
    echo '</div>';
  }
  
  // Affiliation
  if($verified) {
    echo $affiliation."<input type='hidden' name='affiliation' style='font-size:small' value='".$affiliation."'/><br />";
  } else {
  	echo '<div class="form-group">';
			if (empty($affiliation) & $submitted) echo "&nbsp;<div class='col-sm-1'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="affiliation" class="col-sm-2">Institution* &nbsp;</label>';
			echo '<div class="col-sm-6">';
				echo '<input class="form-control" name="affiliation" placeholder="Institution" size="75" type="text" value="'.$affiliation.'">';
			echo '</div>';
    echo '</div>';
  }
  
  // Adresse
  if ($verified) {
    echo $address."<input type='hidden' name='address' style='font-size:small' value='".$address."'/><br />";
  } else {
    echo '<div class="form-group">';
			if (empty($address) & $submitted) echo "&nbsp;<div class='col-sm-1'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="address" class="col-sm-2">Address* &nbsp;</label>';
			echo '<div class="col-sm-6">';
				echo '<input class="form-control" name="address" placeholder="Address" size="75" type="text" value="'.$address.'">';
			echo '</div>';
    echo '</div>';
  }
  if ($verified) {
    echo $address2."<input type='hidden' style='font-size:small' name='address2' value='".$address2."'/><br />";
  } else {
    echo '<div class="form-group">';
			echo '<label for="address2" class="col-sm-2">Address 2 &nbsp;</label>';
			echo '<div class="col-sm-6">';
				echo '<input class="form-control" name="address2" placeholder="Address (second line)" size="75" type="text" value="'.$address2.'">';
			echo '</div>';
    echo '</div>';
  }

  if ($verified) {
    echo $zip."&nbsp;<input type='hidden' style='font-size:small' name='zip' value='".$zip."'/>";
  } else {
     echo '<div class="form-group">';
     if (empty($zip) & $submitted) echo "&nbsp;<div class='col-sm-3'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="zip" class="col-sm-4">Zip code* &nbsp;</label>';
			echo '<div class="col-sm-4">';
				echo '<input class="form-control" name="zip" placeholder="Zip code" size="10" type="text" value="'.$zip.'">';
			echo '</div>';
    echo '</div>';
  }

  if ($verified) {
    echo $city."<input type='hidden' style='font-size:small' name='city' value='".$city."'/><br />";
  } else {
    echo '<div class="form-group">';
     if (empty($city) & $submitted) echo "&nbsp;<div class='col-sm-2'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="city" class="col-sm-2">City* &nbsp;</label>';
			echo '<div class="col-sm-4">';
				echo '<input class="form-control" name="city" placeholder="City" size="50" type="text" value="'.$city.'">';
			echo '</div>';
    echo '</div>';
  }

  if ($verified) {
    echo $country."<input type='hidden' style='font-size:small' name='country' value='".$country."'/><br />";
  } else {
		echo '<div class="form-group">';
     if (empty($country) & $submitted) echo "&nbsp;<div class='col-sm-2'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="country" class="col-sm-2">Country* &nbsp;</label>';
			echo '<div class="col-sm-4">';
				echo '<input class="form-control" name="country" placeholder="Country" size="50" type="text" value="'.$country.'">';
			echo '</div>';
    echo '</div>';
  }
  
  // E-mail
  if ($verified) {
    echo "E-mail : ".$email."<input type='hidden' style='font-size:small' name='email' value='".$email."'/><br />";
  } else {
		echo '<div class="form-group">';
     if (empty($email) & $submitted) echo "&nbsp;<div class='col-sm-2'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="email" class="col-sm-4">Email (a diffrent one for each registered person)* &nbsp;</label>';
			echo '<div class="col-sm-4">';
				echo '<input class="form-control" name="email" placeholder="Email" size="50" type="text" value="'.$email.'">';
			echo '</div>';
    echo '</div>';
  }

  if ($verified) {
    echo "<input type='hidden' name='email2' value='".$email2."'/>";
  } else {
		echo '<div class="form-group">';
     if (empty($email2) & $submitted) echo "&nbsp;<div class='col-sm-2'><mark>Compléter</mark></div> &nbsp;";
			echo '<label for="email2" class="col-sm-4">Confirmation of the email* &nbsp;</label>';
			echo '<div class="col-sm-4">';
				echo '<input class="form-control" name="email2" placeholder="Confirmation of the email" size="50" type="text" value="'.$email2.'">';
			echo '</div>';
    echo '</div>';
  }
  
  // Téléphone, fax
  if ($verified) {echo "Tél. : ".$phone."<input type='hidden' style='font-size:small' name='phone' value='".$phone."' /><br />";
  } else {
		echo '<div class="form-group">';
			echo '<label for="phone" class="col-sm-2">Phone &nbsp;</label>';
			echo '<div class="col-sm-6">';
				echo '<input class="form-control" name="phone" placeholder="Phone" size="50" type="text" value="'.$phone.'">';
			echo '</div>';
    echo '</div>';
  }
  if ($verified) {   
    echo "Fax : ".$fax."<input type='hidden' name='fax' value='".$fax."' /><br />";
  } else {
		echo '<div class="form-group">';
			echo '<label for="fax" class="col-sm-2">Fax &nbsp;</label>';
			echo '<div class="col-sm-6">';
				echo '<input class="form-control" name="fax" placeholder="Fax" size="50" type="text" value="'.$faxs.'">';
			echo '</div>';
    echo '</div>';
  }
 
  // Frais
  echo "<h3>Registration</h3>";
  if ($verified) {
    echo "<i>My registration is made as: </i>";
    if ($status==$guest_status) {
    echo "Exonerated participant. <input type='hidden' name='status' value='".$status."'/>";
    } else {
      if ($reg_day < strtotime($deadline_fees)) {
        for ($i=0; $i<sizeof($code_status); $i++) {
          if ($i==$status) {
            echo $text_status_en[$i]." (".$fees_before[$i]." euros). <input type='hidden' name='status' value='".$status."'/>";
          }
        }
      } else {
        for ($i=0; $i<sizeof($code_status); $i++) {
          if ($i==$status) {
           echo $text_status_en[$i]." (".$fees_after[$i]." euros). <input type='hidden' name='status' value='".$status."'/>";
          }
        }
      }
    }
  } else {
    echo "<div class='alert alert-info' role='alert'><b>Warning! </b>After ".date('d/m/Y',strtotime($deadline_fees)).", registration fees are increasing. ";
    echo "See <a href='".$inscription_url_en."'>here</a> for further details.</div><br />";
    
    echo '<div class="form-group">';
			echo "<label for='status' class='col-sm-5 control-label'>My registration is made as &nbsp;</label>";
			echo '<div class="col-sm-4">';
				echo "<select class='form-control' name='status'>";
					if (time()<strtotime($deadline_fees)) {
						for ($i=0; $i<sizeof($code_status); $i++) {
							if ($i==$status) {
								echo "<option selected value='".$i."'>".$text_status_en[$i]." (".$fees_before[$i]." euros)</option>";
							} else {
								echo "<option value='".$i."'>".$text_status_en[$i]." (".$fees_before[$i]." euros)</option>";
							}
						}
					} else {
						for ($i=0; $i<sizeof($code_status); $i++) {
							if ($i==$status) {
								echo "<option selected value='".$i."'>".$text_status_en[$i]." (".$fees_after[$i]." euros)</option>";
							} else {
								echo "<option value='".$i."'>".$text_status_en[$i]." (".$fees_after[$i]." euros)</option>";
							}
						}
					}
					$i=sizeof($code_status)+1;
					if ($i==$status) {
						echo "<option selected value='".$i."'>Exonerated participant (0 euros)</option>";
					} else {
						echo "<option value='".$i."'>Exonerated participant (0 euros)</option>";
					}
				echo "</select>";
			echo "</div></div><div class='form-group'>";
			echo "<label for='fcode' class='col-sm-4 control-label'>If you are an exonerated participant, please enter here your exoneration code &nbsp;</label>";
			echo '<div class="col-sm-4">';
				echo "<input class='form-control' name='fcode' placeholder='Exoneration code' size='50' type='text' value='".$fcode."'>";
			echo '</div>';
		echo '</div>';
  }
  echo'<input type="hidden" name="nbacc">';

  echo "<h3>Thurday reception (free)</h3>";
  if ($verified) {
    if($gala==="1") {
      echo "I will participate to the Thursday reception.<input type='hidden' name='gala' value='".$gala."'/>";
    } else echo "I will not participate to the Thursday reception.<input type='hidden' name='gala' value='".$gala."'/>";
  } else {
    echo "I will participate to the Thursday reception: ";
    echo '<div class="radio">';
			echo '<label>';
				echo '<input type="radio" name="gala" id="gala" value="1" ';
				if ($gala==="1") {
					echo "checked";
				} else echo "unchecked";
				echo "/> Yes&nbsp; &nbsp;";
			echo '</label>';
		echo '</div>';
		echo '<div class="radio">';
			echo '<label>';
				echo "<input type='radio' name='gala' value='0' id='gala'";
				if($gala==="0") echo " checked"; else echo " unchecked";
				echo "/> No.";
			echo '</label>';
		echo '</div>';
  }

  // Déjeuners
  echo "<h3>Lunch</h3>";
  if ($verified) {
    $wantlunchtext = "";
    if ($lunch_nb==0) {
      echo "I will not have a lunch ".$lunch_desc.".";
    } else {
      echo "I will have ".$lunch_nb." lunch(es) ".$lunch_desc_en." (".$rgl_midi." euros) :<ul>";
      for ($i=0; $i< sizeof($lunchs_en); $i++) {
        if ($want_lunch[$i]==1) {
        echo "<li>".$lunchs_en[$i]."</li>";
        $wantlunchtext .= $lunchs_bd_names[$i]."-";
        }
      }
      echo "<input type='hidden' name='wantlunchtext' value='".$wantlunchtext."'/>";
      echo "</ul>";
    }
  } else {
    echo "I will have one or several lunch(es) ".$lunch_desc_en." :";
    echo "<table>";
    for ($i=0; $i< sizeof($lunchs_en); $i++) {
      echo "<tr><td>".$lunchs_en[$i]." (".$lunch_price." euros)&nbsp;&nbsp;</td><td><input name='wantlunch[]' type='checkbox' value='".$lunchs_bd_names[$i]."'";
      if ($want_lunch[$i]=="1") echo "checked='checked'";
      echo "></td></tr>";
    }
    echo "</table>";
  }
  
  // Activités sociales
  echo'<input type="hidden" name="activity1">';
   echo'<input type="hidden" name="activity2">';

  // Vérifié par le programme : le formulaire est à confirmer par l'utilisateur avec son paiement et des questions additionnelles
  if ($verified and !$confirmed) {
    echo "<h3>Payment</h3>";
    echo "<b>Cancelation:</b><br />";
    echo "Fees refund must be addressed to <a href='mailto:".$contact_email."'>".$contact_email."</a> before ".$remb_deadline_en.". A penalty of ".$remb_penalty." euros will be kept. No fees refund will be allowed after that date.<br />";
    echo "<table style='font-size: small; display:inline'>";
    echo "<tr><td><strong>Registration</strong> : </td><td>&nbsp;</td><td><input class='rgl' name='rgl_insc' type='text' size='5' value='".$rgl_insc."' style='text-align:right' readonly/> euros</td></tr>";
    echo "<tr><td><strong>Lunches (".$lunch_nb.")</strong> : </td><td> + </td><td><input class='rgl' name='rgl_midi' type='text' size='5' value='".$rgl_midi."' style='text-align:right'  readonly/> euros</td></tr>";
    echo "<tr><td><strong>Gala diner</strong> : </td><td> + </td><td><input class='rgl' name='rgl_gala' type='text' size='5' value='";
    if ($gala==1) echo $rgl_gala; else echo 0;
    echo "' style='text-align:right'  readonly/> euros</td></tr>";
    echo "<tr><td><strong>Accompanying persons (".$nb_acc.")</strong> : </td><td> + </td><td><input class='rgl' name='rgl_acc' type='text' size='5' value='".$rgl_acc."' style='text-align:right'  readonly/> euros</td></tr>";
    echo "<tr><td><strong>Total</strong> : </td><td> = </td><td><input class='rgl' name='total' type='text' size='5' value='".$total."' style='text-align:right'  readonly/> euros</td></tr>";
    echo "<tr><td><strong>Type of payment* </strong> : </td><td>&nbsp;</td><td><select name='payment'>";
    for ($i=0; $i < sizeof($pay_desc); $i++) {
      echo "<option value='".$av_pay[$i]."' ";
      if ($payment===$av_pay[$i]) echo "selected";
      echo " >".$pay_desc_en[$i]."</option>";
    }
    echo "</select></td></tr>";
    echo "</table><br />";
    echo "<strong>Additional questions:</strong><br />";
    for ($i=0; $i < sizeof($add_questions); $i++) {
      echo "<input type='checkbox' name='addquestions[]' value='".$add_labels[$i]."'>&nbsp; &nbsp;".$add_questions_en[$i]."<br />";
    }
  }

  // Si non encore confirmé : boutons de validation
  if(!$verified) {
    echo "<br><input type='submit' name='submitBtn' value='Submit' onClick='' />";
  } else {
    echo "<input type='submit' name='confirmBtn' value='Confirm' onClick='' />";
    echo "<input type='submit' name='editBtn' value='Modify' onClick='' />";
  }
  echo "</form>";
} elseif ($confirmed) {
  // L'inscription a été confirmée par le destinataire (fin de l'écran 2)

  // Sauvegarde dans la base de données
  $db=mysql_connect($db_server,$db_user,$db_pass) or die('Erreur de connexion '.mysql_error());
  mysql_select_db($db_user,$db) or die('Erreur de sélection '.mysql_error());
  $fname=mysql_real_escape_string($fname);
  $lname=mysql_real_escape_string($lname);
  $affiliation=mysql_real_escape_string($affiliation);
  $address=mysql_real_escape_string($address);
  $address2=mysql_real_escape_string($address2);
  $zip=mysql_real_escape_string($zip);
  $city=mysql_real_escape_string($city);
  $country=mysql_real_escape_string($country);
  $email=mysql_real_escape_string($email);
  $phone=mysql_real_escape_string($phone);
  $fax=mysql_real_escape_string($fax);
  $ref=$pre_sogen.time();
  $today=date("Y-m-d-H-i");
  if ($status==$guest_status) {
    $bd_status="guest";
  } else {
    $bd_status=$code_status[$status];
  }

  $ok=1;
  $check="SELECT * FROM `".$db_table."` WHERE `email`='".$email."'";
  $res = mysql_query($check);
  if ($one_res=mysql_fetch_object($res)) {
    if (($one_res->fees!=$fees) && ($one_res->res==="1")) {
    $ok=0;
    echo "<div class='alert alert-danger' role='alert'>You are already registered (your email already exists in our database) and you have already paid. If you want to change your options and that it affects your registration fees you must contact us at <a href='mailto:".$contact_email."'>".$contact_email."</a> and describe us your modification request.";
    } else {
    echo "<div class='alert alert-danger' role='alert'><em>You are already registered (your email already exists in our database) and you have already paid. <strong>Your registration has been updated and only the last one remains valid.</strong></em>";
    $query="UPDATE `".$db_table."` SET `title`='{$title}' , `fname`='{$fname}' , `lname`= '{$lname}' , `affiliation`='{$affiliation}' , `address`='{$address}' , `address2`='{$address2}' , `zip`='{$zip}' , `city`='{$city}' , `country`='{$country}', `phone`='{$phone}' , `fax`='{$fax}' , `status`='{$bd_status}', `nb_acc`='{$nb_acc}', `gala`='{$gala}'";
    for ($i=0; $i < sizeof($lunchs_bd_names); $i++) {
      if ($want_lunch[$i]==1) {
        $query.= ", `".$lunchs_bd_names[$i]."`='1'";
      } else $query.= ", `".$lunchs_bd_names[$i]."`='0'";
    }
    $query.=", `activity1`='{$activites[$activity1]}' , `activity2`='{$activites[$activity2]}' , `fees`='{$total}', `payment`='{$payment}', `lang`='{$lang}', `date_modif`='{$today}'";
    for ($i=0; $i < sizeof($add_labels); $i++) {
      $rep = IsChecked($addquestions,$add_labels[$i]);
      $query.= ", `".$add_labels[$i]."`='".$rep."'";
    }
    $query .="WHERE `index`='".$one_res->index."'";
    mysql_query($query) or die('Erreur SQL ! '.$sql.'<br/>'.mysql_error());
    $find_ref = "SELECT ref FROM `".$db_table."` WHERE `email`='".$email."'";
    $refs = mysql_query($find_ref);
    $refs= mysql_fetch_object($refs);
    $ref = $refs->ref;
    }
  } else {
    echo "<div class='alert alert-danger' role='alert'><em>Your registration is registered.</em>";
    $query="INSERT INTO `".$db_table."` (`title` , `fname` , `lname` , `affiliation` , `address` , `address2` , `zip` , `city` , `country` , `email` , `phone` , `fax` , `status` , `nb_acc`, `gala`";
    for ($i=0; $i < sizeof($lunchs_bd_names); $i++) $query.=", `".$lunchs_bd_names[$i]."` ";
    $query.=", `activity1` , `activity2` , `fees` , `payment`, `ref` , `lang`, `res`, `date_first`, `date_modif` ";
    for ($i=0; $i < sizeof($add_labels); $i++) $query.= ", `".$add_labels[$i]."`";
    $query .=") VALUES ('{$title}' , '{$fname}' , '{$lname}' , '{$affiliation}' , '{$address}' , '{$address2}' , '{$zip}' , '{$city}' , '{$country}' , '{$email}' , '{$phone}' , '{$fax}' , '{$bd_status}' , '{$nb_acc}', '{$gala}'";
    for ($i=0; $i < sizeof($lunchs_bd_names); $i++) {
      if ($want_lunch[$i]==1) {
        $query.= ", '1'";
      } else $query.= ", '0'";
    }
    $query.=", '{$activites[$activity1]}' , '{$activites[$activity2]}' , '{$total}' , '{$payment}', '{$ref}' , '{$lang}', '0', '{$today}', '{$today}'";
    for ($i=0; $i < sizeof($add_labels); $i++) {
      $rep = IsChecked($addquestions,$add_labels[$i]);
      $query .=", '".$rep."'";
    }
    $query .= ")";
    mysql_query($query) or die('Erreur SQL ! '.$sql.'<br/>'.mysql_error());
  }
  echo "<br><strong>The reference of your registration is ".$ref.".</strong> Please, keep this reference and use it anytime you contact us for a problem related to your registration.</div>";
  require_once('../facture/send_invoice.php');

  if ($ok) {
    echo "<div class='well'><em>A confirmation email with the invoice has been sent to you.</em><br/>";

    if (($payment=="credit")&&($total!=0)) {
      //Form to send payment information to Société Générale (made from the script 'payment.php')
      echo "In case of problem, please contact: <a href='mailto:".$contact_email."'>".$contact_email."</a>.";
      $charges = $total*100;

      echo "<br>Check your registration options and to proceed to the payment, please click below:<br>";
      echo "<form method='POST' action='http://www.sfds.asso.fr/processpayment'>";
      echo "<input type='hidden' name='id' value='".$id_sogen."'>";
      echo "<input type='hidden' name='demo' value='0'>";
      echo "<input type='hidden' name='language' value='".$lang."'>";
      echo "<input type='hidden' name='charges' value='".$charges."' readonly/>";
      echo "<input type='hidden' name='reference' value='".$ref."' readonly/>";
      echo "<center><img src='https://upload.wikimedia.org/wikipedia/commons/4/48/C64b_new.svg' width='64'>";
      echo "<input type='submit' value='Click here to proceed to the payment' /></center>";
      echo "</form>";
      echo "<br/><span style='font-size:x-small'>Image CB by Roulex 45 (self-made from Public domain, via Wikimedia Commons)</span><br/><br/>";
    };
    echo "</div>";
    
    // Préparation de l'eamil de confirmation
    $destinataire = $email.", " . $email_inscription;
    $sujet = $subject_email." ".$lname.", ".$fname;
    $boundary="mesjoliesfrontieres";
    $fichier=file_get_contents('../facture/docs/facture_'.$ref.'.pdf');
    $fichier=chunk_split(base64_encode($fichier));
    $entete = "From: " . $contact_email . "\n";
    $entete .= "Reply-to: " . $contact_email . "\n";
    $entete .= "MIME-Version: 1.0". "\n";
    $entete .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'";\r\n';
    if ($title==="mr") {
      $texte = "Mr. ".$fname." ".$lname.",\n\n";
    } elseif ($title==="mme") {
      $texte .= "Ms. ".$fname." ".$lname.",\n\n";
    }
    $texte .= $welcome_email_en;
    $texte .= "* Your reference ........................ : " . $ref . "\n\n";
    $texte .= "Please find below a description of your registration options. Indications to proceed to the payment are provided at the end of the email.\n\n";
    $texte .= "Best regards,\n\n";
    $texte .= "The steering committee.\n\n\n";  
    $texte .= "DESCRIPTION OF YOUR REGISTRATION: \n\n";
    $texte .= "* First name ...................... : " . $fname . "\n";
    $texte .= "* Last name ....................... : " . $lname . "\n";
    $texte .= "* Institution ..................... : " . $affiliation . "\n";
    $texte .= "* Address ......................... : " . $address . "\n";
    $texte .= "* Address ......................... : " . $address2 . "\n";
    $texte .= "* Zip code ........................ : " . $zip . "\n";
    $texte .= "* City ............................ : " . $city . "\n";
    $texte .= "* Country ......................... : " . $country . "\n";
    $texte .= "* Email ........................... : " . $email . "\n";
    $texte .= "* Phone ........................... : " . $phone . " \n";
    $texte .= "* Fax ............................. : " . $fax . "\n\n";
    $texte .= "* Registered as ................... : ";
    if ($status==$guest_status) {
      $texte .= "Exonerated (free)";
    } else {
      for ($i=0; $i<sizeof($code_status); $i++) {
        if ($i==$status) {
          $texte .= $text_status_en[$i];
        }
      }
    }
    $texte .= "\n";
	if ($gala==="1") {
      $texte .= "* Thurday reception ...................... : Yes\n";
    } else {
      $texte .= "* Thurday reception ...................... : No\n";
    }
    if ($lunch_nb>0) {
    $texte .= "* You have registered for " . $lunch_nb . " lunch(es) ";
      $texte .= "(";
      for ($i=0; $i < sizeof($lunchs_bd_names); $i++) {
        if ($want_lunch[$i]==1) $texte .= $lunchs_en[$i]." ";
      }
      $texte .= ")"."\n";
    } else $texte .= "* You are not registered for lunch.\n";
    if (count($addquestions)!=0) {
      $texte .="* Other informations ............. : ";
      for ($i=0; $i < sizeof($add_labels); $i++) {
        $res_query = mysql_query("SELECT `".$add_labels[$i]."` FROM ".$db_table." WHERE `ref`='".$ref."'");
        $res_query=mysql_fetch_array($res_query);
        if ($res_query[$add_labels[$i]]==="yes") {
          $texte .= $add_questions_en[$i] .", ";
        }
      }
      $texte .="\n";
    }
    mysql_close();
    
    if ($total==0) {
      $texte .= "There is nothing to pay.\n";
    } else {
      if ($payment=="cheque") {
        $texte .= "* Payment by ...................... : Check\n\n";
      } elseif ($payment=="transfer") {
        $texte .= "* Payment by ...................... : Transfer\n\n";
      } elseif ($payment=="order") {
        $texte .= "* Payment by ...................... : Bon de commande\n\n";
      } elseif ($payment=="credit") {
        $texte .= "* Payment by ...................... : Credit card\n";
      } elseif ($payment=="cash") {
        $texte .= "* Payment by ...................... : Cash\n";
      }
      $texte .= "* Registration fees ............... : " . $rgl_insc . " euros\n";
      $texte .= "* TOTAL ........................... : " . $total . " euros\n\n";
    
      if ($payment=="cheque") {
        $texte .= $cheque_message_en;
      } elseif ($payment=="transfer") {
        $texte .= $transfer_message_en;
      } elseif ($payment=="order") {
        $texte .= $order_message;
      } elseif ($payment=="liquide") {
        $texte .= $cash_message_en;
      }
      $texte .= "* Reference of your registration ........ : " . $ref . "\n\n";
    }

    // Envoi de l'email
    $message ="--" .$boundary. "\n";
    $message .= "Content-Type: text/plain;"."\n";
    $message .= 'Content-Transfer-Encoding: 7bit'."\n";
    $message .= $texte;
    $message .= "In case of problem, please contact: ".$contact_email.".\n";
    $message .= "--" .$boundary. "\n";
    $message .= "Content-Type: application/pdf; name=\"facture_".$ref.".pdf\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= "Content-Disposition: attachment; filename=\"facture_".$ref.".pdf\"\r\n\n";
    $message .= "$fichier";
    $message .= "--" . $boundary ."--";
    mail($destinataire,'=?UTF-8?B?'.base64_encode($sujet).'?=',$message,$entete);
  }
}
?>
		</div> <!-- container -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../inc/js/bootstrap.js"></script>
	</body>
</html>
