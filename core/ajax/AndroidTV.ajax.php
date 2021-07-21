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
  
  	if (init('action') == 'imgUpload') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.jpg', '.png','.gif'))) {
			throw new Exception('Extension du fichier non valide (autorisé .jpg .png .gif) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 5000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 5Mo)', __FILE__));
		}
        $uploaddir = __DIR__ . '/../../desktop/images/';
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir);
        }
		$filename = $_FILES['file']['name'];
		$filepath = $uploaddir . '/' . $filename;
		file_put_contents($filepath,file_get_contents($_FILES['file']['tmp_name']));
		if(!file_exists($filepath)){
			throw new \Exception(__('Impossible de sauvegarder l\'image',__FILE__));
		}
		ajax::success(array('filepath' => $filepath));
    } elseif (init('action') == 'deleteImg') {
		$file = __DIR__ . '/../../desktop/images/' . init('name');
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$filepath  = __DIR__ . '/../../desktop/images/' . init('name');
		if(!file_exists($filepath)){
			throw new Exception(__('Fichier introuvable, impossible de le supprimer', __FILE__));
		}
		unlink($filepath);
		if(file_exists($filepath)){
			throw new Exception(__('Impossible de supprimer le fichier', __FILE__));
		}
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