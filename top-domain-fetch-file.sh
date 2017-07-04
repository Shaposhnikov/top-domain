#!/bin/bash

cd /srv/cache

wget http://s3.amazonaws.com/alexa-static/top-1m.csv.zip

unzip -o top-1m.csv.zip

rm top-1m.csv.zip

exit 0

