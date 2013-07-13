# Brand new awesome application!
How to setup project from stratch:
1. go to terminal and run: git clone https://github.com/boo1ean/network.git
2. cd to dirrectory with project, than /composer.phar install and finally /composer.phar install 
3.  cd /etc/apache2/sites-available;
4. sudo gedit mysite;
5. 	<VirtualHost *:80>
		ServerName yourName
		DocumentRoot /home/USER/folderWithClonedRep/public/
		<Directory /home/USER/folderWithClonedRep/public/>
			DirectoryIndex index.php
			AllowOverride All
			Order allow,deny
			Allow from all
		</Directory>
	</VirtualHost>
6. sudo gedit /etc/hosts
      Add to file: 127.0.0.1      yourName;
7. sudo a2ensite mysite;
8. sudo a2enmod rewrite;
9. sudo service apache2 restart;
