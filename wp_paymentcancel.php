[php]
require_once("payment/config.php");
require_once("payment/connect.php");
if (isset($_REQUEST['ref'])) {
print ("Vous avez annul� le paiement de la commande r�f�rence <strong>".$_REQUEST['ref']."</strong>.
Vous �tes toutefois enregistr� comme participant aux JdS : vous pourrez remplir � nouveau ult�rieurement le formulaire d'inscription pour mettre � jour votre inscription, proc�der au paiement CB ou modifier votre mode de paiement.
En cas de difficult� ou de question, merci de contacter <a href='mailto:".$contact_email."'>".$contact_email."</a>.");
}
print("<center><a href='".$website_url."'>Retour sur la page d'accueil des JdS</a></center>");
print("<center><a href='".$current_url."'>Retour au formulaire d'inscription</a></center>");
[/php]