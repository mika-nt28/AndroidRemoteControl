$(document).ready(function () {
       $('#show-jeedom-modal').click(function() {
        showModal('Informations', 'jeedom');
        return false;
    });
});
function showConfigModal() {
    showModal('Configuration', 'config');
}
function showModal(title, modalName) {
    var modal = $('#md_modal');
    modal.dialog({title: title});
    modal.load('index.php?v=d&plugin=AndroidTV&modal='+modalName+'.AndroidTV').dialog('open');
}
