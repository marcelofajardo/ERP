########## Script will take 2 Command line argument first as Server id , 2nd as scrapper command
server=$1
command=$2

ssh root@$server.theluxuryunlimited.com "nohup node /root/scraper_nodejs/commands/completeScraps/$command &> /root/logs/manual/$command.out &
"
