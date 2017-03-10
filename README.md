<h2><b>Social Analytics Database (SADB)</b></h2>
This is a Zend Framework 2 project, running on PHP 5.3.  It uses Composer for dependency management.

<strong><b>Installation:</b></strong>
<strong>Requirements:</strong> Centos 7, PHP 5.3, Composer, HTTPD (Apache Web Server)
This project is being developed under <b>/var/www/sadb</b>, but the location shouldn't matter as long as Apache is configured to host the location.  Clone the project directly into the configured FS location for hosting.

<b>cd</b> into the project root, and run
<b>composer install</b> - depending on the unstallation and configuration, an alternative invocation such as <b>php composer.phar install</b> may be required.  This reads the "composer.json" file in the project root and installs all the dependancies.  The libraries are written to <b>vendor/</b>.
Next, the DB connections must be set up.  The files where this happens are;
<strong>config/autoload/global.php</strong>
<strong>config/doctrine-config.php</strong>
The file <strong>config/autoload/doctrine.local.php</strong> must be created; it requires the following contents, adjusted appropriately for the datasource:
```php
<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'Doctrine\ORM\Mapping\UnderscoreNamingStrategy' => 'Doctrine\ORM\Mapping\UnderscoreNamingStrategy',
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '5432',
                    'user'     => 'postgres',
                    'password' => 'password',
                    'dbname'   => 'sadb',
                    'encoding' => 'utf8',
                ),
            ),
        ),
        'configuration' => array(            
            'orm_default' => array(
                'naming_strategy' => 'Doctrine\ORM\Mapping\UnderscoreNamingStrategy',
            ),
        ),
        'migrations_configuration' => array(
            'orm_default' => array(
                'directory' => 'data/DoctrineORMModule/Migrations',
                'namespace' => 'DoctrineMigrations',
                'table' => 'migrations',
            ),
        ),
    ),
);
```

Next, in the datasource, a DB must be created and named accordingly (in the code above, I had connected to "sadb").  Within that DB, a blank "public" schema is required.

<b>php public/index.php migrations:migrate</b> will invoke Doctrine's schema generation, and populate the database with the appropriate tables.  <b>At this time, no fixtures are provided.</b>

Next, browse to http://project_location/user, and click "register" to create a user.

The rest of this is developmental nothing.  The index controller currently contains the logic to create a "User" record, but it isn't a valid one (IE capable of using the system), and the code must be modified with a new user name and email address each time it is run.  Be warned.
