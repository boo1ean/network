# Brand new awesome application!

How to setup project from stratch:

1. go to terminal and run: git clone https://github.com/boo1ean/network.git

2.  cd /etc/apache2/sites-available;
3. sudo gedit mysite;
4. 	<VirtualHost *:80>
		ServerName yourName
		DocumentRoot /home/USER/folderWithClonedRep/public/
		<Directory /home/USER/folderWithClonedRep/public/>
			DirectoryIndex index.php
			AllowOverride All
			Order allow,deny
			Allow from all
		</Directory>
	</VirtualHost>
5. sudo gedit /etc/hosts
      Add to file: 127.0.0.1      yourName;
6. sudo a2ensite mysite;
7. sudo a2enmod rewrite;
8. sudo service apache2 restart;

