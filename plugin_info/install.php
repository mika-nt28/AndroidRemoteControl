<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function AndroidTV_install() {
      exec('../3rdparty/install.sh');
}

function AndroidTV_update() {
}
function AndroidTV_remove() {
    exec('../3rdparty/remove.sh');
}

?>
