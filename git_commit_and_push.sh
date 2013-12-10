#!/bin/sh
git add -u
git commit -m "snapshot `date`"
git push origin master
