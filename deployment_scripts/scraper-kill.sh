server=$1
pid=$2
ssh -i ~/.ssh/id_rsa root@$server.theluxuryunlimited.com "kill -9 $pid"

