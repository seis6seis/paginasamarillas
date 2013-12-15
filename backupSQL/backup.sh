set FECHA=`date '+%Y_%m_%d'`
echo $FECHA
mysqldump -h localhost -u root -p hack_paginasamarillas > ./paginasamarillas_$FECHA.sql
