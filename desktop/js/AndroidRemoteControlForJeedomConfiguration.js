$(document).ready(function () {
       $('#show-nextdom-modal').click(function() {
        showModal('Informations', 'nextdom');
        return false;
    });
});
function showConfigModal() {
    showModal('Configuration', 'config');
}
function showModal(title, modalName) {
    var modal = $('#md_modal');
    modal.dialog({title: title});
    modal.load('index.php?v=d&plugin=AndroidRemoteControl&modal='+modalName+'.AndroidRemoteControl').dialog('open');
}