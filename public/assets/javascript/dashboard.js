function enterFolder(id) {
    location.href = "/enterFolder/" + id;
}
function enterSharedFolder(id) {
    location.href = "/enterSharedFolder/" + id;
}
function toRoot() {
    location.href = "/toRoot";
}
function deleteItem(id) {
    location.href = "/delete/" + id;
}
function shareItem(id) {
    $('#ModalShare').modal("show");
    $("#idFolder").val(id);
    console.log($("#idFolder").val());
}
function renameItem(id) {
    location.href = "/rename/" + id;
}
function downloadItem(id) {
    location.href = "/download/" + id;
}