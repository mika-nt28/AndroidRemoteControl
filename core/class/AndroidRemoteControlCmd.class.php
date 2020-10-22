<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class AndroidRemoteControlCmd extends cmd
{

public function execute($_options = null)
{
$ARC = $this->getEqLogic();
$ARC->checkAndroidRemoteControlStatus();

$sudo = exec("\$EUID");
if ($sudo != "0") {
$sudo_prefix = "sudo ";
}
$ip_address = $ARC->getConfiguration('ip_address');

log::add('AndroidRemoteControl', 'info', 'Command ' . $this->getConfiguration('commande') . ' sent to android device at ip address : ' . $ip_address);
shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 " . $this->getConfiguration('commande'));

if (stristr($this->getLogicalId(), 'setVolume')){
shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell media volume --stream 3  --set " . $_options['slider']);
}

$ARC->updateInfo();
}

}
