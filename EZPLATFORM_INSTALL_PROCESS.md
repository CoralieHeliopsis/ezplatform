## Installation eZ Platform avec Docker
 
#### Processus d'installation d'eZ Platform

Avant tout,   
* Il faut vérifier d'avoir les extensions PHP suivantes : intl et curl.  
  *  Pour Ubuntu 16.04 => php 7.0 =>   
    `sudo apt-get install php7.0-intl`   
    `sudo apt-get install php-curl`  

  * Pour Ubuntu 14.04 => php 5 =>  
    `sudo apt-get install php5-intl`  
    `sudo apt-get install php5-curl`  
    
* Il faut s'assurer que composer est installé globalement.
  * Si ce n'est pas le cas =>  
     `php -r "readfile('https://getcomposer.org/installer');" | php`  
     `mv composer.phar /usr/local/bin/composer`  
     
Puis,  
1. Allez dans workspace.

2. Créez un nouveau projet eZ Platform.  
   _composer installe alors eZ Platform dans le workspace, dans le dossier <nom_du_projet>_.   
    `composer create-project --no-dev ezsystems/ezplatform <nom_du_projet>`    
    
3. Renseignez les données demandées dans le terminal pour établir la base de données.

4. L'installation continue et les assets sont installés.  

=> On arrive alors sur une "page d'accueil" d'eZ platform dans le terminal.

5. Allez dans le dossier <nom_du_projet>.

6. Créez la bdd en ligne de commande.  
     `php app/console doctrine:database:create`
     
7. Lancez l'installation d'eZ Platform pour le projet.  
   _Cela va lancer une installation d'un projet vierge dans un environnement de dev par défaut_.  
    `php app/console ezplatform:install clean`  
    
8. Appliquez les droits sur les dossiers.  
    `rm -rf app/cache/*  app/logs/*`  
    `sudo chown -R www-data:www-data app/cache app/logs web`   
    `sudo find {app/{cache,logs},web} -type d | xargs sudo chmod -R 775` 777  
    `sudo find {app/{cache,logs},web} -type f | xargs sudo chmod -R 664` 666
  
     
9. Déclarez un virtualhost.  
    `sudo cp ~/workspace/<nom_du_projet>/doc/apache2/vhost.template /etc/apache2/sites-available/<nom_du_projet>.conf`
      
10. Éditez le fichier placé dans /etc/apache2/sites-available et effectuez les actions suivantes.  
    
    a) remplacer %placeholders% avec les données nécessaires pour un environnement de dev
    - %IP_ADDRESS%:%PORT%    => *:80
    - %HOST_NAME%    => <nom_du_projet>.<dev>.rocks
    - %HOST_ALIAS%    => peut être supprimé ou commenté (si besoin par la suite)
    - DocumentRoot %BASEDIR%/web    => DocumentRoot /home/<dev>/workspace/<nom_du_projet>/web           
    - LimitRequestBody %BODY_SIZE_LIMIT%    => LimitRequestBody 49152
    - TimeOut %TIMEOUT%    => TimeOut 90
    - Directory %BASEDIR%/web    => Directory /home/<dev>/workspace/<nom_du_projet>/web         
    
    b) décommenter la ligne suivante 
    - `#Require all granted`
    
    c) décommenter la ligne suivante 
    - `#if[SYMFONY_ENV] SetEnvIf Request_URI ".*" SYMFONY_ENV=%SYMFONY_ENV%`
    
    d) la remplacer par
    - `SetEnvIf Request_URI ".*" SYMFONY_ENV=dev`
    
    e) sortir « RewriteEngine On » de «<IfModule mod_rewrite.c> » et le placer juste avant comme ceci.
    - `# As we require ´mod_rewrite´  this is on purpose not placed in a <IfModule mod_rewrite.c> block`  
       `RewriteEngine On`  
       
11. Activez le virtualhost.  
     `sudo a2ensite <nom_du_projet>`

12. Redémarrez apache.  
    `sudo service apache2 reload` ou `sudo service apache2 stop` et `sudo service apache2 start`

13. Éditez /etc/php/7.0/cli/php.ini et allez renseigner le date.timezone   
        => mettre « Europe/Paris » après le = et décommenter la ligne.

14. Redémarrer apache

15. Vérifiez la configuration.  
    `php app/check.php`  

16. Allez à l’adresse du site (<nom_du_projet>.<dev>.rocks).  
    __*NOTE*__ :  
    accès au backoffice: <nom_du_projet>.<dev>.rocks/ez  
        identifiant - mot de passe : admin - publish (par défaut)
        
        
#### Processus d'installation de Docker/Docker-compose

1. Vérifiez la version du kernel (=> elle doit au moins être 3.10-generic) :  
     `uname -r`  

2. Mettre à jour si docker a déjà été installé :  
    `sudo apt-get update`  
    `sudo apt-get install apt-transport-https ca-certificates`

3. Ajoutez la nouvelle clé GPG :  
    `sudo apt-key adv --keyserver hkp://ha.pool.sks-keyservers.net:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D`

4. Trouvez l’entrée pour que APT puisse rechercher des paquets :  
    => ubuntu 14.04 => deb https://apt.dockerproject.org/repo ubuntu-trusty main  
    => ubuntu 16.04 => deb https://apt.dockerproject.org/repo ubuntu-xenial main

5. Faire cette commande :  
    => ubuntu 14.04 => `echo «deb https://apt.dockerproject.org/repo ubuntu-trusty main » | sudo tee /etc/apt/sources.list.d/docker.list`  
    => ubuntu 16.04 => `echo «deb https://apt.dockerproject.org/repo ubuntu-xenial main » | sudo tee /etc/apt/sources.list.d/docker.list`

6. Mettre à jour :  
     `sudo apt-get update`

7. Vérifiez que APT prend les mises à jour depuis le bon repository :  
     `apt-cache policy docker-engine`

8. Installez « linux-image-extra-* » :    
    `sudo apt-get update`    
    `sudo apt-get install linux-image-extra-$(uname -r) linux-image-extra-virtual`

9. Mettre à jour :   
    `sudo apt-get update`  

10. Installez docker :  
    `sudo apt-get install docker-engine`

11. Démarrez docker :  
     `sudo service docker start`

12. Vérifiez que docker est installé correctement :  
     `sudo docker run hello-world`

13. Installez docker-compose:   
     `sudo -i curl -L "https://github.com/docker/compose/releases/download/1.9.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose`   

14. Appliquez les droits :  
     `sudo chmod +x /usr/local/bin/docker-compose`

15. Vérifiez la version de docker-compose (=> au moins 1.7):   
     `docker-compose -v`   
     
16. Vérifiez la version de docker, si cela n’est pas déjà fait (=> au moins 1.10) :  
     `docker -v` 
     
#### Suite à l’installation de docker-compose :

1. Dans le « .env » à la racine du projet, changer la ligne 3 (passer de « prod » à « dev ») :   
     `COMPOSE_FILE=doc/docker-compose/base-dev.yml`

2. Allez dans le projet

3. Effectuez les opérations suivantes :  

    a) `echo "{\"github-oauth\":{\"github.com\":\"<readonly-github-token>\"}}" > auth.json`  
    b) `export COMPOSE_FILE=doc/docker-compose/base-dev.yml SYMFONY_ENV=dev SYMFONY_DEBUG=1`  
    c) `docker-compose -f doc/docker-compose/install.yml up --abort-on-container-exit`  
    d) `docker-compose up -d –force-recreate`
