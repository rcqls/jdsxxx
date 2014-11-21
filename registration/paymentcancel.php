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
	echo "<meta name='description' content='Inscription ".$eventname."'>";
	echo "<title>Inscription | ".$eventname."</title>";
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
					echo "<h2>Formulaire d'inscription pour la manifestation ".$eventname."</h2>
					Retour sur le <a href='".$website_url."'>site principal de la manifestation</a>.";
	?>
				</div> <!-- media-body -->
			</div> <!-- media -->
		</div> <!-- well -->

<?php
require_once("../inc/config.php");
require_once("../inc/connect.php");
if (isset($_REQUEST['ref'])) {
print ("Vous avez annulé le paiement de la commande référence <strong>".$_REQUEST['ref']."</strong>.
Vous êtes toutefois enregistré comme participant aux JdS : vous pourrez remplir à nouveau ultérieurement le formulaire d'inscription pour mettre à jour votre inscription, procéder au paiement CB ou modifier votre mode de paiement.
En cas de difficulté ou de question, merci de contacter <a href='mailto:".$contact_email."'>".$contact_email."</a>.");
}
print("<center><a href='".$website_url."'>Retour sur la page d'accueil des JdS</a></center>");
print("<center><a href='".$current_url."'>Retour au formulaire d'inscription</a></center>");
?>
		</div> <!-- container -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../inc/js/bootstrap.js"></script>
	</body>
</html>