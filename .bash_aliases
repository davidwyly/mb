alias attach_mb='docker exec -it -e TERM=xterm-256 mb_php_1 bash -c "cd /var/www/mb && /bin/bash"'
alias run_tests='./vendor/bin/phpunit --bootstrap vendor/autoload.php tests'
