# MANUEL
A l'attention d'un CIR ou d'un CSI/EST/CENT déterminé à faire du full-stack, 
voici quelques étapes et explications succintes pour setup le système de comptage d'heure que j'ai élaboré.

### CHOIX SYSTEME
En accord avec les cours de CIR2 sur le backend et le serveur web, je conseille de déployer un environnement apache2 avec php et postgresql. 
Une machine virtuelle/container sur un serveur type OVH ou auto-herbergé fera largement l'affaire pour ce type d'application.

### INSTALLATION
1. Créez dans postgresql une base de donnée nommée `bdehours`. Assurez-vous que l'utilisateur `postgres` a bien accès à cette bdd et définissez le mot de passe d'accès à `Isen44N`.
Le port d'accès à la base de données sera `5432` (bien vérifier si c'est celui par défaut, au besoin le changer dans les constantes PHP ou dans le fichier de configuration postgresql). Si vous souhaitez modifier ces constantes et utiliser vos propres users/mdp/noms/port,
alors il faudra aussi penser à modifier les constantes suivantes en PHP dans le fichier `/src/appli/utils.php` avec les vôtres:
```php
const DBNAME = "bdehours";
const PORT = 5432;
const USER = "postgres";
const PASS = "Isen44N";
```

2. Veillez à bien activer le module PHP et Rewrite de apache2: `sudo a2enmod phpX.X` (X.X étant la version insallée de PHP, voir /etc/apache2/mods-available) & `sudo a2enmod rewrite`, et à configurer le fichier .conf du serveur web avec à minima les directives suivantes
```
<VirtualHost *:80>
	DocumentRoot path/to/folder
	DirectoryIndex index.php
	    <Directory path/to/folder>
                DirectoryIndex index.php
                Options +Indexes +FollowSymLinks
                AllowOverride All
                Require all granted
                RewriteEngine On


                RewriteCond %{REQUEST_FILENAME} -f [OR]
                RewriteCond %{REQUEST_FILENAME} -d
                RewriteRule ^(.+) - [PT,L]

                RewriteRule ^assets/(.*)$ public/assets/$1 [L]

                RewriteBase /
                RewriteRule ^ index.php
        </Directory>
</VirtualHost>
```
Avec `path/to/folder` le chemin d'accès vers la racine du site web (ex: /var/www/html/bdeweb). Vous pouvez rajouter des directives si souhaitez mais celles-ci sont absolument mandatoires pour faire fonctionner le routeur php.

3. Éxecutez le script SQL `/ressources/CTBDE.sql`au sein de la base de donnée créée à l'étape 1 afin de la remplir de tables. Le fichier contient également quelques commentaires pour expliquer ce que font chaque tables et leurs champs associés.

4. L'étape la plus fun, il faut désormais peupler manuellement (et oui j'avais la flemme de faire un script sql) la base de données. Les commentaires du fichier `/ressources/CTBDE.sql`seront d'une grande utilité pour cela. 
Il faut donc créer les Speciality souhaitée puis les Users dans les tables correspondantes. Une attention particullière doit être portée sur les champs `mail`et `password` de la table Users puisqu'ils permettront à chaque membre de se connecter à son espace personnel.
Le `mail` renseigné dans la table sera utilisé pour se connecter, et le `password` __doit au préalable avoir été hash avant d'être inséré__! Il ne faut pas insérer de mot de passe 'en brut' ou 'en clair' dans la table Users, cela ne marchera pas! Je recommande d'utiliser ce site: https://onlinephp.io/password-hash et de demander à chaque membre d'envoyer leur mot de passe hashé au sysadmin (celui qui s'occupera d'installer tout ce bazard). Les paramètres par défaut du site suffiront, attention tout de même à bien sélectionner la bonne version PHP. Une fois cette étape faite, félicitation vous avez fait le plus long, la mauvaise nouvelle c'est que je peux vous garantir qu'un membre du BDE aura forcément oublié son mot de passe d'ici la fin de l'année, et que vous devrez donc recalculer le hash avec lui et le modifier manuellement dans la base de donnée.

*Addendum sur le nom de domaine de la machine:* Si vous choisissez d'attribuer un nom de domaine à la machine afin d'y accéder (ce qui est fort probable), le nom de domaine de base ne vous permettra pas d'accéder au site. Pour accéder à la page d'accueil, il faut spécifiquement rentrer dans l'URL le nom de domaine + `/accueil` (une simple histoire de routage). Si par exemple le nom de domaine du site est `examplebde.fr` alors pour accéder à la page d'accueil il faudra entrer comme URL `examplebde.fr/accueil`. Si vous entrez juste le nom de domaine de base, vous tomberez sur une page vierge non redirigée (je pense qu'une âme dévouée saura résoudre ce problème en un tour de main).

## Docker

### Utilisation avec Docker Compose

Pour démarrer l'application en utilisant Docker Compose:

```bash
docker compose up -d
```
L'application sera accessible à l'adresse http://localhost/accueil

### Architecture Docker
Le projet utilise:

- Un conteneur web basé sur Alpine Linux avec Apache et PHP 8.2
- Un conteneur PostgreSQL pour la base de données
- Image depuis GitHub Container Registry
- L'image est automatiquement publiée sur GitHub Container Registry à chaque push sur la branche main.

Pour l'utiliser:
````BASH
docker pull ghcr.io/breizhhardware/site-comptage-heure:latest
````

Variables d'environnement
Le conteneur PostgreSQL utilise les variables suivantes:
POSTGRES_DB: bdehours
POSTGRES_USER: postgres
POSTGRES_PASSWORD: Isen44N

### FONCTIONNEMENT
L'utilisation du site une fois déployé est très simple, l'utilisateur se connect à son compte et accès à son "Espace Personnel". Ici il peut entrer la date à laquelle il a travaillé, la durée du temps de travail (timeslot) et enfin un descriptif du travail réalisé. Il peut aussi consulter ses derniers ajouts et leurs statuts (Validé, Refusé, En attente). Un temps de travail est véritablement calculé dans le total d'un utilisateur QUE lorsque un administraterur a validé le timeslot. Les administrateurs ont ainsi en plus accès à un onglet spécifique qui leur permet de consulter les heures en attente de validation, et de les refuser/valider. Cet onglet permet également de consulter l'historique des autres utilisateurs. Un administrateur est défini comme tel dans la table Users grâce au champ `is_admin`.

Voilà tout, le site n'est pas parfait mais fera très bien l'affaire pour son application finale. Un utilisateur déterminé pourra ajouter un système de création de compte ou de récupération d'identifiant afin de ne pas avoir à les entrer/changer manuellement dans la base de données. Il est également possible d'ajouter un fichier exemple d'insertion SQL pour faciliter la tâche aux futurs repreneurs. Enfin un utilisateur très déterminé peut s'amuser à réecrire l'API du routeur en AJAX ou avec FETCH. Have fun ;) !

Si vous avez des questions, n'hésitez pas à me contacter: nirina.macon@gmail.com. J'invite chaque personne ayant contribué à ce projet à ajouter son épitaphe ci-dessous, j'espère que la liste s'aggrandira ;).
Nirina MACON, V1, 2024-2025, Odyssey BDE