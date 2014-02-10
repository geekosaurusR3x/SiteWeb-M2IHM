/*
Title: Owncould : un exemple d'utilisation
Description: Un article decrivant l'utilisation d'Owncloud
Tags: article,owncloud,archlinux
Author: Skad
Date: 02/05/2013
*/

![Owncloud logo](http://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/OwnCloud2-Logo.svg/96px-OwnCloud2-Logo.svg.png "Owncloud logo")

Alors voila la problématique était relativement simple et commue.

Je lis pas mal de comics en numérique et ma vielle tablette Transformer premiere du nom (tf101) est assez pratique pour cela.  
Neamoins je suis assez fainéant pour ne pas apprécier devoir copier/coller les archives sur la SDcard (surtout qu'ils sont stoqués sur un serveur dans mon réseau pour centraliser tout ça).

Donc j'avais pas vraiment d'options :  

* Soit j'utilise un hébergeur comme Google Drive ou DropBox (pour ne citer qu'eux)  
* Soit j'utilise un système de VPN et je monte un partage Samba de mon serveur par le reseau  
* Soit je monte mon propre système de partage type Cloud

J'ai choisi la 3ieme solution et je vais faire mon retour d'experience là dessus.

##Instation d'Owncloud


J'avais déjà à ma disposition un serveur web ouvert vers l'extérieur qui me permetait de montrer à des amis quelques travaux web realisés  
(je passe donc sur l'instalation d'un server LAMP mais cela poura faire l'object d'un autre article plus tard)  

on installe:  `sudo pacman -S owncloud`  
Les données seront instalées dans : `/usr/share/webbapps/owncloud` et la config dans `/etc/webbapps/owncloud`  

Passons à la config (c'est souvent là que ca devient marrant) :   

###Mysql  

Pour stoquer les infos, **Owncloud** a besoin d'utiliser une base de donnée : Mysql ou Sqlite.  
J'ai choisi Mysql vue que ca tournait deja sur ma machine.

Ajout d'un utilisateur juste pour **Owncloud** et lui donner des droits sur la base owncloud en local :  
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

j'ai choisi le vhost et je vous renvoie [ici](http://lxl.io/apache-subdomain "Define subdomains") pour comprendre les sous-domaines

Ensuite il suffit de copier le fichier de virtual host d'**OwnCloud** qui se trouve dans le dossier de configuration :  
`sudo cp /etc/webapps/owncloud/apache.example.conf /etc/httpd/conf/extra/owncloud.conf`  
d'éditer le fichier de config d'apache :  
`sudo nano /etc/httpd/conf/extra/owncloud.conf`  
et de coller dedans :  
`Include /etc/httpd/conf/extra/owncloud.conf`

(ne pas oublier d'ajouter l'entrée DNS chez son registrar)

###Onwcloud

Bon jusque là c'était la config de l'environement. Maintenant passons à la config d'**Owncloud** en lui-même  
On commence par éditer le virtual host pour le faire pointer au bon endroit :  

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

La balise `ServerName` désigne l'url qui va etre demandée par le client  
Le `<Directory "/usr/share/webapps/owncloud">` désigne les règles pour le dossier d'**Owncloud**
(!!! Ne pas oublier de surcharger la valeur php `php_admin_value open_basedir` avec ceci : `/tmp/:/usr/share/pear/:/usr/share/webapps/:/etc/webapps/owncloud/` sinon php ne voudra rien savoir sortira une erreure : `open_basedir restriction in effect. File(/usr/share/webapps/*.php) is not within the allowed path(s):`)

Bon je crois qu'après tout ça, la configration est finie et on peut déjà accéder à notre **Owncloud**
[http://owncloud.localhost](http://owncloud.localhost "Owncloud")  
Pour le reste il suffit de se laisser porter par la page de config qui est assez simple.

Après pour connecter les clients il suffira d'utiliser notre entrée DNS comme url de server `owncloud.host.com`

###Client Pc
Bon sous ArchLinux il a un client officiel présent dans les dépôts AUR `sudo yaourt owncloud-client`.  
Après instalation il suffit de rentrer le login et le mot de passe défini sur le serveur et ca va se metre à synchroniser tout seul.
![Owncloud CLient Linux](http://owncloud.org/wp-content/uploads/2012/03/linux3.png "Owncloud CLient Linux")

###Client Android
J'ai choisi le client officiel que l'on trouve [ici](https://play.google.com/store/apps/details?id=com.owncloud.android "Owncloud official client app") mais il en existe pas mal d'autres.
Grace au client Android je peut enfin lire en live mes comics directement sur ma tablette, que je sois dans mon lit ou à la fac entre deux cours.
![Owncloud connection Android](https://lh6.ggpht.com/tyWNeXaQN5qsXDEfirYwHzHvTPX5C2KfSprz7iRLDTAxBpt-J7Kwp0VUjAMih059zYJ9=h900-rw "Oncloud connection Android")  

Et voilà! Vous avez votre propre cloud et au moins vous pourrez stocker beaucoup plus que ce que les hébergeurs vous proposent
