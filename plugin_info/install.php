<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function AndroidRemoteControl_install() {
      exec('../3rdparty/install.sh');
}

function AndroidRemoteControl_update() {
    exec('../3rdparty/reset.sh');
}


function AndroidRemoteControl_remove() {
    exec('../3rdparty/reset.sh');
    exec('../3rdparty/remove.sh');
}

?>
