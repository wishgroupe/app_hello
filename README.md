The Web root directory is `www`

# Composer Install 

Please follow this tutorial :

https://getcomposer.org/download/

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === 'aa96f26c2b67226a324c27919f1eb05f21c248b987e6195cad9690d5c1ff713d53020a02ac8c217dbf90a7eacc9d141d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=/usr/bin --filename=composer
rm composer-setup.php
```

Then launch `vendor` directory install
```
composer install
```

`app/boondmanager/configs/boondmanager.conf.ini`  
- Rename file as `boondmanager.ini`  
- Replace following variables :  
	#{{DB_SERVER}}  
	#{{DB_PORT}}  
	#{{DB_USER}}  
	#{{DB_PASSWORD}}  
	#{{DB_DATABASE}}  
	#{{APP_URL}}  
    #{{APP_REFERENCE}}  
    #{{APP_KEY}}  

`app/boondmanager/databases/apps/hello/hello.sql` :  
- SQL script to execute on your database server
