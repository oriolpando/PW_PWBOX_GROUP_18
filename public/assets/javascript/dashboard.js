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



function resetFitxers() {

    var pare = document.getElementById("formFitxer");

    var children = pare.children;

    var child = pare.lastChild;

    alert(child.tagName);
    while (child.tagName == 'INPUT'){
        pare.removeChild(child);
        child = pare.lastChild;
    }


}

function plusFitxer() {


    var nom = document.getElementById("formFitxer").lastElementChild.name;
alert(nom);
    var i = document.createElement("input");
    i.setAttribute('accept',".jpg,.png,.gif,.pdf,.md,.txt");
    i.setAttribute('type',"file");


    if (nom===("resetUpload")){
       nom = "fitxerUpload0";

    i.setAttribute('name',nom);
    i.setAttribute('id',nom);
    }else {
        var num;
        if (!isNaN(parseInt(nom.substr(nom.length - 3)))){
            num =parseInt(nom.substr(nom.length - 3))+1;

        }else{
            if (!isNaN(parseInt(nom.substr(nom.length - 2)))) {
                num = parseInt(nom.substr(nom.length - 2)) + 1;
            }else{
                num = parseInt(nom.substr(nom.length - 1)) + 1;
            }
        }

        nom = "fitxerUpload"+num;
        i.setAttribute('name',nom);
        i.setAttribute('id',nom);

    }
    document.getElementById('formFitxer').appendChild(i);



}