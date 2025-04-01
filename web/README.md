# MV Agusta Russia

### Standalone installation (production)

Prerequisites:
```
php: 7.3.19
mysql: 8.0.14
nginx: 1.18.0
composer: 1.8.6
yarn: 1.22.4

php.ini:
upload_max_filesize = 100M
post_max_size = 100M

nginx:
client_max_body_size 100m;
```

Setup MySQL with `--default-authentication-plugin=mysql_native_password`.

Create **/app/.env.local** file with the following values (or pass these environment variables). Do not modify **/app/.env** itself. 
```
APP_ENV=prod
DATABASE_URL=mysql://user:password@host:port/database
```
Run **composer** install:
```
composer install --prefer-dist --no-interaction --no-dev --no-cache -a
```
  
Set permissions:
```
cd app/
mkdir -p var/log
chmod -R a+rw var
setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var
```

Run **yarn** install then build assets:
```
yarn install
yarn build
```

Create the database (if not created yet):
```
cd app/
bin/console doctrine:database:create
```

Roll-up migrations:
```
bin/console doctrine:migration:migrate --no-interaction
```

Create default admin user (password can be changed later):
```
bin/console app:user:create admin@mvrussia.com admin
```

### Import data (optional)

This helper command will try to import the data from the **/assets/import** directory.

Full import will be performed only the first time the script runs.
Rerunning allowed, but in this case a limited set of data will be imported.

Execution may take a long time, be sure to set off any execution and memory limits.
```
bin/console app:content:import
```
