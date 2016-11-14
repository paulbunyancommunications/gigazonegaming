#!/usr/bin/env bash
# @todo make sure script does not return error when installing, right now when running with vagrant up the coding will be "red" which may or may not be classified as an error in Jenkins
# ===================================================
# Start Setup
# ===================================================
cd ~/
# Update yum
sudo yum -y update
sudo npm update -g
sudo npm install selenium-standalone@latest -g
sudo selenium-standalone install
sudo npm install phantomjs -g
sudo npm install phantomjs2 -g

# ===================================================
# Start install of the Selenium stack
# * Selenium stand alone server
# * Firefox
# * Xvfb
# ===================================================

# borrowed from https://github.com/seanbuscay/vagrant-phpunit-selenium/blob/master/setup.sh
set -e
echo '-------------------------'
echo 'INSTALLING SELENIUM STACK'
echo '-------------------------'

#!/bin/bash

# specify stable versions to grab
FIREFOX_VERSION="47.0.1"

#=========================================================
echo "Updating packages ..."
#=========================================================
sudo yum update && yum upgrade

#=========================================================
echo "Installing dependencies ..."
#=========================================================
sudo mkdir /var/selenium && cd /var/selenium
sudo wget http://selenium-release.storage.googleapis.com/2.40/selenium-server-standalone-2.40.0.jar
sudo wget update

#=========================================================
echo "Installing java ..."
#=========================================================
cd /opt/
sudo wget --no-cookies --no-check-certificate --header "Cookie: gpw_e24=http%3A%2F%2Fwww.oracle.com%2F; oraclelicense=accept-securebackup-cookie" "http://download.oracle.com/otn-pub/java/jdk/8u111-b14/jdk-8u111-linux-x64.tar.gz"
sudo tar xzf jdk-8u111-linux-x64.tar.gz

export JAVA_HOME=/opt/jdk1.8.0_111

cd /var/www
export JRE_HOME=/opt/jdk1.8.0_111/jre
export PATH=$PATH:/opt/jdk1.8.0_111/bin:/opt/jdk1.8.0_111/jre/bin

#=========================================================
echo "Installing dependencies again..."
#=========================================================
sudo wget install firefox -y
sudo wget install xvfb -y
sudo yum install -y links
sudo yum install -y openssh openssh-server openssh-clients
sudo yum install -y httpd
sudo yum install -y curl
sudo yum install -y unzip
#=========================================================
echo "Installing LAMP stack ... "
#=========================================================
sudo yum -y install apache2

# set up root password for MySQL
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password password'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password password'

# depedencies
sudo yum -y install mysql-server libapache2-mod-auth-mysql php56-mysql

# ensure we get PHP 5.4.x, required for composer
sudo yum install -y software-properties-common
sudo yum install -y python-software-properties
sudo add-apt-repository ppa:ondrej/php56-oldstable
sudo yum update

# installing PHP and it's dependencies
sudo yum -y install php56 libapache2-mod-php56 php56-mcrypt
sudo yum -y install php56-curl

# =========================================================
# echo "Installing GUI ... "
# =========================================================
 sudo yum install -y ubuntu-desktop gnome

# # install GUI
 sudo yum install -y xfce4 virtualbox-guest-dkms virtualbox-guest-utils virtualbox-guest-x11
# # Permit anyone to start the GUI
 sudo sed -i 's/allowed_users=.*$/allowed_users=anybody/' /etc/X11/Xwrapper.config

#=========================================================
echo "Installing xvfb for headless testing"
#=========================================================
sudo yum -y install xvfb

#=========================================================
echo "Installing firefox ${FIREFOX_VERSION} ... "
#=========================================================
sudo wget https://ftp.mozilla.org/pub/firefox/releases/${FIREFOX_VERSION}/firefox-${FIREFOX_VERSION}.linux-x86_64.sdk.tar.bz2 && tar xjf firefox-${FIREFOX_VERSION}.linux-x86_64.sdk.tar.bz2
sudo mv firefox-sdk /opt/firefox
sudo rm -rf /usr/bin/firefox
sudo ln -s /opt/firefox/bin/firefox /usr/bin/firefox
sudo rm -rf firefox-${FIREFOX_VERSION}.linux-x86_64.sdk.tar.bz2


#=========================================================
echo "Downloading selenium server 3.0.1 ..."
#=========================================================
wget "https://goo.gl/Lyo36k" -O selenium-server-standalone-3.0.1.jar
chown vagrant:vagrant selenium-server-standalone.jar
chmod +x selenium-server-standalone.jar

#=========================================================
echo "Installing phantomjs ... "
#=========================================================
sudo curl -O https://phantomjs.googlecode.com/files/phantomjs-1.9.2-linux-x86_64.tar.bz2
sudo tar xvf phantomjs-1.9.2-linux-x86_64.tar.bz
sudo cp phantomjs-1.9.2-linux-x86_64/bin/phantomjs /usr/local/bin
sudo yum install freetype
sudo yum install fontconfig

#=========================================================
echo "Installing composer for PHP tests"
#=========================================================
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

sudo systemctl stop firewalld
sudo systemctl mask firewalld
sudo yum install iptables-services
sudo systemctl enable iptables
sudo systemctl [stop|start|restart] iptables
sudo service iptables save
# ===================================================
# Start Xvfb, firefox, and Selenium in the background
# ===================================================



