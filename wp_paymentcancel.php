[php]
require_once("payment/config.php");
require_once("payment/connect.php");
if (isset($_REQUEST['ref'])) {
print ("Vous avez annulé le paiement de la commande référence <strong>".$_REQUEST['ref']."</strong>.
Vous êtes toutefois enregistré comme participant aux JdS : vous pourrez remplir à nouveau ultérieurement le formulaire d'inscription pour mettre à jour votre inscription, procéder au paiement CB ou modifier votre mode de paiement.
En cas de difficulté ou de question, merci de contacter <a href='mailto:".$contact_email."'>".$contact_email."</a>.");
}
print("<center><a href='".$website_url."'>Retour sur la page d'accueil des JdS</a></center>");
print("<center><a href='".$current_url."'>Retour au formulaire d'inscription</a></center>");
[/php]