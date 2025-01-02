#!/bin/bash

cd $(dirname $0)/..

php artisan lang:update
perl -i -pe "BEGIN{undef $/;} s@('email'\s+=>\s+)'メール'@\1'メールアドレス'@smg" lang/ja/validation.php
