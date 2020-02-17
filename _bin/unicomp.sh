#!/bin/bash
############################################################
# unicomp - a "Universal Compiler"
#   An attempt to provide a wrapper compiler (along with the 
#   run-time support) for popular languages. 
#
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
# Currently supported language with base compiler
# (add new languages at the end) :
#    1. C       (clang)
#    2. C++     (clang++)
#    3. Java    (gcj)
#    4. Python  (pypy - for its sandboxing)
#    5.
#    6.
#
#  
SCRIPT=`basename $0`
LANGID=prutor-lang

function help() {
 echo "Usage: "
 echo "$SCRIPT <input-file> [options]"
 echo "    [options] are the options to be passed to the native compiler"
 echo "    Only a single <input-file> is supported"
}

function compileC() {
    LFILE=${FILE}.c
    sed 's/^.*'$LANGID'.*$//' $FILEXT > $LFILE
    clang $LFILE ${ARGS} 
}

function compileCPP() {
    LFILE=${FILE}.cpp
    sed 's/^.*'$LANGID'.*$//' $FILEXT > $LFILE
    clang++ $LFILE ${ARGS} 
}

function compileJava() {
    # For java, we need the top level class name to be fixed
    # this is required for prutor to run properly
    JAVAMAIN=Main
    LFILE=${FILE}.java
    sed 's/^.*'$LANGID'.*$//' $FILEXT > $LFILE
    gcj $LFILE --main=$JAVAMAIN ${ARGS} 
}

function compilePython() {
    # For python, we just need to set up the files
    # to be interpreted 
    LFILE=${FILE}.out
    PYTHON=`which python`
    echo "#!${PYTHON}" > $LFILE
    grep -v $LANGID $FILEXT >> $LFILE
    chmod +x $LFILE
}

FILEXT=$1          # first argument is the file name
EXT=".uc"
FILE=`basename ${FILEXT} ${EXT}` # ignore the extension

shift   # $@ is the [options] part now!
ARGS="$@"

# the language
ULANG=`grep $LANGID $FILEXT | head -1 | cut -d: -f2 | awk '{print $1}'`
LANG=`echo $ULANG | tr [:lower:] [:upper:]`

case "$LANG" in 
    "C" ) compileC  ;; 
    "C++" | "CPP" | "CXX" | "CC" ) compileCPP ;;
    "JAVA" ) compileJava  ;;
    "PYTHON" ) compilePython ;;
    *)  printf "${FILE}:1: error: Unsupported lang $ULANG" > /dev/stderr
	exit -1 ;;
esac

