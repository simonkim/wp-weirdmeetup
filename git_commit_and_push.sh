#!/bin/sh
echo "# Backing up database under conf/ directory..."
cd conf/
./dbdump.sh
cd ..
echo "# git: adding changed files..."
git add -u
echo "# git: committing...""
git commit -m \"snapshot `date`\"
echo "# git: pushing""
git push origin master
echo "# done, unless there was an error!"
