date=`date +%d-%m-%y`
bkproot=/mnt/volume_blr1_02/erp_backup
bkpdir=/mnt/volume_blr1_02/erp_backup/$date

mkdir -p $bkpdir
mysqldump erp_live |gzip -v > $bkpdir/erp_live.sql.gz

find $bkproot -mtime +8 -exec rm -rf {} \; 


############### Magento Servers Database Backup #############
envfile='/mnt/volume_blr1_03/websites/erp.amourint.com/httpdocs/erp/.env'
magento_servers='BRANDS AVOIRCHIC OLABELS SOLOLUXURY SUVANDNAT THEFITEDIT THESHADESSHOP UPEAU VERALUSSO'
mageroot=/mnt/volume_blr1_02/magento_backup
magentobkp=/opt/$date

user=`grep MAGENTO_DB_USER $envfile|cut -d'=' -f2`
pass=`grep MAGENTO_DB_PASSWORD $envfile|cut -d"'" -f2`

for server in $magento_servers
do
	mkdir -p $magentobkp/$server
	db=`grep "$server"_DB $envfile|cut -d'=' -f2`
	host=`grep "$server"_HOST $envfile|cut -d'=' -f2`
	mysqldump -h $host -u $user -p"$pass" $db |gzip -v > $magentobkp/$server/$db.sql.gz
done
mv $magentobkp $mageroot/

find $magentoroot -mtime +8 -exec rm -rf {} \; 
