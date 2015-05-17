dir=$(dirname $1)
file=$(basename $1)
unpackdir=$2

cd $dir
mkdir -p $unpackdir/$dir
unzip -uo $file -d "$unpackdir/$dir"
