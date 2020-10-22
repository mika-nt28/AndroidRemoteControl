<?php

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
include_file('desktop', 'AndroidTVForJeedomConfiguration', 'js', 'AndroidTV');

?>
<form class="form-horizontal">
  <div class="panel-body">
    <div class="form-group">
      <label class="col-sm-2 control-label">{{Réparer :}}</label>
			<div class="col-sm-4">
		<a class="btn btn-warning" id="bt_resetAndroidTV"><i class="fa fa-check"></i> {{Relancer le service ADB}}</a>
    </div>      
</div>  
</form>
<script>
  $('#bt_resetAndroidTV').on('click',function(){
  		bootbox.confirm('{{Etes-vous sûr de vouloir relancer le service ADB ?}}', function (result) {
  			if (result) {
              	$.post({
        url: 'plugins/AndroidTV/core/ajax/AndroidTV.ajax.php',
        data: {
            action: 'resetADB'
        },
        dataType: 'json',
        success: function (data, status) {
            // Test si l'appel a échoué
            if (data.state !== 'ok' || status !== 'success') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
            else {

            }
        },
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        }
    });	

  			}
  		});
  	});
</script>
