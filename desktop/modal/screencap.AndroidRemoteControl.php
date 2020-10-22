<?php

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

if (init('id') == '') {
    throw new Exception('{{L\'id de l\'opération ne peut etre vide : }}' . init('op_id'));
}

$id = init('id');
$ip_address = init('ip');

?>
<div class="container-modal">
<div id="image"></div>
</div>
  
<script>
$(document).ready(function() {
<?php shell_exec("sudo adb -s $ip_address:5555 shell screencap -p > /var/www/html/plugins/AndroidTV/3rdparty/screencap$id.png"); ?>
$('#image').html('<img src="/plugins/AndroidTV/3rdparty/screencap<?php echo  $id ?>.png">');

});
</script>
<style>
  img {
    max-width: 100%;
    max-height: 100%;
}
</style>
