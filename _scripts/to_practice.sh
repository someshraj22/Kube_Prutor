#!/bin/bash
dd=`dirname $0`
stty -echo
php ${dd}/to_practice.php
stty echo
