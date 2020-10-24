#!/bin/bash
touch /tmp/AndroidTV_dep
if [[ $EUID -ne 0 ]]; then
  sudo_prefix=sudo;
fi
echo 0 > /tmp/AndroidTV_dep
echo "############################################################################"
echo "# Installation in progress"
echo "############################################################################"
echo "############################################################################"
echo "# Update repository packages and install dependencies"
echo "############################################################################"

echo 5 > /tmp/AndroidTV_dep
$sudo_prefix apt-get -y update
echo 50 > /tmp/AndroidTV_dep

$sudo_prefix apt-get -y install android-tools-adb

echo 75 > /tmp/AndroidTV_dep
$sudoPrefix adb start-server

echo 90 > /tmp/AndroidTV_dep

echo "############################################################################"
echo "# Installation Information"
echo "############################################################################"
$sudo_prefix cat /etc/os-release

echo 100 > /tmp/AndroidTV_dep
rm /tmp/AndroidTV_dep
echo "############################################################################"
echo "# Installation finished"
echo "############################################################################"
