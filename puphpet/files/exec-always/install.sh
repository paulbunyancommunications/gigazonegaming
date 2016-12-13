#!/usr/bin/env bash
sudo npm update -g
sudo npm upgrade -g
sudo npm update
sudo gem update --system -g
sudo gem update -g
# install YARN
# https://yarnpkg.com/en/docs/install#linux-tab
sudo wget https://dl.yarnpkg.com/rpm/yarn.repo -O /etc/yum.repos.d/yarn.repo
sudo yum -y install yarn
sudo npm install -g bower