

<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}


               
$dir = __DIR__ . '/../images/';
//sendVarToJS('eqDir', $path = init('id'));
?>

<div class="row row-overflow">
	<span class="btn btn-default btn-file pull-right">
		<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImageIcon" type="file" name="file" style="display: inline-block;" accept=".jpg, .png">
	</span>
  	</br>
  	</br>
  	<div style="height: 100%;margin-left: 15px; display: flex; flex-wrap: wrap;">
	<?php
		$files = scandir($dir);
		foreach ($files as $file) {
			if (substr($file, -4) == '.png' or substr($file, -4) == '.jpg' or substr($file, -4) == '.JPG') {
				
              	echo '<div style="height: 140px;min-width: 120px;display: flex;flex-direction: column;align-items: center;justify-content: flex-end;">';
              	echo '<div>';
              	echo '<img src="plugins/AndroidTV/desktop/images/'. $file .'" style="width: auto;height: auto;max-width: 80px;max-height: 80px;display: flex;justify-content: center;align-items: center;">';
                echo "</div>";     
              	echo '<div style="font-size: 0.8em;word-wrap: break-word;">';
				echo substr(basename($file),0,12);
              	echo '</br>';
              	echo '</div>';
				echo '<div>'; 
              	echo '<a class="btn btn-danger btn-xs bt_removeImgIcon" data-filename="'.$file.'" title="Supprimer"><i class="fas fa-trash"></i> </a>';
				echo '<a class="btn btn-info btn-xs bt_copyLinkImg" data-link="'.$file.'" title="Lien"><i class="fas fa-external-link-alt"></i> </a>';
				echo '<a class="btn btn-success btn-xs" target="_blank" href="core/php/downloadFile.php?pathfile=' . urlencode('plugins/AndroidTV/desktop/images/'. $file ) . '" ><i class="fas fa-download"></i></a>';
                echo '</div> </br> </br>';	
				echo '<input type="text" value="'.$file.'" id="'. $file . '" style="position:absolute; opacity:0">';
				echo '</div>';
             
			}
		}
	?>
      </div>
</div>
                    
<script>

	$('#bt_uploadImageIcon').fileupload({
		replaceFileInput: false,
		url: 'plugins/AndroidTV/core/ajax/AndroidTV.ajax.php?action=imgUpload&jeedom_token='+JEEDOM_AJAX_TOKEN,
		dataType: 'json',
		done: function (e, data) {
			if (data.result.state != 'ok') {

				$('#div_iconSelectorAlert').showAlert({message: data.result.result, level: 'danger'});
				return;
			}

			$('#md_modal').empty().load('index.php?v=d&plugin=AndroidTV&modal=chooseIcone');

		}
	});

	$('.bt_removeImgIcon').on('click',function(){
		var filename = $(this).attr('data-filename');
		bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer cette image}} <span style="font-weight: bold ;">' + filename + '</span> ?', function (result) {
			if (result) {
				$.ajax({// fonction permettant de faire de l'ajax
					type: "POST", // methode de transmission des données au fichier php
					url: "plugins/AndroidTV/core/ajax/AndroidTV.ajax.php", // url du fichier php
					data: {
						action: "deleteImg",
						name: filename
					},
					dataType: 'json',
					error: function(request, status, error) {
						handleAjaxError(request, status, error);
					},
					success: function(data) { // si l'appel a bien fonctionné
						if (data.state != 'ok') {
							$('#div_alert').showAlert({message:  data.result,level: 'danger'});
							return;
						}
						$('#md_modal').empty().load('index.php?v=d&plugin=AndroidTV&modal=chooseIcone');
					}
				});				
			}
		});
	});
	
	$('.bt_copyLinkImg').on('click',function(){

		var path = $(this).attr('data-link');
		var copyText = document.getElementById(path);
		  copyText.select();
		  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

		  /* Copy the text inside the text field */
		  document.execCommand("copy");	
		
	});

</script>

<?php
	include_file('3rdparty', 'jquery.tree/jstree.min', 'js');
?>