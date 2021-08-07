for server in 0{1..9} {10..15} 
do
	echo "#####################   Server -   s$server #################################"
	Total_mem=`ssh -i ~/.ssh/id_rsa root@s$server.theluxuryunlimited.com 'echo "scale=2; $(free -m|grep Mem|awk '\''{print $2}'\'')/1024" |bc'`
	Used_mem=`ssh -i ~/.ssh/id_rsa root@s$server.theluxuryunlimited.com 'echo "scale=2; $(free -m|grep Mem|awk '\''{print $3}'\'')/1024" |bc'`
	Used_mem_percentage=`echo "scale=2; $Used_mem/$Total_mem*100"|bc`
	echo "Total Memory = $Total_mem G"
	echo "Used Memory = $Used_mem G"
	echo "Used Memory in Percentage = $Used_mem_percentage%"
	ssh -i ~/.ssh/id_rsa root@s$server.theluxuryunlimited.com 'ps -eo pid,etime,args|grep command|grep -v grep|awk '\''{print $1 , $2 , $4}'\'''
done
