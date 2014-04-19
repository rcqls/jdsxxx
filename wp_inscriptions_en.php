[php]

// Load configuration file
require_once('payment/config.php');
require_once('payment/config-en.php');
require_once('payment/connect.php');

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
$lang='fr';
if(isset($_POST['title'])) $title=$_POST['title']; else $title='';
if(isset($_POST['fname'])) $fname=ucwords(strtolower($_POST['fname'])); else $fname='';
if(isset($_POST['lname'])) $lname=ucwords(strtolower($_POST['lname'])); else $lname='';
if(isset($_POST['affiliation'])) $affiliation=str_replace(array("\'","'")," ",$_POST['affiliation']); else $affiliation='';
if(isset($_POST['address'])) $address=str_replace(array("\'","'",";")," ",$_POST['address']); else $address='';
if(isset($_POST['address2'])) $address2=str_replace(array("\'","'",";")," ",$_POST['address2']); else $address2='';
if(isset($_POST['zip'])) $zip=$_POST['zip']; else $zip='';
if(isset($_POST['city'])) $city =str_replace(array("\'","'",";")," ",$_POST['city']); else $city='';
if(isset($_POST['country'])) $country=strtoupper($_POST['country']); else $country='';
if(isset($_POST['email'])) $email=$_POST['email']; else $email='';
if(isset($_POST['email2'])) $email2=$_POST['email2']; else $email2='';
if(isset($_POST['phone'])) $phone=$_POST['phone']; else $phone='';
if(isset($_POST['fax'])) $fax =$_POST['fax']; else $fax='';
if(isset($_POST['status'])) $status =$_POST['status']; else $status=0;
if(isset($_POST['fcode'])) $fcode =$_POST['fcode']; else $fcode='';
if(isset($_POST['nbacc'])) $nb_acc =$_POST['nbacc']; else $nb_acc=0;
if(isset($_POST['gala'])) $gala =$_POST['gala']; else $gala=0;
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
if(isset($_POST['editBtn'])) $edit=true; else $edit=false;
if(isset($_POST['submitBtn'])) $submitted=true; else $submitted=false;
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
  for ($i=0; $i< sizeof($lunchs_en); $i++) {
    if (IsChecked($wantlunch,$lunchs_bd_names[$i])=="yes") {
      $want_lunch[$i]=1;
    } else $want_lunch[$i]=0;
  }
} elseif (($submitted) or ($confirmed) or ($edit)) {
  $wantlunchtext=$_POST['wantlunchtext'];
  $wantlunch= explode("-",$wantlunchtext);
  $want_lunch=array();
  for ($i=0; $i< sizeof($lunchs_en); $i++) {
    if (IsChecked($wantlunch,$lunchs_bd_names[$i])=="yes") {
      $want_lunch[$i]=1;
    } else $want_lunch[$i]=0;
  }
} else {
  $want_lunch=array();
  for ($i=0; $i< sizeof($lunchs_en); $i++) {
    $want_lunch[$i] = 0;
  }
}

// The following lines initialized the array of open questions; if status is not "confirmed", the array is set empty
if (!$confirmed) {
  $addquestions = array();
} else {if (isset($_POST['addquestions'])) $addquestions=$_POST['addquestions']; else $addquestions=array();}

if (($submitted and $verified) or $confirmed) {
  // Registration is ok: calculate the price to pay
  $db=mysql_connect($db_server,$db_user,$db_pass) or die('Connexion error '.mysql_error());
  mysql_select_db($db_user,$db) or die('Selection error '.mysql_error());
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
  for ($i=0; $i< sizeof($lunchs_en); $i++) {
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
    echo "<span style='color:red'>Please fill all missing fields in red.</span>";
  } elseif($reason==="email") {
    echo "<span style='color:red'>Both e-mail addresses don't match.</span>";
  } elseif ($reason==="notfree") {
    echo "<span style='color:red'>The entered code of exemption was incorrect.</span>";
  }
}

