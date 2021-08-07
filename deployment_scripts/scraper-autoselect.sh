#!/bin/bash
###  This script is used to autoselect scraper server which has maximum memory available and start scraper there

ScriptDIR=`dirname "$0"`
day=`date +%d`

rm /tmp/scrap_* /opt/scrap_status

####################    Get all running Scraper details in all servers #######
function scraper_status
{
	for server in 0{1..6}
	do
	        ssh -i ~/.ssh/id_rsa -o ConnectTimeout=5 root@s$server.theluxuryunlimited.com "ps -eo pid,etimes,args|grep command|grep -v externalScraper|grep -v grep|awk -v var=$server '{print var, \$1 , \$2/3600 , \$4}'" >> /opt/scrap_status 2>/dev/null
	done
}

####################    Get all Servers Used Memory in % #########
function scraper_memory
{
	rm /tmp/scrap_memory  > /dev/null
	for server in 0{1..6}
	do
		Used_mem=`ssh -i ~/.ssh/id_rsa -o ConnectTimeout=5 root@s$server.theluxuryunlimited.com 'free | grep Mem | awk '\''{print $3/$2 * 100.0}'\''' 2>/dev/null`
		if [ -z $Used_mem ]
		then
			Used_mem="100.00"
		fi
		echo $server $Used_mem >> /tmp/scrap_memory
	done
}

#################  Get list of Scrapers which need to start/restart ##########
function scraper_restart_list
{
	while read scrap
	do
		status=`grep -w $scrap /opt/scrap_status`
		if [ ! -z "$status" ]						### If Scraper is already running
		then
			processing_time=`echo $status|cut -d' ' -f3|cut -d'.' -f1`
			if [ $processing_time -gt 48 ]				### if Scraper processing from more than 2 days then kill and restart
			then
				server=`echo $status|cut -d' ' -f1`
				pid=`echo $status|cut -d' ' -f2`
				ssh -i ~/.ssh/id_rsa root@s$server.theluxuryunlimited.com "kill -9 $pid" < /dev/null
				sed -i "/$scrap/d" /opt/scrap_status
				echo "$scrap restart" >> /tmp/scrap_restart
			fi
		else
			scrapfile=`echo $scrap|cut -d'.' -f1`
			logfile=`find /mnt/volume_blr1_03/websites/logs/ -mmin -720 -iname "$scrapfile-*.log"|wc -l`
			if [ $logfile -eq 0 ]
			then
				echo "$scrap" >> /tmp/scrap_restart
			fi
		fi
	done < $ScriptDIR/scraper-list.txt
}
	
########### Restart Scraper #####
function scraper_restart
{
	echo "############ Below Scrappers will be Restarted ##############################"
	cat /tmp/scrap_restart
	while read scraperjs
	do
		scraper_memory < /dev/null
		scraper=`echo $scraperjs|cut -d'.' -f1`
		server=`cat /tmp/scrap_memory|sort -n -k2|head -n1|cut -d' ' -f1`
		minmemory=`cat /tmp/scrap_memory|sort -n -k2|head -n1|cut -d' ' -f2|cut -d'.' -f1`
		if [ $minmemory -gt 95 ]
		then
			echo "No server has free memory more than 5%"
			exit
		fi
		echo $server $scraper
		ssh -o ConnectTimeout=5 root@s$server.theluxuryunlimited.com "nohup node /root/scraper_nodejs/commands/completeScraps/$scraper.js &> /root/logs/$scraper-$day.log &" < /dev/null
		if [ $? -eq 0 ]
		then
	                ssh -o ConnectTimeout=5 root@s$server.theluxuryunlimited.com "ps -eo pid,etimes,args|grep $scraperjs|grep -v grep|awk -v var=$server '{print var, \$1 , \$2/3600 , \$4}'" >> /opt/scrap_status 2>/dev/null < /dev/null 
			date=`date +'%F-%T'`
			day=`date +'%d'`
			echo "$scraper s$server $date Processing-$scraper-$day-s$server" >> /opt/scrap_history
		fi
		echo "Wait for 30 Seconds before starting another scrapper"
		sleep 30
	done < /tmp/scrap_restart
}

scraper_status
scraper_restart_list
scraper_restart < /dev/null
