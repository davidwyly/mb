##########
# DOCKER #
##########

alias docker_rebuild='docker-compose down; docker system prune --all --force; docker-compose up --build'
alias attach_php='docker exec -it -e TERM=xterm-256 mb_php_1 bash -c "cd /var/www/mb && /bin/bash"'
alias attach_nginx='docker exec -it -e TERM=xterm-256 mb_nginx_1 bash -c "cd /var/www/mb && /bin/bash"'
alias attach_mysql='docker exec -it -e TERM=xterm-256 mb_mysql_1 bash -c "cd / && /bin/bash"'

#########
# TESTS #
#########

alias run_tests='./vendor/bin/phpunit --bootstrap vendor/autoload.php tests'