// If the form was found incomplete or the user chose to edit something or the form still hasn't been confirmed
if (!$confirmed) {
  // Display the form with indications on missing entries and previously entered data if any
  if (!$submitted) {
    echo "Your registration can be updated anytime by filling again this form <strong>with the same e-mail address</strong>. The rate of your first registration will still be applied after a modification.<br>";
    echo $welcome_message_en;
  }
  if ($verified) {
    if ($exist_email) echo "<strong>Warning !</strong> You used an e-mail address which already exists in our database: you are going to  <strong>update an existing registration</strong>. If you want to register a new participant, please change your e-mail address.";
  }
  echo '<form name="main" action="'. $current_url_en .'" method="post">';
  
  // Civilité
  if($verified) {
    if ($title==="mr") echo "M.&nbsp;<input type='hidden' name='title' value='mr'/>";
    elseif ($title==="Mrs") echo "Mme&nbsp;<input type='hidden' name='title' value='Mrs'/>";
  } else {
    echo "<font style='font-size: small'><b>Civilité * </b><select name='title'>";
    echo "<option value='mr'";
    if($title==="mr") echo "selected";
    echo ">M.</option>";
    echo "<option value='Mrs'";
    if($title==="Mrs") echo "selected";
    echo ">Mrs</option>";
    echo "</select></font>";
  }
  
  // Nom et prénom
  if ($submitted and empty($fname)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo $fname."&nbsp;<input type='hidden' name='fname' style='font-size:small' value='".$fname."'/>";
  } else {
    echo "<b>First name*</b>&nbsp;&nbsp;<input name='fname' type='text' size='50' style='font-size:small' value='".$fname."' /><br><br />";
  }
  if ($submitted and empty($lname)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo $lname."&nbsp;<input type='hidden' name='lname' style='font-size:small' value='".$lname."'/><br />";
  } else {
    echo "<b>Last name *</b>&nbsp;&nbsp;<input name='lname' type='text' size='50' style='font-size:small' value='".$lname."' /><br /><br />";
  }
  
  // Affiliation
  if ($submitted and empty($affiliation)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if($verified) {
    echo $affiliation."<input type='hidden' name='affiliation' style='font-size:small' value='".$affiliation."'/><br />";
  } else {
    echo "<b>University, Company or Organization *</b><br /><input name='affiliation' type='text' size='75' style='font-size:small' value='".$affiliation."' /><br /><br />";
  }
  
  // Adresse
  if ($submitted and empty($address)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo $address."<input type='hidden' name='address' style='font-size:small' value='".$address."'/><br />";
  } else {
    echo "<br /><b>Address *</b><br /><input name='address' type='text' style='font-size:small' size='75' value='".$address."' /><br /><br />";
  }
  if ($verified) {
    echo $address2."<input type='hidden' style='font-size:small' name='address2' value='".$address2."'/><br />";
  } else {
    echo "<br /><b>Address</b><br /><input name='address2' type='text' style='font-size:small' size='75' value='".$address2."' /><br /><br />";
  }
  if ($submitted and empty($zip)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo $zip."&nbsp;<input type='hidden' style='font-size:small' name='zip' value='".$zip."'/>";
  } else {
    echo "<br /><b>Zip code *</b>&nbsp;&nbsp;<input name='zip' type='text' style='font-size:small' size='10' value='".$zip."' /><br /><br />";
  }
  if ($submitted and empty($city)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo $city."<input type='hidden' style='font-size:small' name='city' value='".$city."'/>";
  } else {
    echo "<br /><b>City *</b>&nbsp;&nbsp;<input name='city' type='text' style='font-size:small' size='40' value='".$city."' /><br /><br />";
  }
  if ($submitted and empty($country)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo $country."<input type='hidden' style='font-size:small' name='country' value='".$country."'/><br />";
  } else {
    echo "<br /><b>Country *</b>&nbsp;&nbsp;<input name='country' type='text' style='font-size:small' size='50' value='".$country."' /><br /><br />";
  }
  
  // E-mail
  if ($submitted and empty($email)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo "E-mail: ".$email."<input type='hidden' style='font-size:small' name='email' value='".$email."'/>";
  } else {
    echo "<br /><b>E-mail address(a different e-mail address for each participant) *</b>&nbsp;&nbsp;<input name='email' type='text' style='font-size:small' size='50' value='".$email."' /><br /><br />";
  }
  if ($submitted and empty($email2)) {
    echo "<font style='color:red; font-size: small'><b>Please fill:</b></font>";
  }
  if ($verified) {
    echo "<input type='hidden' name='email2' value='".$email2."'/>";
  } else {
    echo "<br /><b>Confirm your e-mail address *</b>&nbsp;&nbsp;<input name='email2' type='text' style='font-size:small' size='50' value='".$email2."' /><br /><br />";
  }
  
  // Téléphone, fax
  if ($verified) {echo "Tel.: ".$phone."<input type='hidden' style='font-size:small' name='phone' value='".$phone."' />";
  } else {
    echo "<br /><b>Phone </b>&nbsp;&nbsp;<input name='phone' type='text' style='font-size:small' size='25' value='".$phone."' /><br /><br />";
  }
  if ($verified) {
    echo "Fax: ".$fax."<input type='hidden' name='fax' value='".$fax."' /><br />";
  } else {
    echo "<br /><b>Fax </b>&nbsp;&nbsp;<input name='fax' type='text' style='font-size:small' size='25' value='".$fax."' />";
  }
 
  // Frais
  echo "<h3>Expenses of registration</h3>";
  if ($verified) {
    echo "<i>I register as: </i>";
    if ($status==$guest_status) {
    echo "Exempted from the expenses of registration (free). <input type='hidden' name='status' value='".$status."'/>";
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
    echo "<p style='font-size: small'><b>Warning ! </b>After the ".date('d/m/Y',strtotime($deadline_fees)).", the registration fees are increased. ";
    echo "See <a href='".$inscription_url_en."'>here</a> for more details.<br />";
    echo "<b>I register as: </b></p>";
    echo "<select name='status'>";
    if (time()<strtotime($deadline_fees)) {
      for ($i=0; $i<sizeof($code_status); $i++) {
        if ($i==$status) {
          echo "<option selected style='font-size: small' value='".$i."'>".$text_status_en[$i]." (".$fees_before[$i]." euros)</option>";
        } else {
          echo "<option style='font-size: small' value='".$i."'>".$text_status_en[$i]." (".$fees_before[$i]." euros)</option>";
        }
      }
    } else {
      for ($i=0; $i<sizeof($code_status); $i++) {
        if ($i==$status) {
          echo "<option selected style='font-size: small' value='".$i."'>".$text_status_en[$i]." (".$fees_after[$i]." euros)</option>";
        } else {
          echo "<option style='font-size: small' value='".$i."'>".$text_status_en[$i]." (".$fees_after[$i]." euros)</option>";
        }
      }
    }
     $i=sizeof($code_status)+1;
     if ($i==$status) {
       echo "<option selected style='font-size: small' value='".$i."'>Exempted from the expenses of registration (0 euros)</option>";
     } else {
       echo "<option style='font-size: small' value='".$i."'>Exempted from the expenses of registration (0 euros)</option>";
     }
    echo "</select>";
    echo "<font style='font-size: small'><b>If you are exempted from the expenses of registration, please give your exemption code: </b><input name='fcode' type='text' size='20' style='font-size:small' value='".$fcode."' /></font>";
  }
  if ($verified) {
    echo " I will be coming with: ".$nb_acc." person(s).<input type='hidden' style='font-size:small' name='nbacc' value='".$nb_acc."'/>";
  } else {
    echo "<p style='font-size: small'> You can register one or more accompanying person. The registration of one accompanying person enables him (or her) to attend to the gala dinner of the conference gala and to the social activities. The fees of the registration of each accompanying person is ".$acc_price." euros.<br /><b>Number of accompanying person: </b>&nbsp;&nbsp;<input name='nbacc' type='text' maxlength=1 size=2 style='font-size:small' value='".$nb_acc."' />";
  }
  
  // Dîner de gala
  echo "<h3>Gala dinner (".$gala_diner_date_en.", ".$gala_diner_price." euros)</h3>";
  if ($verified) {
    if($gala==="1") {
      echo "I will attend the gala dinner.<input type='hidden' name='gala' value='".$gala."'/>";
    } else echo "I won't attend the gala dinner.<input type='hidden' name='gala' value='".$gala."'/>";
  } else {
    echo "<p style='font-size: small; display:inline'>I will attend the gala dinner: </p>";
    echo "<br><p style='font-size: small; display:inline'><input type='radio' name='gala' value='1' onClick='calcTotal()'";
    if ($gala==="1") {
      echo "checked";
    } else echo "unchecked";
    echo "/> Yes <input type='radio' name='gala' value='0' onClick='calcTotal()'";
    if($gala==="0") echo "checked"; else echo "unchecked";
    echo "/> No.<br /></p><br />";
  }

  // Déjeuners
  echo "<h3>Lunch</font></h3>";
  if ($verified) {
    $wantlunchtext = "";
    if ($lunch_nb==0) {
      echo "I do not want to book for a lunch. ".$lunch_desc_en.".";
    } else {
      echo "I want to book ".$lunch_nb." lunch(s) ".$lunch_desc_en." (".$rgl_midi." euros):<ul>";
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
    echo "<p style='font-size: small'>I want to book one or more lunch(s) ".$lunch_desc_en.":</p>";
    echo "<table width='300'>";
    for ($i=0; $i< sizeof($lunchs_en); $i++) {
      echo "<tr><td>".$lunchs_en[$i]." (".$lunch_price." euros)</td><td><input name='wantlunch[]' type='checkbox' value='".$lunchs_bd_names[$i]."'";
      if ($want_lunch[$i]=="1") echo "checked='checked'";
      echo "</td></tr>";
    }
    echo "</table>";
  }
  
  // Activités sociales
  echo "<h3>Social activities (free)</h3>";
  if($verified) {
    echo "Social activity (choice 1): ".$activites_en[$activity1].".<br />";
    echo " <input style='font-size: small' type='hidden' name='activity1' value='".$activity1."'/>";
    echo "Social activity (choice 2): ".$activites_en[$activity2].".<br />";
    echo " <input style='font-size: small' type='hidden' name='activity2' value='".$activity2."'/>";
  } else {
    echo "<p style='font-size: small'><b>Social activities ".$social_activity_date_en."</b>: To satisfy you at best and facilitate the organization of the social activities, we suggest you formulating two choices in order of priority among the following activities. This will allow us to size better the various groups.</p>";
    echo "&nbsp; &nbsp; <b>Social activity (choice 1):</b> <select name='activity1'>";
    for ($i=0; $i<sizeof($activites_en); $i++) {
      if ($i==$activity1) {
        echo "<option selected style='font-size: small' value='".$i."'>".$activites_en[$i]."</option>";
      } else {
        echo "<option style='font-size: small' value='".$i."'>".$activites_en[$i]."</option>";
      }
    }
    echo "</select>";
    echo "&nbsp; &nbsp; <b>Social activity (choice 2):</b> <select name='activity2'>";
    for ($i=0; $i<sizeof($activites_en); $i++) {
      echo "<option style='font-size: small' value='".$i."'";
      if ($i==$activity2) echo ' selected ';
      echo ">".$activites_en[$i]."</option>";
    }
    echo "</select>";
  }

  // Vérifié par le programme: le formulaire est à confirmer par l'utilisateur avec son paiement et des questions additionnelles
  if ($verified and !$confirmed) {
    echo "<h3>Payment</h3>";
    echo "<b>Cancellation:</b><br />";
    echo "The refunding of the registration fees will be possible on mail request to <a href='mailto:".$contact_email."'>".$contact_email."</a> before the ".$remb_deadline_en.". It will give rise to a penalty of ".$remb_penalty." euros. No refunding will be done after this date.";
    echo "<table style='font-size: small; display:inline'>";
    echo "<tr><td><strong>Registration</strong>: </td><td>&nbsp;</td><td><input class='rgl' name='rgl_insc' type='text' size='5' value='".$rgl_insc."' style='text-align:right' readonly/> euros</td></tr>";
    echo "<tr><td><strong>Lunch (".$lunch_nb.")</strong>: </td><td> + </td><td><input class='rgl' name='rgl_midi' type='text' size='5' value='".$rgl_midi."' style='text-align:right' readonly/> euros</td></tr>";
    echo "<tr><td><strong>Gala dinner</strong>: </td><td> + </td><td><input class='rgl' name='rgl_gala' type='text' size='5' value='";
    if ($gala==1) echo $rgl_gala; else echo 0;
    echo "' style='text-align:right' readonly/> euros</td></tr>";
    echo "<tr><td><strong>Accompanying person (".$nb_acc.")</strong>: </td><td> + </td><td><input class='rgl' name='rgl_acc' type='text' size='5' value='".$rgl_acc."' style='text-align:right' readonly/> euros</td></tr>";
    echo "<tr><td><strong>Total </strong>: </td><td> = </td><td><input class='rgl' name='total' type='text' size='5' value='".$total."' style='text-align:right' readonly/> euros</td></tr>";
    echo "<tr><td><strong>Method of payment * </strong>: </td><td>&nbsp;</td><td><select name='payment'>";
    for ($i=0; $i < sizeof($pay_desc_en); $i++) {
      echo "<option value='".$av_pay[$i]."' ";
      if ($payment===$av_pay[$i]) echo "selected";
      echo " >".$pay_desc_en[$i]."</option>";
    }
    echo "</select></td></tr>";
    echo "</table><br />";
    echo "<strong>Additional questions:</strong><br />";
    for ($i=0; $i < sizeof($add_questions_en); $i++) {
      echo "<input type='checkbox' name='addquestions[]' value='".$add_labels[$i]."'>&nbsp; &nbsp;".$add_questions_en[$i]."<br />";
    }
  }

  // Si non encore confirmé: boutons de validation
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
  $db=mysql_connect($db_server,$db_user,$db_pass) or die('Connexion error '.mysql_error());
  mysql_select_db($db_user,$db) or die('Selection error '.mysql_error());
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
    echo "You are already registered (your e-mail address was already existing in our database) and you have already paid. Your fees have changed: please contact us at <a href='mailto:".$contact_email."'>".$contact_email."</a> to make a request of modifications of your options.<br />";
    } else {
    echo "<em>Your are already registered (your e-mail address was already existing in our database): <strong>your registration was updated and only the last registration will be taken into account.</strong>.</em><br/>";
    $query="UPDATE `".$db_table."` SET `title`='{$title}' , `fname`='{$fname}' , `lname`= '{$lname}' , `affiliation`='{$affiliation}' , `address`='{$address}' , `address2`='{$address2}' , `zip`='{$zip}' , `city`='{$city}' , `country`='{$country}', `phone`='{$phone}' , `fax`='{$fax}' , `status`='{$bd_status}', `nb_acc`='{$nb_acc}', `gala`='{$gala}'";
    for ($i=0; $i < sizeof($lunchs_bd_names); $i++) {
      if ($want_lunch[$i]==1) {
        $query.= ", `".$lunchs_bd_names[$i]."`='1'";
      } else $query.= ", `".$lunchs_bd_names[$i]."`='0'";
    }
    $query.=", `activity1`='{$activites_en[$activity1]}' , `activity2`='{$activites_en[$activity2]}' , `fees`='{$total}', `payment`='{$payment}', `lang`='{$lang}', `date_modif`='{$today}'";
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
    echo "<em>Your registration has been recorded.</em><br/>";
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
    $query.=", '{$activites_en[$activity1]}' , '{$activites_en[$activity2]}' , '{$total}' , '{$payment}', '{$ref}' , '{$lang}', '0', '{$today}', '{$today}'";
    for ($i=0; $i < sizeof($add_labels); $i++) {
      $rep = IsChecked($addquestions,$add_labels[$i]);
      $query .=", '".$rep."'";
    }
    $query .= ")";
    mysql_query($query) or die('Erreur SQL ! '.$sql.'<br/>'.mysql_error());
  }
  require_once('facture/send_invoice.php');

  if ($ok) {
    echo "<em>An email of confirmation was sent to you at your address with as attachment the invoice of your registration.</em><br/>";

    if (($payment=="credit")&&($total!=0)) {
      //Form to send payment information to Société Générale (made from the script 'payment.php')
      echo "If you have any problem, please contact: <a href='mailto:".$contact_email."'>".$contact_email."</a>.";
      $charges = $total*100;

      echo "Check your informations and, to proceed to the payment, click the icon below:<br>";
      echo "<form method='POST' action='http://www.sfds.asso.fr/processpayment'>";
      echo "<input type='hidden' name='id' value='".$id_sogen."'>";
      echo "<input type='hidden' name='demo' value='0'>";
      echo "<input type='hidden' name='language' value='".$lang."'>";
      echo "<input type='hidden' name='charges' value='".$charges."' readonly/>";
      echo "<input type='hidden' name='reference' value='".$ref."' readonly/>";
      echo "<center><img src='https://upload.wikimedia.org/wikipedia/commons/4/48/C64b_new.svg' width='64'>";
      echo "<input type='submit' value='Click here to pay with your credit card via the payment gateway of the website of the SFdS' /></center>";
      echo "</form>";
      echo "<br/><span style='font-size:x-small'>Image CB par Roulex 45 (self-made from Public domain, via Wikimedia Commons)</span><br/><br/>";
    }
    
    // Préparation de l'email de confirmation
    $destinataire = $email.", " . $email_inscription;
    $sujet = $subject_email." ".$lname.", ".$fname;
    $boundary="mesjoliesfrontieres";
    $fichier=file_get_contents('facture/docs/facture_'.$ref.'.pdf');
    $fichier=chunk_split(base64_encode($fichier));
    $entete = "From: " . $contact_email . "\n";
    $entete .= "Reply-to: " . $contact_email . "\n";
    $entete .= "MIME-Version: 1.0". "\n";
    $entete .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'";\r\n';
    if ($title==="mr") {
      $texte = "M. ".$fname." ".$lname.",\n\n";
    } elseif ($title==="mme") {
      $texte .= "Mme ".$fname." ".$lname.",\n\n";
    }
    $texte .= $welcome_email_en;
    $texte .= "* Reference of your registration ...........: " . $ref . "\n\n";
    $texte .= "You will find below a a summary of your registration, as well as details allowing you to proceed to the payment of this one (at the end).\n\n";
    $texte .= "Best regards,\n\n";
    $texte .= " The Organising Committee .\n\n\n";
    $texte .= "SUMMARY: \n\n";
    $texte .= "* First name ..........................: " . $fname . "\n";
    $texte .= "* Last name .............................: " . $lname . "\n";
    $texte .= "* Organization .....................: " . $affiliation . "\n";
    $texte .= "* Address .........................: " . $address . "\n";
    $texte .= "* Address ......................: " . $address2 . "\n";
    $texte .= "* Zip Code .....................: " . $zip . "\n";
    $texte .= "* City ...........................: " . $city . "\n";
    $texte .= "* Country ............................: " . $country . "\n";
    $texte .= "* E-Mail ..........................: " . $email . "\n";
    $texte .= "* Phone .......................: " . $phone . " \n";
    $texte .= "* Fax .............................: " . $fax . "\n\n";
    $texte .= "* Registered as .............: ";
    if ($status==$guest_status) {
      $texte .= "Exempted from the expenses of registration (free)";
    } else {
      for ($i=0; $i<sizeof($code_status); $i++) {
        if ($i==$status) {
          $texte .= $text_status_en[$i];
        }
      }
    }
    $texte .= "\n";
    $texte .= "* Number of accompanying person ..........: " . $nb_acc . "\n";
    if ($gala==="1") {
      $texte .= "* Participation in the gala dinner ..: Oui\n";
    } else {
      $texte .= "* Participation in the gala dinner ..: Non\n";
    }
    if ($lunch_nb>0) {
    $texte .= "* You have booked " . $lunch_nb . " lunch(s)";
      $texte .= "(";
      for ($i=0; $i < sizeof($lunchs_bd_names); $i++) {
        if ($want_lunch[$i]==1) $texte .= $lunchs_en[$i]." ";
      }
      $texte .= ")"."\n";
    } else $texte .= "* Booking of lunches ........: Non\n";
    $texte .= "* Activity, first choice .........: ".$activites_en[$activity1]."\n";
    $texte .= "* Activity, second choice ..........: ".$activites_en[$activity2]."\n";
    if (count($addquestions)!=0) {
      $texte .="* Additional informations .............: ";
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
        $texte .= "* Method of Payment ................: Cheque\n\n";
      } elseif ($payment=="transfer") {
        $texte .= "* Method of Payment ................: Transfer\n\n";
      } elseif ($payment=="order") {
        $texte .= "* Method of Payment ................: Order form \n\n";
      } elseif ($payment=="credit") {
        $texte .= "* Method of Payment ................: Credit card\n";
      } elseif ($payment=="cash") {
        $texte .= "* Method of Payment ................: Cash \n";
      }
      $texte .= "* Registration fees .............: " . $rgl_insc . " euros\n";
      $texte .= "* Lunchs .......................: " . $rgl_midi . " euros\n";
      $texte .= "* Accompanying person ...................: " . $rgl_acc . " euros\n";
      $texte .= "* Gala dinner ...................: " . $rgl_gala . " euros\n";
      $texte .= "* TOTAL PRICE ......................: " . $total . " euros\n\n";
    
      if ($payment=="cheque") {
        $texte .= $cheque_message_en;
      } elseif ($payment=="transfer") {
        $texte .= $transfer_message_en;
      } elseif ($payment=="order") {
        $texte .= $order_message_en;
      } elseif ($payment=="liquide") {
        $texte .= $cash_message_en;
      }
      $texte .= "* Reference of your registration ...........: " . $ref . "\n\n";
    }
    // Affichage du message à l'écran
    echo $texte;

    // Envoi de l'email
    $message ="--" .$boundary. "\n";
    $message .= "Content-Type: text/plain;"."\n";
    $message .= 'Content-Transfer-Encoding: 7bit'."\n";
    $message .= $texte;
    $message .= "If you have any problem, please contact: ".$contact_email.".\n";
    $message .= "--" .$boundary. "\n";
    $message .= "Content-Type: application/pdf; name=\"facture_".$ref.".pdf\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= "Content-Disposition: attachment; filename=\"facture_".$ref.".pdf\"\r\n\n";
    $message .= "$fichier";
    $message .= "--" . $boundary ."--";
    mail($destinataire,'=?UTF-8?B?'.base64_encode($sujet).'?=',$message,$entete);
  }
}

[/php]