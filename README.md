Repository 'jdsxxx'
======

by _Nathalie V²_

_Programmes utilisés pour le déploiement des sites des conférences de la SFdS._

### Liste des programmes :

* ```config.php``` et ```config-en.php``` : scripts de personnalisation pour les webmestres ; dans le dossier ```inc``` de wordpress
* ```connect.php``` : script de connexion à la table où est stocké l'instance de myreview et le formulaire d'inscription aux JdS ; dans le dossier ```inc``` de wordpress
* ```create_registration_table.php``` : scripts de création de la table pour les inscriptions (utilise ```config.php``` et ```connect.php```) ; dans le dossier ```registration``` de wordpress
* ```index.php``` : script pour l'extraction automatique du programme ; dans le dossier ```prog``` du site wordpress ; utilise les scripts ```showabstract.php```, ```config.php``` et ```connect.php```
* ```index.php``` : script pour le formulaire d'inscription ; dans le dossier ```registration``` du site wordpress ; utilise les scripts ```config.php``` et ```connect.php``` ;
* ```index.php``` : script pour la validation des paiements et l'extraction du fichier d'inscrits ; utilise les scripts ```config.php``` et ```connect.php``` ;
* ```paymentautoresponse.php``` : fichier d'exécution automatique au retour de la passerelle de paiement ; utilise ```config.php``` et ```connect.php``` ; dans le dossier ```registration``` du site wordpress et nécessite d'avoir exécuté ```create_registration_table.php``` ;
* ```paymentresponse.php``` :  scripts de réponses utilisateurs (version française et anglaise) de la passerelle de paiement de la SFdS ; utilise ```config.php``` et ```connect.php``` ; dans le dossier ```registration``` du site wordpress ;
* ```paymentcancel.php``` :  scripts de réponses utilisateurs (version française et anglaise) de la passerelle de paiement de la SFdS ; utilise ```config.php``` et ```connect.php``` ; dans le dossier ```registration``` du site wordpress ;
* ```showabstract.php``` : script pour montrer les résumés cours ; utilisé pour l'extraction automatique du programme ; dans le dossier ```prog``` du site wordpress ;
* ```send_invoice.php``` : script de génération de la facture PDF envoyée par email (et stockée aussi dans le répertoire ```facture/docs``` du site wordpress) ; dans le dossier ```facture``` du site wordpress.