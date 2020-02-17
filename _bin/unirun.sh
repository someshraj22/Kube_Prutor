#!/bin/bash
############################################################
# unirun - Run time support for "Universal Compiler"
#   See unicomp.sh
# 
# Copyright (c) 2016 Amey Karkare.
# All rights reserved.
#
# Usage of this program and the accompanying materials in any
# form without prior permission from the owner is strictly
# prohibited.
#
# Author(s):  Amey Karkare (karkare@cse.iitk.ac.in)
#################################################################
SCRIPT=`basename $0`

function help() {
 echo "Usage: "
 echo "    $SCRIPT <file-to-execute> <max_time(ms)> <max_mem(bytes)>"
 echo "                 Only a single <file-to-execute> is supported"
}

if [ $# -ne 3 ]; then
    help
    exit
fi

FILE=$1 # first argument is the executable name
FILE=`basename $FILE .out`

EXECREAL=./${FILE}.out
EXECTEMP=./${FILE}.$$.out

SAFEDIR=/var/www/data/tmp/
SANDBOX=/var/www/data/cpsandbox

mkdir -p $SAFEDIR
cp $EXECREAL $SAFEDIR/$EXECTEMP

file $EXECREAL | grep -qw Python
if [ $? -eq 0 ]; then  # Python
    SANDBOX=pypy-sandbox
    TMOUT=`expr $2 / 1000`
    $SANDBOX --timeout=$TMOUT --heapsize=$3 --tmp=$SAFEDIR -- -S /tmp/$EXECTEMP
    if [ $? -eq 0 ]; then
	echo "OK:0:0" > /dev/stderr
    else
	echo "RT:0:0" > /dev/stderr
    fi
else # C/C++/Java
    cd $SAFEDIR
    $SANDBOX ${EXECTEMP} $2 $3
fi

rm -f $EXECREAL $EXECTEMP
