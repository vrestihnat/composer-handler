#!/bin/bash
export HOME=/tmp && export COMPOSER_HOME=/tmp
#sh /var/www/composer-handler/testloop.sh  > $2
eval $1 2>$2
#echo $1
#echo $(pwd -P)
echo "|||:::|||konec" >> $2
