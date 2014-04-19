Repository 'jdsxxx'
======

by _Nathalie V²_

_Programmes utilisés pour le déploiement des sites des conférences de la SFdS._

### Liste des programmes :

* ```config.php``` et ```config-en.php``` : scripts de personnalisation pour les webmestres ; dans le dossier payment de wordpress
* ```connect.php``` : script de connexion à la table où est stocké l'instance de myreview et le formulaire d'inscription aux JdS ; dans le dossier payment de wordpress
* ```create_registration_table.php``` : scripts de création de la table pour les inscriptions (utilise config.php et connect.php) ; dans le dossier payment de wordpress
* ```wp_inscriptions.php, wp-inscription_en.php``` : script inclus dans la page "formulaire d'inscription" du site wordpress ; gère le formulaire d'inscription ; utilise ```config.php```, connect.php et ```send_invoice.php``` et nécessite d'avoir exécuté ```create_registration_table.php```
* ```paymentautoresponse.php``` : fichier d'exécution automatique au retour de la passerelle de paiement ; utilise config.php et connect.php ; dans le dossier payment du site wordpress ;
* ```showabstract.php``` : script pour montrer les résumés cours ; utilisé pour l'extraction automatique du programme
* ```wp_paymentcancel.php```, ```wp_paymentresponse.php```, ```wp_paymentcancel_en.php``` et ```wp_paymentresponse_en.php``` : scripts de réponses utilisateurs (version française et anglaise) de la passerelle de paiement de la SFdS ; inclus directement dans un post wordpress ; utilisent ```config.php``` et ```connect.php```
* ```send_invoice.php``` : script de génération de la facture PDF envoyée par email (et stockée aussi dans le répertoire facture/docs du site wordpress) ; dans le dossier facture du site wordpress
* ```wp_valid_payment.php``` : scripts inclus dans la page "Valider un paiement" du site wordpress et permettant de valider un paiement à réception pour mettre à jour la base de données ; utilise les scripts connect.php et config.php
* ```wp_list_inscrits.php``` : scripts inclus dans la page "Liste des inscrits" du site wordpress permettant de récupérer la liste des inscrits : la liste est exporté au format csv dans le dossier inscrits du site wordpress et numérotée par date
* ```wp_program.php``` et ```wp_program_en.php``` : scripts inclus dans la page "Programme" du site wordpress permettant de générer le programme à partir de myreview ; nécessite les fichiers ```connect.php``` et ```config.php``` et que la base de données de myreview soit commune à celle de la table des inscriptions