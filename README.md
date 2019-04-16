The Web root directory is `www`

# Composer Install 

Please follow this tutorial :

https://getcomposer.org/download/

Then launch `vendor` directory install
```
composer install
```

# Set application's config
`app/hello/configs/hello.conf.ini`  
- Rename file as `hello.ini`  
- Replace following variables :

|  variable | description | example |
| --- | --- | --- |
| **{{API_URL}}** |  base url to boondmanager's api | `https://ui.boondmanager.com/api` |
| **{{DB_SERVER}}** | Mysql server address | `127.0.0.1` |
| **{{DB_PORT}}** | Mysql port | `3306` |
| **{{DB_USER}}** | Mysql user | `app_hello` |
| **{{DB_PASSWORD}}** | Mysql password | `myPassword` |
| **{{DB_DATABASE}}** | Mysql database | `app_hello` |
| **{{APP_INSTALLATION_CODE}}** | App's internal reference | `API100001` |
| **{{APP_URL}}** | App's base url for apis (same as the one given in boondmanager market place | `https://myhost/labs_hello` |
| **{{APP_REFERENCE}}** | App's reference in boondmanager marketPlace | `hellolabs` |
| **{{APP_KEY}}** | App's' key in boondmanager marketPlace | `123600cb12b1234a3123e` |

# Install the database

create a database on your server and then  install the following script on your database server `app/hello/databases/hello.sql`

# Set folders

create a folder tmp and give a right access to www-data on it

```
mkdir tmp
chmod 755 tmp
chown www-data:www-data tmp
``` 


