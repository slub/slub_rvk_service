# SLUB - RVK Service
This scripts provides a JSON service for RVK-Notations

## Installation and use with Apache server
1. pull git with "git clone https://git.slub-dresden.de/slub-webseite/slub-rvk-service.git /var/www/api"
2. change directory to "/var/www/api"
3. check for write access e.g. join group "www-data", userown for "www-data:www-data" and usermod g+w
4. start update script (first install included) with "php update.php"
5. finally test the service with your browser as described below

## XML-Update
Run update.php manually or use crontab like:
    0 1 20 1-12/3 * www-data php /var/www/api/update.php

###For local testing and editing
1. git clone https://git.slub-dresden.de/slub-webseite/slub-rvk-service.git
2. git branch <e.g. update>
3. git checkout <e.g. update>
4. git add .
5. git commit
6. git push

## How to use the Service
Run the index.php file with parameter rvk eg:server/api/index.php?rvk=AA%2010000.
