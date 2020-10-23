<?php

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new \Exception(__('401 - Accès non autorisé', __FILE__));
    }
        $ipaddress = init('params');

	if (init('action') == 'connect') {
            	log::add('AndroidTV', 'debug', 'connection encours a ' . $ipaddress);

      	AndroidTV::connectADB($ipaddress);
      			ajax::success();
    }

    if (init('action') == 'resetADB') {
            	log::add('AndroidTV', 'debug', '==== reset encours ====');
      	AndroidTV::resetADB();
      			ajax::success();
    }

   throw new \Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
} catch (\Exception $e) {
    if (function_exists('displayException')) {
        ajax::error(displayException($e), $e->getCode());
    }
    else {
        ajax::error(displayExeption($e), $e->getCode());
    }
}
