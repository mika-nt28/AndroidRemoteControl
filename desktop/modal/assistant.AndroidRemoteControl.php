<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id='div_assisantAndroidTVAlert' style="display: none;"></div>
<!-- <button id="go" class="btn btn-primary" type="button" >{{Rechercher}}</button>
<input id="search" type="text" name="page_no" size="3"/> -->

<!--changed input type to button and added an id of go-->
<!-- <input id="go" type="button" name="goto" value="Go"/> -->
<!-- <br/><br/><br/> -->
<div id="contentAssistant" style="height: 100%">
</div>
<script type="text/javascript" src="/plugins/AndroidTV/3rdparty/hilitor.js"></script>
<script>
	$("#go").click(function(){
		var search = document.getElementById("search").value;
		var myHilitor = new Hilitor2("contentAssistant","docIspyConnect");
  	myHilitor.apply(search);
	});
</script>
