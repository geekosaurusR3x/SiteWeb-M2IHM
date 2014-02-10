/*
Title: Article sur Owncloud
Description: Un article décrivant l'utilisation d'Owncloud
Tags: article,owncloud,archlinux
Author: Skad
Date: 05/02/2013
*/

![Owncloud logo](http://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/OwnCloud2-Logo.svg/96px-OwnCloud2-Logo.svg.png "Owncloud logo")

Alors voilà, la problématique était relativement simple et commune.

Je lis pas mal de comics en numérique et ma vieille tablette Transformer première du nom (tf101) est assez pratique pour cela.  
Néanmoins, je suis assez fainéant pour ne pas apprécier devoir copier/coller les archives sur la SDcard (surtout qu'ils sont stoqués sur un serveur dans mon réseau pour centraliser tout ça).

Donc j'avais pas vraiment d'options :  

* Soit j'utilise un hébergeur comme Google Drive ou DropBox (pour ne citer qu'eux)  
* Soit j'utilise un système de VPN et je monte un partage Samba de mon serveur par le réseau  
* Soit je monte mon propre système de partage type Cloud

J'ai choisi la troisième solution et je vais faire mon retour d'expérience là-dessus.

##Installation d'Owncloud


J'avais déjà à ma disposition un serveur web ouvert vers l'extérieur qui me permettait de montrer à des amis quelques travaux web realisés  
(je passe donc sur l'installation d'un server LAMP, mais cela pourra faire l'objet d'un autre article plus tard).  

On installe:  `sudo pacman -S owncloud`  
Les données seront installées dans : `/usr/share/webbapps/owncloud` et la config dans `/etc/webbapps/owncloud`.  
Passons à la config (c'est souvent là que ça devient marrant) :   

###Mysql  

Pour stocker les infos, **Owncloud** a besoin d'utiliser une base de données : Mysql ou Sqlite.  
J'ai choisi Mysql, vu que ça tournait déjà sur ma machine.

Ajout d'un utilisateur juste pour **Owncloud** et lui donner des droits sur la base owncloud en local :  
`GRANT ALL PRIVILEGES ON owncloud.* TO 'owncloud'@localhost IDENTIFIED BY 'mot_de_passe';`

###PHP

Ensuite, **Owncloud** nécessite plusieurs extensions : 

* gd.so
* xmlrpc.so
* zip.so
* iconv.so

Pour les activer, il suffit d'ouvrir php.ini `sudo nano /etc/php/php.ini` et de décommenter les lignes `extension=*ma lib*`  
On fait de même avec :

* mysql.so
* mysqli.so
* pdo_mysql.so

Voilà, c'est fini pour la partie **PHP**.

###Apache


Alors là encore, un choix à faire :

* utilisation des vhost
* faire un simple lien dans le dossier

J'ai choisi le vhost et je vous renvoie [ici](http://lxl.io/apache-subdomain "Define subdomains") pour comprendre les sous-domaines.

Ensuite, il suffit de copier le fichier de virtual host d'**OwnCloud** qui se trouve dans le dossier de configuration :  
`sudo cp /etc/webapps/owncloud/apache.example.conf /etc/httpd/conf/extra/owncloud.conf`  
d'éditer le fichier de config d'apache :  
`sudo nano /etc/httpd/conf/extra/owncloud.conf`  
et de le coller dedans :  
`Include /etc/httpd/conf/extra/owncloud.conf`

(ne pas oublier d'ajouter l'entrée dns chez son registrar)

###Onwcloud

Bon jusque-là c'était la config de l'environnement. Maintenant passons à la config d'**Owncloud** en lui-même.  
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

La balise `ServerName` désigne l'url qui va être demandée par le client.  
Le `<Directory "/usr/share/webapps/owncloud">` désigne les règles pour le dossier d'**Owncloud**.
(!!! Ne pas oublier de surcharger la valeur php `php_admin_value open_basedir` avec ceci : `/tmp/:/usr/share/pear/:/usr/share/webapps/:/etc/webapps/owncloud/` sinon php ne voudra rien savoir et sortira une erreur : `open_basedir restriction in effect. File(/usr/share/webapps/*.php) is not within the allowed path(s):`)

Je crois qu'après tout ça, la configuration est finie et on peut déjà accéder à notre **Owncloud**.
[http://owncloud.localhost](http://owncloud.localhost "Owncloud")  
Pour le reste, il suffit de se laisser porter par la page de config qui est assez simple.

Après, pour connecter les clients, il suffira d'utiliser notre entrée dns comme url de serveur `owncloud.host.com`.

###Client Pc
Sous ArcheLinux, il a un client officiel présent dans les dépôts aur `sudo yaourt owncloud-client`.  
Après l'installation, il suffit de rentrer le login et le mot de passe définis sur le serveur et ça va se mettre à synchroniser tout seul.
![Owncloud CLient Linux](http://owncloud.org/wp-content/uploads/2012/03/linux3.png "Owncloud CLient Linux")

###Client Android
J'ai choisi le client officiel que l'on trouve [ici](https://play.google.com/store/apps/details?id=com.owncloud.android "Owncloud official client app"), mais il en existe pas mal d'autres.
Grâce au client android, je peux enfin lire en live mes comics directement sur ma tablette, que je sois dans mon lit ou alors à la fac entre deux cours.
![Owncloud connection Android](https://lh6.ggpht.com/tyWNeXaQN5qsXDEfirYwHzHvTPX5C2KfSprz7iRLDTAxBpt-J7Kwp0VUjAMih059zYJ9=h900-rw "Oncloud connection Android")  

Et voilà, vous avez votre propre cloud et au moins vous pourrez stocker beaucoup plus que ce les hébergeurs ne vous proposent!