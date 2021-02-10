<?php
require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once "AndroidTVCmd.class.php";

class AndroidTV extends eqLogic{
	public static $_widgetPossibility = array(
		'custom' => true,
		'custom::layout' => false,
		'parameters' => array(
			'sub-background-color' => array(
				'name' => 'Couleur de la barre de contrôle',
				'type' => 'color',
				'default' => 'rgba(0,0,0,0.5)',
				'allow_transparent' => true,
				'allow_displayType' => true,
			),
		),
	);
	public static function cron()    {
		foreach (eqLogic::byType('AndroidTV', true) as $eqLogic) {
			$eqLogic->updateInfo();
			#$eqLogic->refreshWidget();
		}
	}
	public static function dependancy_info()    {
		$return                  = array();
		$return['log']           = 'AndroidTV_dep';
		$return['progress_file'] = '/tmp/AndroidTV_dep';
		$adb                     = '/usr/bin/adb';
		if (is_file($adb)) {
			$return['state'] = 'ok';
		} else {
			exec('echo AndroidTV dependency not found : ' . $adb . ' > ' . log::getPathToLog('v_log') . ' 2>&1 &');
			$return['state'] = 'nok';
		}
		return $return;
	}
	public static function dependancy_install(){
		log::add('AndroidTV', 'info', 'Installation des dépéndances android-tools-adb');
		$resource_path = realpath(__DIR__ . '/../../3rdparty');
		passthru('/bin/bash ' . $resource_path . '/install.sh ' . $resource_path . ' > ' . log::getPathToLog('AndroidTV_dep') . ' 2>&1 &');
	}
	public function runcmd($_cmd) {
		$type_connection = $this->getConfiguration('type_connection');
		$ip_address = $this->getConfiguration('ip_address');
		$sudo = exec("\$EUID");
		if ($sudo != "0") {
			$sudo_prefix = "sudo ";
		}
		if ($type_connection == "TCPIP") {
			$data = shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 " . $_cmd);
			return $data;
		}elseif ($type_connection == "TCPIP") {
			$data = shell_exec($sudo_prefix . "adb " . $_cmd);
			return $data;
		}
	}
	public static function resetADB(){
		$sudo = exec("\$EUID");
		if ($sudo != "0")
			$sudo_prefix = "sudo ";
		log::add('AndroidTV', 'debug', 'Arret du service ADB');
		shell_exec($sudo_prefix . "adb kill-server");
		sleep(3);
		log::add('AndroidTV', 'debug', 'Lancement du service ADB');
		shell_exec($sudo_prefix . "adb start-server");
	}
	public function connectADB($_ip_address = null) {
		$sudo = exec("\$EUID");
		if ($sudo != "0") 
			$sudo_prefix = "sudo ";
		if (isset($_ip_address)) 
			$ip_address = $_ip_address;
		else
			$ip_address = $this->getConfiguration('ip_address');
		log::add('AndroidTV', 'debug', $this->getHumanName(). ' Déconnection préventive du périphérique '.$ip_address.' encours');
		shell_exec($sudo_prefix . "adb connect ".$ip_address);
		log::add('AndroidTV', 'debug', $this->getHumanName(). ' Connection au périphérique '.$ip_address.' encours');
		shell_exec($sudo_prefix . "adb connect ".$ip_address);
	}
	public function addCmd($name,$type='action',$subtype='other',$configuration='',$unite='',$value=''){
		$cmd = $this->getCmd(null, $name);
		if (!is_object($cmd)) {
			$cmd = new AndroidTVCmd();
			$cmd->setLogicalId($name);
			$cmd->setName(__($name, __FILE__));
		}
		$cmd->setType($type);
		$cmd->setUnite($unite);
		$cmd->setSubType($subtype);
		$cmd->setEqLogic_id($this->getId());
		if(is_array($configuration)){
			foreach($configuration as $key => $value)
				$cmd->setConfiguration($key, $value);
		}
		$cmd->setValue($value);
		$cmd->save();
		return $cmd;
	}
	public function postSave() {
		////////////////////////////////////////////////////////////  Création des commandes /////////////////////////////////////////////////////////////////
		$this->addCmd("disk_total","info","string",array('categorie'=> "commande"));
		$this->addCmd("disk_free","info","string",array('categorie'=> "commande"));
		$this->addCmd("resolution","info","string",array('categorie'=> "commande"));
		$this->addCmd("type","info","string",array('categorie'=> "commande"));
		$this->addCmd("version_android","info","string",array('categorie'=> "commande"));
		$this->addCmd("name","info","string",array('categorie'=> "commande"));
		$this->addCmd("power_state","info","binary",array('categorie'=> "commande"));
		$this->addCmd("encours","info","string",array('categorie'=> "commande"));
		$this->addCmd("title","info","string",array('categorie'=> "commande"));
		$this->addCmd("play_state","info","string",array('categorie'=> "commande"));
		$this->addCmd("battery_level","info","numeric",array('categorie'=> "commande"));
		$this->addCmd("battery_status","info","string",array('categorie'=> "commande"));
		$this->addCmd("mainmenu","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 3"));
		$this->addCmd("power_set","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 26"));
		$this->addCmd("play","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 85"));
		$this->addCmd("stop","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 86"));
		$this->addCmd("up","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 19"));
		$this->addCmd("down","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 20"));
		$this->addCmd("left","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 21"));
		$this->addCmd("right","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 22"));
		$this->addCmd("return","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 4"));
		$this->addCmd("enter","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 23"));
		$this->addCmd("volume+","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 24"));
		$this->addCmd("volume-","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 25"));
		$this->addCmd("mute","action","other",array('categorie'=> "commande",'commande'=>"shell input keyevent 164"));
		$this->addCmd("reboot","action","other",array('categorie'=> "commande",'commande'=>"shell reboot"));	
		///////////////////////////////////////////////////  Création des commandes de raccourcis d'application///////////////////////////////////////////////
		$this->addCmd("netflix","action","other",array('categorie'=> "appli",'icon'=>"netflix.png",'commande'=>"shell am start com.netflix.ninja/.MainActivity"));
		$this->addCmd("youtube","action","other",array('categorie'=> "appli",'icon'=>"youtube.png",'commande'=>"shell monkey -p com.google.android.youtube.tv -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("plex","action","other",array('categorie'=> "appli",'icon'=>"plex.png",'commande'=>"shell monkey -p com.plexapp.android -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("kodi","action","other",array('categorie'=> "appli",'icon'=>"kodi.png",'commande'=>"shell monkey -p org.xbmc.kodi -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("molotov","action","other",array('categorie'=> "appli",'icon'=>"molotov.png",'commande'=>"shell monkey -p tv.molotov.app -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("spotify","action","other",array('categorie'=> "appli",'icon'=>"spotify.png",'commande'=>"shell monkey -p com.spotify.tv.android -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("amazonvideo","action","other",array('categorie'=> "appli",'icon'=>"amazonvideo.png",'commande'=>"shell monkey -p com.amazon.amazonvideo.livingroom.nvidia -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("vevo","action","other",array('categorie'=> "appli",'icon'=>"vevo.png",'commande'=>"shell monkey -p com.vevo -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("mytf1","action","other",array('categorie'=> "appli",'icon'=>"tf1.png",'commande'=>"shell monkey -p fr.tf1.mytf1 -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("m6replay","action","other",array('categorie'=> "appli",'icon'=>"m6replay.png",'commande'=>"shell monkey -p fr.m6.m6replay.by -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("dsvideo","action","other",array('categorie'=> "appli",'icon'=>"dsvideo.png",'commande'=>"shell monkey -p com.synology.dsvideo -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("ted","action","other",array('categorie'=> "appli",'icon'=>"ted.png",'commande'=>"shell monkey -p com.ted.android.tv -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("leanback","action","other",array('categorie'=> "appli",'icon'=>"home.png",'commande'=>"shell input keyevent 3"));
		$this->addCmd("tvlauncher","action","other",array('categorie'=> "appli",'icon'=>"home.png",'commande'=>"shell input keyevent 3"));
		$this->addCmd("zapster","action","other",array('categorie'=> "appli",'icon'=>"freeboxtv.png",'commande'=>"shell am start org.droidtv.zapster/.playtv.activity.PlayTvActivity"));
		$this->addCmd("freebox","action","other",array('categorie'=> "appli",'icon'=>"freeboxtv.png",'commande'=>"shell monkey -p fr.freebox.tv -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("mycanal","action","other",array('categorie'=> "appli",'icon'=>"mycanal.png",'commande'=>"shell monkey -p com.canal.android.canal -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("stb.emu","action","other",array('categorie'=> "appli",'icon'=>"television.png",'commande'=>"shell monkey -p com.mvas.stb.emu.pro -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("deezer","action","other",array('categorie'=> "appli",'icon'=>"deezer.png",'commande'=>"shell monkey -p  -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("tinycam free","action","other",array('categorie'=> "appli",'icon'=>"tinycamfree.png",'commande'=>"shell monkey -p com.alexvas.dvr -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("tinycam pro","action","other",array('categorie'=> "appli",'icon'=>"tinycampro.png",'commande'=>"shell monkey -p com.alexvas.dvr.pro -c android.intent.category.LAUNCHER 1"));
		$this->addCmd("mediashell","action","other",array('categorie'=> "appli",'icon'=>"home.png",'commande'=>""));
		$this->addCmd("Freebox by Oqee","action","other",array('categorie'=> "appli",'icon'=>"freeboxtv.jpg",'commande'=>"am start -n net.oqee.androidtv/.ui.main.MainActivity"));
		
		$volume=$this->addCmd('Volume','info','numeric',array('categorie'=> 'commande'),'%');
		$this->addCmd('setVolume','action','slider',array('categorie'=> 'commande'),'',$volume->getId());

		$sudo = exec("\$EUID");
		if ($sudo != "0")
		$sudo_prefix = "sudo ";
		if ($this->getConfiguration('type_connection') == "TCPIP") {
			log::add('AndroidTV', 'debug', $this->getHumanName() . " Restart ADB en mode TCP");
			$check = shell_exec($sudo_prefix . "adb devices TCPIP 5555");
		} elseif ($this->getConfiguration('type_connection') == "SSH") {
			log::add('AndroidTV', 'debug', $this->getHumanName() . " Check de la connection SSH");
		} else{
			log::add('AndroidTV', 'debug', $this->getHumanName() . " Restart ADB en mode USB");
			$check = shell_exec($sudo_prefix . "adb devices USB");
		}
	}
	public function preUpdate(){
	if ($this->getConfiguration('ip_address') == '') 
		throw new \Exception(__('L\'adresse IP doit être renseignée', __FILE__));
	}
	public function getInfo(){
		if($this->checkAndroidTVStatus() === false)
			return false;
		$sudo = exec("\$EUID");
		if ($sudo != "0")
			$sudo_prefix = "sudo ";
		$ip_address = $this->getConfiguration('ip_address');
		$infos['power_state'] = substr($this->runcmd("shell dumpsys power -h | grep \"Display Power\" | cut -c22-"), 0, -1);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " power_state: " . $infos['power_state']);
		$infos['encours']     = substr($this->runcmd("shell dumpsys window windows | grep -E 'mFocusedApp'| cut -d / -f 1 | cut -d ' ' -f 7"), 0, -1);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " encours: " .$infos['encours'] );
		$infos['version_android']     = substr($this->runcmd("shell getprop ro.build.version.release"), 0, -1);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " version_android: " .$infos['version_android'] );
		$infos['name']        = substr($this->runcmd("shell getprop ro.product.model"), 0, -1);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " name: " .$infos['name'] );
		$infos['type']        = substr($this->runcmd("shell getprop ro.build.characteristics"), 0, -1);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " type: " .$infos['type']);
		$infos['resolution']  = substr($this->runcmd("shell dumpsys window displays | grep init | cut -c45-53"), 0, -1);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " resolution: " .$infos['resolution'] );
		$infos['disk_free'] = substr($this->runcmd("shell dumpsys diskstats | grep Data-Free | cut -d' ' -f7"), 0, -1);
		log::add('AndroidTV', 'debug',$this->getHumanName() . " disk_free: " .$infos['disk_free'] );
		$infos['disk_total'] = round(intval(substr($this->runcmd("shell dumpsys diskstats | grep Data-Free | cut -d' ' -f4"), 0, -1))/1000000, 1);
		log::add('AndroidTV', 'debug', "disk_total: " .$infos['disk_total']);
		//$infos['title'] = substr($this->runcmd("shell dumpsys bluetooth_manager | grep MediaPlayerInfo | grep .$infos['encours']. |cut -d')' -f3 | cut -d, -f1 | grep -v null | sed 's/^\ *//g'"), 0);
		//log::add('AndroidTV', 'debug', $this->getHumanName() . "title: " .$infos['title']);
		//$infos['volume'] = substr($this->runcmd("shell media volume --stream 3 --get | grep volume |grep is | cut -d -f4"), 0, -1);
		//log::add('AndroidTV', 'debug',$this->getHumanName() . "volume: " .$infos['volume']);
		$infos['play_state']  = substr($this->runcmd("shell dumpsys bluetooth_manager | grep mCurrentPlayState | cut -d,  -f1 | cut -c43-"), 0, -1);
		log::add('AndroidTV', 'debug',  $this->getHumanName() . " play_state: " .$infos['play_state'] );
		$infos['battery_level']  = substr($this->runcmd("shell dumpsys battery | grep level | cut -d: -f2"), 0, -1);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " battery_level: " .$infos['battery_level']);
		$infos['battery_status']  = substr($this->runcmd("shell dumpsys battery | grep status"), -3);
		log::add('AndroidTV', 'debug', $this->getHumanName() . " battery_status: " .$infos['battery_status']);
		return $infos;
	}
	public function updateInfo(){
		try {
			$infos = $this->getInfo();
			if($infos === false)
				return;
		} catch (\Exception $e) {
			return;
		}
		if (!is_array($infos)) 
			return;
		log::add('AndroidTV', 'info', $this->getHumanName() . ' Rafraichissement des informations');
		if (isset($infos['power_state'])) 
			$this->checkAndUpdateCmd('power_state', ($infos['power_state'] == "ON") ? 1 : 0 );
		if (isset($infos['encours'])) {
			$encours = $this->getCmd(null, 'encours');
			$app_known = 0;
			foreach ($this->getCmd() as $cmd) {
				if (stristr($infos['encours'], $cmd->getName())){
					$encours->setDisplay('icon', 'plugins/AndroidTV/desktop/images/'.$cmd->getConfiguration('icon'));
					$this->checkAndUpdateCmd('encours', $cmd->getName());
					$app_known = 1;
				}
			}
			if (!$app_known) 
				log::add('AndroidTV', 'info', $this->getHumanName() . ' Application '.$infos['encours'].' non reconnu.');
			$encours->save();
		}
		if (isset($infos['version_android'])) 
			$this->checkAndUpdateCmd('version_android', $infos['version_android']);
		if (isset($infos['name'])) 
			$this->checkAndUpdateCmd('name', $infos['name']);
		if (isset($infos['type'])) 
			$this->checkAndUpdateCmd('type', $infos['type']);
		if (isset($infos['resolution'])) 
			$this->checkAndUpdateCmd('resolution', $infos['resolution']);
		if (isset($infos['disk_free']))
			$this->checkAndUpdateCmd('disk_free', $infos['disk_free']);
		if (isset($infos['disk_total'])) 
			$this->checkAndUpdateCmd('disk_total', $infos['disk_total']);
		if (isset($infos['title'])) 
			$this->checkAndUpdateCmd('title', $infos['title']);
		if (isset($infos['volume']))
			$this->checkAndUpdateCmd('Volume', $infos['volume']);
		if (isset($infos['play_state'])) {
			if ($infos['play_state'] == 2) 
				$this->checkAndUpdateCmd('play_state', "pause");
			elseif ($infos['play_state'] == 3)
				$this->checkAndUpdateCmd('play_state', "lecture");
			elseif ($infos['play_state'] == 0)
				$this->checkAndUpdateCmd('play_state', "arret");
			else
				$this->checkAndUpdateCmd('play_state',"inconnue");
		}
		if (isset($infos['battery_level'])) 
			$this->checkAndUpdateCmd('battery_level', $infos['battery_level']);
		if (isset($infos['battery_status'])) {
			if ($infos['battery_status'] == 2)
				$this->checkAndUpdateCmd('battery_status',"en charge");
			elseif ($infos['battery_status'] == 3)
				$this->checkAndUpdateCmd('battery_status',"en décharge");
			elseif ($infos['battery_status'] == 4)
				$this->checkAndUpdateCmd('battery_status',"pas de charge");
			elseif ($infos['battery_status'] == 5 )
				$this->checkAndUpdateCmd('battery_status',"pleine");
			else
				$this->checkAndUpdateCmd('battery_status',"inconnue");
		}
	}
	public function checkAndroidTVStatus(){
		$sudo = exec("\$EUID");
		if ($sudo != "0")
			$sudo_prefix = "sudo ";
		$ip_address = $this->getConfiguration('ip_address');			
		if ($this->getConfiguration('type_connection') == "TCPIP") {
			log::add('AndroidTV', 'debug', $this->getHumanName() . " Check de la connection TCPIP");
			$check = shell_exec($sudo_prefix . "adb devices | grep " . $ip_address . " | cut -f2 | xargs");
		} elseif ($this->getConfiguration('type_connection') == "SSH") {
			log::add('AndroidTV', 'debug', $this->getHumanName() . " Check de la connection SSH");
		} else{
			log::add('AndroidTV', 'debug', $this->getHumanName() . " Check de la connection USB");
			$check = shell_exec($sudo_prefix . "adb devices | grep " . $ip_address . " | cut -f2 | xargs");
		}
		if (strstr($check, "offline")) {
			$cmd = $this->getCmd(null, 'encours');
			log::add('AndroidTV', 'info',$this->getHumanName() . ' Votre appareil est offline');
			$cmd->setDisplay('icon', 'plugins/AndroidTV/desktop/images/erreur.png');
			$cmd->save();
			//$this->connectADB($ip_address);
			return false;
		} elseif (!strstr($check, "device")) {
			$cmd = $this->getCmd(null, 'encours');
			$cmd->setDisplay('icon', 'plugins/AndroidTV/desktop/images/erreur.png');
			$cmd->save();
			log::add('AndroidTV', 'info', $this->getHumanName() . ' Votre appareil n\'est pas détecté par ADB.');
			$this->connectADB($ip_address);
		} elseif (strstr($check, "unauthorized")) {
			$cmd = $this->getCmd(null, 'encours');
			$cmd->setDisplay('icon', 'plugins/AndroidTV/desktop/images/erreur.png');
			$cmd->save();
			log::add('AndroidTV', 'info',$this->getHumanName() . ' Votre connection n\'est pas autorisé');
			$this->connectADB($ip_address);
		}
	}
	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace))
			return $replace;
		$version = jeedom::versionAlias($_version);
		$replace['#version#'] = $_version;
		if ($this->getDisplay('hideOn' . $version) == 1)
			return '';
		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_history#'] = '';
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
			$replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
			if ($cmd->getLogicalId() == 'encours')
				$replace['#thumbnail#'] = $cmd->getDisplay('icon');
			if ($cmd->getLogicalId() == 'play_state'){
				if($cmd->execCmd() == 'play')
					$replace['#play_pause#'] = '"fa fa-pause  fa-lg" style="color:green"';
				else
					$replace['#play_pause#'] = '"fa fa-play  fa-lg"';
				
			}
			if ($cmd->getIsHistorized() == 1) 
				$replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
			$replace['#' . $cmd->getLogicalId() . '_id_display#'] = ($cmd->getIsVisible()) ? '#' . $cmd->getLogicalId() . "_id_display#" : "none";
		}
		$replace['#applis#'] = "";
		foreach ($this->getCmd('action') as $cmd) {
			if ($cmd->getConfiguration('categorie') == 'appli'){
				$replace['#applis#'] = $replace['#applis#'] . '<a class="btn cmd icons noRefresh" style="display:#'.$cmd->getLogicalId().'_id_display#; padding:3px" data-cmd_id="'.$cmd->getId().'" title="'.$cmd->getName().'" onclick="jeedom.cmd.execute({id: '.$cmd->getId().'});"><img src="plugins/AndroidTV/desktop/images/'.$cmd->getConfiguration('icon') .'"></a>';
			}else{
				$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
				$replace['#' . $cmd->getLogicalId() . '_id_display#'] = (is_object($cmd) && $cmd->getIsVisible()) ? '#' . $cmd->getId() . "_id_display#" : 'none';
			}
			$replace['#' . $cmd->getLogicalId() . '_id_display#'] = ($cmd->getIsVisible()) ? '#' . $cmd->getLogicalId() . "_id_display#" : "none";
		}
		$replace['#ip#'] = $this->getConfiguration('ip_address');
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'eqLogic', 'AndroidTV')));
	}
}
