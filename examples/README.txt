Start docker by runnning command
sudo ./docker/docker-start.sh

Web server container is up and running on port 8082 at localhost.
There is also phpMyAdmin at: http://localhost:8082/phpmyadmin
Root password is "root"
Import the nestpay_payment table from script: ../resources/nestpay_payment.sql
Edit config file "./example/config.php"
Go to http://localhost:8082/examples/ and test your account.
Cheers!
