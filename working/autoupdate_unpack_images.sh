dir=$(dirname $1)
file=$(basename $1)
unpackdir=$2

cd $dir
unzip -LL -uo $file -d "$unpackdir"
