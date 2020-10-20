<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id='div_resetAndroidRemoteControlAlert' style="display: none;"></div>
<a class="btn btn-warning pull-right" data-state="1" id="bt_AndroidRemoteControlLogStopStart"><i class="fa fa-pause"></i> {{Pause}}</a>
<input class="form-control pull-right" id="in_AndroidRemoteControlLogSearch" style="width : 300px;" placeholder="{{Rechercher}}" />
<br/><br/><br/>
<pre id='pre_AndroidRemoteControlreset' style='overflow: auto; height: 90%;with:90%;'></pre>


<script>
	$.ajax({
		type: 'POST',
		url: 'plugins/AndroidRemoteControl/core/ajax/AndroidRemoteControl.ajax.php',
		data: {
			action: 'reset'
		},
		dataType: 'json',
		global: false,
		error: function (request, status, error) {
			handleAjaxError(request, status, error, $('#div_resetAndroidRemoteControlAlert'));
		},
		success: function () {
			 jeedom.log.autoupdate({
			       log : 'AndroidRemoteControl',
			       display : $('#pre_AndroidRemoteControl'),
			       search : $('#in_AndroidRemoteControlLogSearch'),
			       control : $('#bt_AndroidRemoteControlLogStopStart'),
           		});
		}
	});
</script>
