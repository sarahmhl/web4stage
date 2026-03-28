<VirtualHost *:80>
    ServerName web4stage.local
    Redirect permanent / https://web4stage.local/

    ErrorLog "logs/web4stage-error.log"
    CustomLog "logs/web4stage-access.log" common
</VirtualHost>

<VirtualHost *:80>
    ServerName static.web4stage.local
    Redirect permanent / https://static.web4stage.local/

    ErrorLog "logs/web4stage-static-error.log"
    CustomLog "logs/web4stage-static-access.log" common
</VirtualHost>

<VirtualHost *:443>
    ServerName web4stage.local
    DocumentRoot "C:/xampp/htdocs/projet web/public"

    SSLEngine on
    SSLCertificateFile "C:/xampp/htdocs/projet web/config/apache/certs/web4stage.local.pem"
    SSLCertificateKeyFile "C:/xampp/htdocs/projet web/config/apache/certs/web4stage.local-key.pem"

    <Directory "C:/xampp/htdocs/projet web/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>

    ErrorLog "logs/web4stage-ssl-error.log"
    CustomLog "logs/web4stage-ssl-access.log" common
</VirtualHost>

<VirtualHost *:443>
    ServerName static.web4stage.local
    DocumentRoot "C:/xampp/htdocs/projet web"

    SSLEngine on
    SSLCertificateFile "C:/xampp/htdocs/projet web/config/apache/certs/web4stage.local.pem"
    SSLCertificateKeyFile "C:/xampp/htdocs/projet web/config/apache/certs/web4stage.local-key.pem"

    <Directory "C:/xampp/htdocs/projet web/assets">
        AllowOverride None
        Require all granted
        Options FollowSymLinks
    </Directory>

    RewriteEngine On
    RewriteRule ^/assets/(.*)$ /assets/$1 [L]

    ErrorLog "logs/web4stage-static-ssl-error.log"
    CustomLog "logs/web4stage-static-ssl-access.log" common
</VirtualHost>
