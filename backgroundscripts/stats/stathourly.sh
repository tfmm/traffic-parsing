#!/bin/sh

for each in $(mysql -u USERNAME --password='PASSWORD' --skip-column-names -se "select port from DATABASE.commonports;")

do 

quantity=$(mysql -u USERNAME --password='PASSWORD' -se "SELECT COUNT(*) FROM DATABASE.rawdata WHERE dst_port = $each AND time > (UNIX_TIMESTAMP() - 3600);"|tail -n1)

mysql -u USERNAME --password="PASSWORD" -e "INSERT INTO DATABASE.stats (type, amount, service) VALUES('hourly', $quantity, $each);"

done
