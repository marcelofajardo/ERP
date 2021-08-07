#!/bin/bash

function Create {
	check_user=`mysql -h $host -u $user -p"$password" -e "select user from mysql.user where user='$mysql_user'"`
	if [ -z "$check_user" ]
	then
		mysql -h $host -u $user -p"$password" <<QUERY
		CREATE USER '$mysql_user'@'localhost' IDENTIFIED BY '$mysql_pass';
		FLUSH PRIVILEGES;
QUERY
	else
		echo "User already created"
	fi
}

function Delete {
	check_user=`mysql -h $host -u $user -p"$password" -e "select user from mysql.user where user='$mysql_user'"`
	if [ -z "$check_user" ]
	then
		echo " User Does not exist"
	else
		mysql -h $host -u $user -p"$password" <<QUERY
		drop user '$mysql_user'@localhost;
		FLUSH PRIVILEGES;
QUERY
	fi
}

function Update {
	check_user=`mysql -h $host -u $user -p"$password" -e "select user from mysql.user where user='$mysql_user'"`
	if [ -z "$check_user" ]
	then
		echo " User Does not exist"
	else
		if [ -z "$mysql_table" ]
		then
			echo "Please enter table name to give permission"
		else
			if [ "$permission_type" = "read" ]
			then
				type="select"
			else
				type="select,insert,update"
			fi

			for table_name in $(echo $mysql_table | sed "s/,/ /g")
			do
				mysql -h $host -u $user -p"$password" <<QUERY
				GRANT $type ON $database.$table_name TO '$mysql_user'@'localhost';
				FLUSH PRIVILEGES;
QUERY
			done
		fi
	fi
}

function Revoke {
	check_user=`mysql -h $host -u $user -p"$password" -e "select user from mysql.user where user='$mysql_user'"`
	if [ -z "$check_user" ]
	then
		echo " User not exist"
	else
		if [ -z "$mysql_table" ]
		then
			echo "Please enter table name to revoke permission"
		else
			for table_name in $(echo $mysql_table | sed "s/,/ /g")
			do
				mysql -h $host -u $user -p"$password" <<QUERY
				REVOKE select,insert,update ON $database.$table_name from '$mysql_user'@'localhost';
				FLUSH PRIVILEGES;
QUERY
			done
		fi
	fi
}

function HELP {
	echo " -u|--user: Mysql User to connect"
	echo " -p|--password: Mysql user Password"
	echo " -h|--host: Mysql server host to connect"
	echo " -d|--db: Mysql database to connect"
	echo " -f: Function (create - create new mysql user with given password)
		(delete - Delete mysql user)
		(update - Assign insert & update permission on specific table)
		(revoke - Revoke insert & update permission from all tables)"
	echo "-n|--new-user: New Mysql user to create"
	echo "-s|--new-pass: New Mysql user password"
	echo " -m: Permission type read/write to specific table"
	echo " -t: Mysql Database Table"
}

args=("$@")
idx=0
while [[ $idx -lt $# ]]
do
        case ${args[$idx]} in
	        -u|--user)
	        user="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -p|--password)
	        password="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -h|--host)
	        host="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -d|--db)
	        database="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -f|--function)
	        function="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -n|--new-user)
	        mysql_user="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -s|--new-pass)
	        mysql_pass="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -t|--table)
	        mysql_table="${args[$((idx+1))]}"
	        idx=$((idx+2))
	        ;;
	        -m|--permission)
	        permission_type="${args[$((idx+1))]}"
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

if [ "$function" = "create" ]
then
	Create
elif [ "$function" = "delete" ]
then
	Delete
elif [ "$function" = "update" ]
then
	Update
elif [ "$function" = "revoke" ]
then
	Revoke
fi
