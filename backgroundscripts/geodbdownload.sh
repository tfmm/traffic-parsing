#!/bin/bash
if [ `date +%d` -gt 7 ] ; then
   exit
else
wget -P /home/srv/html/netdata/mapping/ http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz

gunzip -c /home/srv/html/netdata/mapping/GeoLiteCity.dat.gz > /home/srv/html/netdata/mapping/GeoLiteCity.dat

rm -f /home/srv/html/netdata/mapping/GeoLiteCity.dat.gz

fi
