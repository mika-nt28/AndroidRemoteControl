<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function AndroidTV_install() {
      exec('../3rdparty/install.sh');
}

function AndroidTV_update() {
}
function AndroidTV_remove() {
      foreach(eqLogic::byType('AndroidTV') as $AndroidTV){
			$cron = cron::byClassAndFunction('AndroidTV', 'CheckAndroidTV', array('id' => $AndroidTV->getId()));
			if(is_object($cron))	
				$cron->remove();
		}
    exec('../3rdparty/remove.sh');
}

?>
