#!/bin/bash
touch /tmp/AndroidTV_dep
sudo_prefix='';
if [[ $EUID -ne 0 ]]; then
  sudo_prefix=sudo;
fi
echo 0 > /tmp/AndroidTV_dep
echo "############################################################################"
echo "# Remove in progress"
echo "############################################################################"

echo 5 > /tmp/AndroidTV_dep
$sudoPrefix adb stop-server
echo 50 > /tmp/AndroidTV_dep
$sudo_prefix apt-get -y remove android-tools-adb
echo 100 > /tmp/AndroidTV_dep
rm /tmp/AndroidTV_dep
echo "############################################################################"
echo "# Remove finished"
echo "############################################################################"
