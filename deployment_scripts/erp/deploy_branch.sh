BRANCH_NAME=$1
COMPOSER_UPDATE=$2
scriptPath="$(cd "$(dirname "$0")"; pwd)"
cd $scriptPath;
cd ../..
git checkout $BRANCH_NAME;
git pull;
./artisan migrate
echo $BRANCH_NAME;
if $COMPOSER_UPDATE  == "true"
then
   composer update
else 
    echo "Finished" 
fi
