#!/bin/bash
x=1
while [ $x -le 10000 ]
do
	    echo $x | md5sum
	    x=$(($x + 1))
done

echo "|||:::|||konec"