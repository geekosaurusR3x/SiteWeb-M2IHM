/*
Title: Article sur Owncloud
Description: Un article decrivant l'utilisation d'Owncloud
Tags: article,owncloud,archlinux
Author: Skad
Date: 05/02/2013
*/

#Owncould : un exemple d'utilisation
![Owncloud logo](http://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/OwnCloud2-Logo.svg/96px-OwnCloud2-Logo.svg.png "Owncloud logo")

Alors voila la problématique était relativement simple et commue.

Je lis pas mal de comics en numérique et ma vielle tablette Transformer premiere du nom (tf101) est assez pratique pour cela.  
Neamoins je suis assez fainéant pour ne pas apprécier devoir copier/coller les archives sur la SDcard (surtout qu'ils sont stoqués sur un serveur dans mon réseau pour centraliser tout ca.

Donc j'avais pas vraiment d'options :  

* Soit j'utilise un hébergeur comme Google Drive ou DropBox (pour ne citer qu'eux)  
* Soit j'utilise un système de VPN et je monte un partage Samba de mon serveur par le reseau  
* Soit je monte mon propre système de partage type Cloud

J'ai choisi la 3ieme solution et je vais faire mon retour d'experience là dessus.

##Instation d'Owncloud


J'avais déjà à ma disposition un serveur web ouvert vers l'exterieur qui me permetait de montrer à des amis quelques travaux web realisés  
(je passe donc sur l'instalation d'un server LAMP mais cela poura faire l'object d'un autre article plus tard)  

on installe:  `sudo pacman -S owncloud`  
Les données seront instalées dans : `/usr/share/webbapps/owncloud` et la config dans `/etc/webbapps/owncloud`  

Passons à la config (c'est souvent la que ca devient marrant) :   

###Mysql  

Pour stoquer les infos, **Owncloud** a besoin d'utiliser une base de donnée : Mysql ou Sqlite.  
J'ai choisi Mysql vue que ca tournait deja sur ma machine.

Ajout d'un utilisateur juste pour **Owncloud** et lui donner des droits sur la base owncloud en locale :  
`GRANT ALL PRIVILEGES ON owncloud.* TO 'owncloud'@localhost IDENTIFIED BY 'mot_de_passe';`

###PHP

Ensuite **Owncloud** nécessite plusieurs extensions : 

* gd.so
* xmlrpc.so
* zip.so
* iconv.so

Pour les activer il suffit d'ouvrir php.ini `sudo nano /etc/php/php.ini` et de décomenter les lignes `extension=*ma lib*`  
Onfait de meme avec :

* mysql.so
* mysqli.so
* pdo_mysql.so

Voila c'est finit pour la partie **PHP**

###Apache


Alors la encore un choix a faire :

* utilisation des vhost
* faire un simple lien dans le dossier

j'ai choisi le vhost et je vous renvois [ici](http://lxl.io/apache-subdomain "Define subdomains") pour comprendre les sous domaines

Ensuite il suffi de copier le fichier de virtual host d'**OwnCloud** qui se trouver dans le dossier de configuration :  
`sudo cp /etc/webapps/owncloud/apache.example.conf /etc/httpd/conf/extra/owncloud.conf`  
d'editer le fichier de config d'appache :  
`sudo nano /etc/httpd/conf/extra/owncloud.conf`  
et de coller dedans :  
`Include /etc/httpd/conf/extra/owncloud.conf`

(ne pas oublier d'ajouter l'entrée dns chez son registrar)

###Onwcloud

Bon jusque la c'était la config de l'environement. Maintenant passont à la config d'**Owncloud** en lui même  
On commence par éditer le virtual host pour pointer au bon endroit :  

    <VirtualHost *:80>
        ServerAdmin yourmail@host.com
        DocumentRoot /usr/share/webapps/owncloud
        ServerName owncloud.host.com
        ErrorLog logs/owncloud.host.com.error_log
        CustomLog logs/owncloud.host.com.access_log common
        <Directory "/usr/share/webapps/owncloud">
            Options Indexes FollowSymLinks
            AllowOverride All
            Order allow,deny
            Allow from all
            php_admin_value open_basedir /tmp/:/usr/share/pear/:/usr/share/webapps/:/etc/webapps/owncloud/
        </Directory>
    </VirtualHost>
    
