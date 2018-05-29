LaFrite 

Voici le projet d'une équipe innovante et aux multiples qualités

Pour débuter vous devez Installer PHP, apache, MySQL et composer. Attention à vérifier que PHP 7.2 soit installé.

1 - Téléchargez le dossier laFrite - git clone

2 - Installer composer :

Sur mac : $ curl -sS https://getcomposer.org/installer | php Sur windows : se rendre sur https://getcomposer.org/download/ et télécharger composer 3 - Exécutez la commande à l'intérieur du dossier depuis votre terminal : composer install

4 - Dans .env modifié la Database DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name Changez votre db_user et db_password selon votre machine et apellez votre db_name "laFrite" Varie selon votre type de machine ( windows - mac )

Toujours dans .env modifier  ###> symfony/swiftmailer-bundle ###
et à la ligne "MAILER_URL=" on rajoute gmail://lafrite.labarquette@gmail.com:labarquette@localhost


5 - Démarrer SQL Effectuez les commandes suivantes :

php bin/console doctrine:database:create . Création de la base de donnée

php bin/console doctrine:database:update —force / Mise à jour de la base de donnée

php bin/console doctrine:fixtures:load / Création des fixtures dans la base

6 - Pour se connecter en tant qu'admin rajoutez /admin dans l'url puis connectez vous

username : admin password : admin

7 - Faite dans votre terminal $ php bin/console server:start pour lancer les serveurs.

Et débutez votre jeu de dettes
