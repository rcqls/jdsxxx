<?php
// Partie réservée à l'administrateur
// -----------------------------------------------------------------------------
// Name of the table in the database
$db_table='registrationXXX';
// Sogenactif form parameters
$pre_sogen = "JdSxxx_"; // what is used as a reference for the payment in the sogenactif form
$id_sogen = 19; //id of the sogenactif payment reference
// URLs
$website_url = "http://jdsxxx.sfds.asso.fr";
$current_url = "http://jdsxxx.sfds.asso.fr/registration/";
$contact_email = "jdsxxx@sfds.asso.fr";
$inscription_url = "http://jdsxxx.sfds.asso.fr/inscriptions/";
$url_papers="http://papersjdsXX.sfds.asso.fr/";
$url_prog="http://jdsxxx.sfds.asso.fr/programme/";
$url_validpaiment="http://jdsxxx.sfds.asso.fr/admin/index.php";
// -----------------------------------------------------------------------------


// Partie pour le webmestre de la conférence
// Coûts
$code_status = array("student","member","normal"); // code (un mot sans espace ou caractère spécial) qui indiquera le statut de l'inscription dans la base de données (fichier d'inscrits) ; le dernier doit TOUJOURS être "invite"
$text_status = array("Étudiant, retraité ou membre d'un pays en voie de développement","Membre de la SFdS ou d'une association partenaire (SFB, SMAI, SMF, SFC, FetM)","Autre"); //différents statuts d'inscrits
$fees_before = array(120,250,300); // coûts des inscriptions avant la date limite pour les différents statuts d'inscrits
$fees_after = array(150,320,280); // coûts des inscriptions après la date limite pour les différents statuts d'inscrits
$lunch_price = 15; // coûts des déjeuners
$gala_diner_price = 0; // coûts du dîner de gala
$acc_price=100; // coût pour un accompagnant (activités sociales + gala)
$lunch_exo=0; // coût d'un déjeuner pour un exonéré
$gala_exo=0; // coût du repas de gala pour un exonéré
$remb_penalty=100; // pénalité de remboursement des frais
$free_code = "Free4U-JdS2013"; // code d'exonération : en choisir un sans espace
// Dates
$deadline_fees = "2013-04-14"; // deadline pour les coûts réduits
$deadline_papers = "2013-04-20"; // date à laquelle les liens vers les articles (PDF) seront affichés dans le programme
$gala_diner_date = "Mercredi 29 mai, inclus dans le prix de l'inscription"; // date du repas de gala
$lunchs=array("Lundi 27 mai","Mardi 28 mai","Mercredi 29 mai (panier repas)","Jeudi 30 mai","Vendredi 31 mai"); // liste des noms des déjeuners
$lunchs_bd_names=array("lunch1","lunch2","lunch3","lunch4","lunch5"); // liste des noms des déjeuners dans la BD (même longueur que la liste précédente, pas d'espace)
$social_activity_date= "de l'après-midi du mercredi 29 mai"; // date des activités sociales
$remb_deadline="1er mai 2013"; // date limite de remboursement des frais
// Lunch and social activities description
$lunch_desc= "(repas à l'ESC, dans la limite des 300 places disponibles ; buffet comprenant des plats végétariens)"; // description des déjeuners
$activites=array("A0 - Je ne souhaite pas participer", "A1 - 1/2 Journée Toulouse et Airbus", "A2 - 1/2 Journée à la découverte de Toulouse", "A3 - 1/2 Journée à la découverte d Albi", "A4 - 1/2 Journée à la découverte de Carcassonne"); // liste des activités sociales
// Means to pay fees
$av_pay = array("credit","transfer","order"); // Moyens possibles : array("cheque","transfer","order","credit","cash"); to add others, please contact the dev: nathalie@nathalievilla.org
$pay_desc = array("Carte bancaire","Virement","Bon de commande"); // description en français des moyens de paiements possibles (même longueur que la liste précédente)
// Additional questions (an answer 'yes' or 'no' is requested)
$add_labels = array("certificate", "sncf", "paper"); // labels pour la BD des questions additionnelles
$add_questions = array("Je demande une attestation de participation", "Je demande un dossier pour réduction SNCF", "Je présente une communication aux JdS"); // questions additionnelles en français (même longueur que la liste précédente)

// Informations bancaires
$siret="419 725 197 00018"; // siret
$bankaccount="00037260664"; // numéro de compte
$rib="72"; // RIB
$iban="FR76 3000 3030 8500 0372 6066 472"; // IBAN
$bic="SOGEFRPP"; // BIC
$bankowner="MF-SFDS-JDS-2013"; // Titulaire
$adr_titulaire="Société Française de Statistique<br>"; // adresse (plusieurs lignes possibles ; commencer les lignes à partir de la deuxième par .= au lieu de =)
$adr_titulaire.="11 rue Pierre et Marie Curie<br>";
$adr_titulaire.="75231 Paris cedex 05";
$the_bank="Société Générale"; // banque
$bank_code="30003"; // code banque
$bank_guichet="03085"; // code guichet

