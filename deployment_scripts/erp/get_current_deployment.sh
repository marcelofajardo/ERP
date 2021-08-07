scriptPath="$(cd "$(dirname "$0")"; pwd)"
cd $scriptPath;
cd ..
git rev-parse --abbrev-ref HEAD