<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id='div_resetAndroidTVAlert' style="display: none;"></div>
<a class="btn btn-warning pull-right" data-state="1" id="bt_AndroidTVLogStopStart"><i class="fa fa-pause"></i> {{Pause}}</a>
<input class="form-control pull-right" id="in_AndroidTVLogSearch" style="width : 300px;" placeholder="{{Rechercher}}" />
<br/><br/><br/>
<pre id='pre_AndroidTVreset' style='overflow: auto; height: 90%;with:90%;'></pre>


<script>
	$.ajax({
		type: 'POST',
		url: 'plugins/AndroidTV/core/ajax/AndroidTV.ajax.php',
		data: {
			action: 'reset'
		},
		dataType: 'json',
		global: false,
		error: function (request, status, error) {
			handleAjaxError(request, status, error, $('#div_resetAndroidTVAlert'));
		},
		success: function () {
			 jeedom.log.autoupdate({
			       log : 'AndroidTV',
			       display : $('#pre_AndroidTV'),
			       search : $('#in_AndroidTVLogSearch'),
			       control : $('#bt_AndroidTVLogStopStart'),
           		});
		}
	});
</script>