// Messages
$welcome_message="<p style='font-size:small'>Les inscriptions sont possibles jusqu'au <strong>19 mai 2013</strong>. Merci de nous informer de toute modification concernant votre inscription en remplissant à nouveau ce formulaire ou bien en envoyant un email à <a href='mailto:jds2013@sfds.asso.fr'>jds2013@sfds.asso.fr</a>. Ceci est particulièrement important pour les modifications concernant le <strong>dîner de gala</strong> ou la <strong>visite à AIRBUS</strong>.<br>
Les champs marqués d'une (*) sont obligatoires. En cas de problème pour votre inscription, veuillez contacter <a href='mailto:jds2013@sfds.asso.fr'>jds2013@sfds.asso.fr</a>.<br />
Si vous souhaitez participer à la session du café de la statistique qui aura lieu le 30 mai 2013, merci de vous inscrire <b>en plus</b> <a href='http://lime.sfds.asso.fr/index.php?sid=22517&lang=fr'>ici</a>.<br /></p>"; // message au début du formulaire
$subject_email="[JdS2013] Inscription :"; // sujet de l'email envoyé (le nom est ajouté automatiquement)
$subject_email_cb="[JdS2013] Paiement CB :"; // sujet de l'email de confirmation de paiement CB (le nom est ajouté automatiquement)
$welcome_email="Nous avons bien pris en compte votre inscription aux JdS2013.\n"; // message qui commence l'email
$email_inscription = "inscriptionsJdS2013@sfds.asso.fr"; // email auquel est envoyé la confirmation d'inscription

$cheque_message="Votre chèque doit être :\n"; // message pour les instructions de paiement par chèque (plusieurs lignes possibles ; commencer les lignes à partir de la deuxième par .= au lieu de =)
$cheque_message .= "1. libellé à l'ordre de : ".$bankowner."\n";
$cheque_message .= "2. envoyé à : Sébastien Déjean\n";
$cheque_message .= "Institut de Mathématiques de Toulouse\n";
$cheque_message .= "Université Paul Sabatier\n";
$cheque_message .= "118 route de Narbonne\n";
$cheque_message .= "31062 Toulouse cedex 9 - France\n\n";
$cheque_message .= "!! Attention !! Merci de noter la référence de l'inscription sur votre chèque, ainsi que votre nom :\n";

$transfer_message = "Virement vers le compte français :\n"; // message pour les instructions de paiement par virement (plusieurs lignes possibles ; commencer les lignes à partir de la deuxième par .= au lieu de =)
$transfer_message .= "Titulaire : ".$bankowner."\n";
$transfer_message .= "\t Société Française de Statistique\n";
$transfer_message .= "\t Institut Poincaré\n";
$transfer_message .= "\t 11, rue Pierre et Marie Curie\n";
$transfer_message .= "\t 75005 Paris Cedex\n";
$transfer_message .= "Banque : ".$the_bank."\n";
$transfer_message .= "Code banque : ".$bank_code."\n";
$transfer_message .= "Code guichet : ".$bank_guichet."\n";
$transfer_message .= "Numéro de compte : ".$bankaccount."\n";
$transfer_message .= "Clé RIB : ".$rib."\n";
$transfer_message .= "IBAN : ".$iban."\n";
$transfer_message .= "BIC : ".$bic." \n\n";
$transfer_message .= "!! Attention !! Merci de spécifier comme référence du virement :\n";

$order_message = "Votre bon de commande doit :\n"; // message pour les instructions de paiement par bon de commande (plusieurs lignes possibles ; commencer les lignes à partir de la deuxième par .= au lieu de =)
$order_message .= "1. être libellé à l'ordre de : ".$bankowner."\n";
$order_message .= "2. contenir la référence de l'inscription (voir ci-dessous)\n";
$order_message .= "3. DE PRÉFÉRENCE être envoyé par email à : Sébastien Déjean <sebastien.dejean@math.univ-toulouse.fr>\n";
$order_message .= "avec dans l'objet de l'email [Bon de commande JDS 2013]\n";
$order_message .= "OU BIEN : être envoyé par courrier ordinaire à : Sébastien Déjean\n";
$order_message .= "Institut de Mathématiques de Toulouse\n";
$order_message .= "Université Paul Sabatier\n";
$order_message .= "118 route de Narbonne\n";
$order_message .=  "31062 Toulouse cedex 9 - France\n\n";
$order_message .= "4. Pour le paiement ultérieur par virement bancaire :\n\n";
$order_message .= "Virement vers le compte français :\n";
$order_message .="Titulaire : ".$bankowner."\n";
$order_message .= "\t Société Française de Statistique\n";
$order_message .= "\t Institut Poincaré\n";
$order_message .= "\t 11, rue Pierre et Marie Curie\n";
$order_message .= "\t 75005 Paris Cedex\n";
$order_message .= "Banque : ".$the_bank."\n";
$order_message .= "Code banque : ".$bank_code."\n";
$order_message .= "Code guichet : ".$bank_guichet."\n";
$order_message .= "Numéro de compte : ".$bankaccount."\n";
$order_message .= "Clé RIB : ".$rib."\n";
$order_message .= "IBAN : ".$iban."\n";
$order_message .= "BIC :".$bic." \n\n";
$order_message .= "!! Attention !! Merci de spécifier comme référence du virement :\n";

$cash_message .= "Vous avez choisi de payer en liquide. Le paiement devra être effectué impérativement le jour de votre arrivée au guichet d'inscription.\n"; // message pour les instructions de paiement en liquide (plusieurs lignes possibles ; commencer les lignes à partir de la deuxième par .= au lieu de =)

//Invoice
$place="Toulouse"; // lieu de la conférence
$eventname="45e Journées de Statistique"; // nom de la conférence
$emetteur="JdS 2013"; // émetteur de la facture
$em_adr1="Pôle finance du comité d'organisation"; // adresse de l'émetteur (3 lignes obligatoirement)
$em_adr2="Université Toulouse III - Paul Sabatier";
$em_adr3="31062 Toulouse cedex 9";
$tresorier="Sébastien Déjean"; // nom du trésorier
$telfax="+33(0)5 61 55 69 16/+33(0)5 61 55 60 89"; // téléphone et fax du pôle finance
$tres_email="sebastien.dejean@math.univ-toulouse.fr"; // email du pôle finance
$pres_co="Christine Thomas-Agnan"; // nom du président du CO
?>
