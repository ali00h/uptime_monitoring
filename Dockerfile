FROM ali00h/cronjob_without_volume:latest

ENV CRON_LIST="* * * * * wget http://localhost/uptimecheck.php?p=1"

COPY index.php /var/www/html/webui/public/uptimecheck.php
