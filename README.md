The Web root directory is `www`

# Composer Install 

Please follow this tutorial :

https://getcomposer.org/download/

Then launch `vendor` directory install
```
composer install
```

`app/hello/configs/hello.conf.ini`  
- Rename file as `hello.ini`  
- Replace following variables :  
    #{{API_URL}}  
	#{{DB_SERVER}}  
	#{{DB_PORT}}  
	#{{DB_USER}}  
	#{{DB_PASSWORD}}  
	#{{DB_DATABASE}}  
    #{{APP_INSTALLATION_CODE}}  
	#{{APP_URL}}  
    #{{APP_REFERENCE}}  
    #{{APP_KEY}}  

`app/hello/databases/hello.sql` :  
- SQL script to execute on your database server
