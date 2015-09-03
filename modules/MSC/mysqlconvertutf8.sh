#!/bin/bash
# Use this to convert a table from latin1 encoding to utf8 enconding
 
DBUSER='root'
 
TEMPFILE='tempfile.txt'
 
#Verify the Arguments
if [ $# -ne 2 ];
then
	echo "USAGE: mysqlconvertutf8.sh Database Table"
	exit 1
fi

# Args
# MySQL Database
DB=$1
# MySQL Database Table
DBTABLE=$2
 
#Alter Command
ALTERCMD="ALTER TABLE $DBTABLE CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
 
#Grab data force UTF8
mysqldump -u${DBUSER} -a -c -e --add-drop-table --default-character-set=utf8 ${DB} ${DBTABLE} > $TEMPFILE

#change it to think its latin1
sed -i 's/latin1/utf8/g' $TEMPFILE

#import new data
mysql -u${DBUSER} ${DB} < $TEMPFILE #Export, without forcing char set mysqldump -u${DBUSER} -p${DBPASSWD} -h${DBHOST} -a -c -e --add-drop-table ${DB} ${DBTABLE} > $TEMPFILE

#import final data
mysql -u${DBUSER} ${DB} < $TEMPFILE #Data is converted now need to change table definitions. echo $ALTERCMD > $TEMPFILE
mysql -u${DBUSER} ${DB} < $TEMPFILE
 
#Cleanup after yourself, delete the tempfile
###############[ -e $TEMPFILE ]; rm -f $TEMPFILE
 
exit 0

