[php]
require_once("payment/config.php");
require_once("payment/connect.php");
if (isset($_REQUEST['ref']) && isset($_REQUEST['res'])) {
if ($_REQUEST['res']==1) {
print ("Merci pour votre inscription.
Le paiement de la commande r�f�rence <strong>".$_REQUEST['ref']."</strong> est enregistr�. Vous allez recevoir un email de confirmation de votre inscription.");
} else {
print ("Le paiement de la commande r�f�rence <strong>".$_REQUEST['ref']."</strong> a �t� refus�.
Vous allez recevoir un email r�capitulatif de votre inscription : nous vous sugg�rons de proc�der � nouveau � l'inscription pour retenter un paiement par carte bancaire ou bien pour modifier votre mode de paiement.
En cas de difficult� ou de question, merci de contacter <a href='mailto:".$contact_email."'>".$contact_email."</a>.");
}
}
print("<center><a href='".$website_url."'>Retour sur la page d'accueil des JdS</a></center>");
print("<center><a href='".$current_url."'>Retour au formulaire d'inscription</a></center>");
[/php]