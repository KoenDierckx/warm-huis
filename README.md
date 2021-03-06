
# Mijn Warm Huis

Mijn warm huis is a symfony application that allows a user to check the parameters of his house and get recommendations on how to improve it. It is an expansion of the original "Check je huis", which can be found here: https://github.com/StadGent/Check-Je-Huis/
 
## Requirements
Minimum PHP 7.0.0 is required and composer to perform the installation.
 
## Installation

The following steps can be followed to install the application
1. Clone this repository and go to the directory of the cloned repo
2. `composer install`
3. At the end of `composer install` fill in the correct parameters for the application such as database connection details.
4. `app/console doctrine:migrations:migrate`
5. `app/console cache:clear && app/console cache:warmup`
6. Dump the assets
  * in production environments dump the assets into public folder:
`app/console assetic:dump`
  * in dev environment symlink the assets: `app/console assets:install --symlink`
 
After the steps above are taken, make sure the application is reachable through a URL. Make sure the vhost points to 
the app's `web` folder.

## Configuration files

The main configuration files reside in `app/config` with `app/config/config.yml` as the base file.

The settings in this main file will be overridden with the config of the environment (e.g.: `app/config/config_prod.yml`).
  
The parameters from `app/config/parameters.yml` will then be replaced in the resulting config.

## Command line tools

Overview of the most used commands:

* `app/console -e=prod` or `app/console -e=prod`
    * for production, always explicitly set the environment to prod
* `app/console list` lists all available commands, optionally filtered by package
    * e.g.: `app/console list doctrine:migrations` only lists the doctrine migration commands
* `app/console -e=prod cache:clear && app/console -e=prod cache:warmup` clears and rebuilds basic bootstrap cache
* `app/console -e=prod assetic:dump` compiles css and js files and puts them in the public folder
* `app/console -e=prod doctrine:migrations:status` checks if the database is up-to-date
* `app/console -e=prod doctrine:migrations:migrate` update the database to the latest version
* `app/console -e=prod fos:user:change-password` update the password for a user by username, the default admin user is: admin / 22Pvsepl

## Important remarks

* This application is still based on Symfony 2. We do not plan to upgrade to Symfony 3 right away, but pull reqeusts 
are welcome!
* There are integrations with other websites and systems still present in this codebase. It can't be used as is ATM. We 
will look into making this more generic in the future, but can't guarantee any dates.

## Example apache virtualhost
```   
<VirtualHost *:80>
    DocumentRoot /web/warmhuis   
    ServerName warmhuis.test   
    SetEnv SYMFONY__ENV prod
    SetEnv SYMFONY__DEBUG 0
    
    <Directory /web/warmhuis>
        AllowOverride None
        Options -Indexes +FollowSymLinks
        Require all granted

        <IfModule mod_rewrite.c>
            RewriteEngine On

            <IfModule mod_vhost_alias.c>
                RewriteBase /
            </IfModule>

            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ app.php [QSA,L]
        </IfModule>
    </Directory>

    <Directory /usr/lib/cgi-bin>
        Require all granted
    </Directory>
    <IfModule mod_fastcgi.c>
        AddHandler php7-fcgi .php
        Action php7-fcgi /php7-fcgi
        Alias /php7-fcgi /usr/lib/cgi-bin/php7-fcgi
        FastCgiExternalServer /usr/lib/cgi-bin/php7-fcgi -socket /var/run/php/php7.0-fpm.sock -idle-timeout 3600 -pass-header Authorization
    </IfModule>
</VirtualHost>
