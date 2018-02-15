#!/bin/bash

if [ ! -f ./.env.bash ]; then
    echo ".env.bash not found. Exit"
    exit 4
else 
    source ./.env.bash
fi 

if [[ ! $(git status --porcelain) ]]; then
    echo "Git status indicates that their is some modifications on this repository."
    echo "Please commit or stash them before using this script"
    exit 1
fi

# 0 Backup current branch
current_branch=$(git symbolic-ref --short HEAD)

# First step is to check that test on master branch are ok
git checkout master

composer install
if [ "$?" -ne "0" ]; then
    echo "Composer install failed !"
    git checkout "$current_branch"
    exit 3
fi

composer test
if [ "$?" -ne "0" ]; then
    echo "Tests failed !"
    git checkout "$current_branch"
    exit 2
fi

folder="$FOLDER"
ssh_details="$SSH_DETAILS"

echo "Going to deploy to ${ssh_details}:${folder}"

git checkout "$current_branch"

ssh $ssh_details "cd $folder; git pull; composer install"

if [ "$?" -ne 0 ]; then 
    echo "Deploy failed ! ssh command failed."
    exit 5
else
    echo "Deploy successful !"
fi
