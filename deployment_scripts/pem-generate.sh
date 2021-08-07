#!/bin/bash

function HELP {
	echo "-f|--function: add/delete"
	echo "-s|--server: Server Name"
	echo "-u|--user: Username"
}

args=("$@")
idx=0
while [[ $idx -lt $# ]]
do
        case ${args[$idx]} in
	        -f|--function)
	        function="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -s|--server)
	        server="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -u|--user)
	        user="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
                -h|--help)
	        HELP
	        exit 1
	        ;;
	        *)
	        idx=$((idx+1))
	        ;;
	esac
done

if [ -z $server ] || [ -z $user ] || [ -z $function ]
then
	HELP
	exit
fi

if [ "$function" = "add" ]
then
	########### Command to Create user and Generate Ssh key file for input server name and user ############
	command="id -u $user &>/dev/null || useradd -m -G sudo -s /bin/bash $user -p $(openssl passwd -crypt $user) ; su - $user -c \"echo 'y'|ssh-keygen -f ~/.ssh/id_rsa -N '' ; cat ~/.ssh/id_rsa.pub > ~/.ssh/authorized_keys ; chmod 600 /home/$user/.ssh/authorized_keys ; cat /home/$user/.ssh/id_rsa\""
elif [ "$function" = "delete" ]
then
	####### Command to delete user access to specific server #####
	command="userdel -r $user -f"
fi

#################################################################################################################################################
#################################################################################################################################################
if [ "$server" == "Erp-Server" ]		### Check for Erp Server
then
	ssh -i ~/.ssh/id_rsa root@erp.theluxuryunlimited.com -p2112 "$command"

elif [ "$server" == "s01" ] || [ "$server" == "s02" ] || [ "$server" == "s03" ] || [ "$server" == "s04" ] || [ "$server" == "s05" ] || [ "$server" == "s06" ] || [ "$server" == "s07" ] || [ "$server" == "s08" ] || [ "$server" == "s09" ] || [ "$server" == "s10" ] || [ "$server" == "s11" ] || [ "$server" == "s12" ] || [ "$server" == "s13" ] || [ "$server" == "s14" ] || [ "$server" == "s15" ]
then
	ssh -i ~/.ssh/id_rsa root@$server.theluxuryunlimited.com "$command"

elif [ "$server" == "Cropper-Server" ]		### Check for Cropper Server
then
	ssh -i ~/.ssh/id_rsa root@178.62.200.246 "$command"

elif [ "$server" == "BRANDS" ]
then
	ssh -i ~/.ssh/id_rsa root@138.68.161.55 "$command"

elif [ "$server" == "AVOIRCHIC" ]
then
	ssh -i ~/.ssh/id_rsa root@138.68.141.190 "$command"

elif [ "$server" == "OLABELS" ]
then
	ssh -i ~/.ssh/id_rsa root@138.68.185.192 "$command"

elif [ "$server" == "SOLOLUXURY" ]
then
	ssh -i ~/.ssh/id_rsa root@46.101.78.91 "$command"

elif [ "$server" == "SUVANDNAT" ]
then
	ssh -i ~/.ssh/id_rsa root@188.166.168.141 "$command"

elif [ "$server" == "THEFITEDIT" ]
then
	ssh -i ~/.ssh/id_rsa root@139.59.182.8 "$command"

elif [ "$server" == "THESHADESSHOP" ]
then
	ssh -i ~/.ssh/id_rsa root@138.68.165.128 "$command"

elif [ "$server" == "UPEAU" ]
then
	ssh -i ~/.ssh/id_rsa root@138.68.181.9 "$command"

elif [ "$server" == "VERALUSSO" ]
then
	ssh -i ~/.ssh/id_rsa root@139.59.175.99 "$command"

fi
