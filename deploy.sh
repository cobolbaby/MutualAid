#!/bin/bash
read -p "please input git branch name:" branch
read -p "please input runtime path (default path is /opt/mayihelp) :" runtime_path
if [ "$runtime_path" == "" ]; then
runtime_path=/opt/mayihelp
fi

git checkout master

if [ $? -ne 0 ]; then
exit 0
fi

git pull
git checkout $branch
git pull
rsync -azv --exclude "deploy.sh" --exclude ".git" --exclude ".gitignore" . $runtime_path