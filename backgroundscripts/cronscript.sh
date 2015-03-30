#!/bin/bash
#set variable for script location
scriptloc=/home/arborscripts
#Check for stop file
if [ -e /home/arborscripts/stop ]; then

#Send admin an email if stopped
SUBJECT="Arbor Cron Stopped"
EMAIL="dev@null.com"
EMAILMESSAGE="/home/arborscripts/email.txt"
/bin/mail -s "$SUBJECT" "$EMAIL" < $EMAILMESSAGE

else

#pull data
curl -k https://127.0.0.1/arborws/traffic -d api_key=APIKEY --data-urlencode query@$scriptloc/pullreq.xml > $scriptloc/report.xml

mysql -u USER --password="PASSWORD" -e "LOAD XML LOCAL INFILE '$scriptloc/report.xml' INTO TABLE DATABASE.rawdata ROWS IDENTIFIED BY '<flow>';"

mysql -u USER --password="PASSWORD" -e "DELETE FROM DATABASE.rawdata WHERE time < (UNIX_TIMESTAMP() - 604800);"
fi
