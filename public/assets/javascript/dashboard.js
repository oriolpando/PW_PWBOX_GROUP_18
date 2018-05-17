function enterFolder(id) {
    location.href = "/enterFolder/" + id;
}
function enterSharedFolder(id) {
    location.href = "/enterSharedFolder/" + id;
}
function toRoot() {
    location.href = "/toRoot";
}
function toSharedRoot() {
    location.href = "/toSharedRoot";
}
function deleteItem(id) {
    $('#DeleteItem').modal("show");
    $("#deleteIt").val(id);
    console.log($("#deleteIt").val());
}
function deleteItemFin() {
    console.log($("#deleteIt").val());
    location.href = "/delete/" + $("#deleteIt").val();
}
function shareItem(id) {
    $('#ModalShare').modal("show");
    $("#idFolder").val(id);
    console.log($("#idFolder").val());
}
function renameItem(id) {
    $('#Rename').modal("show");
    $("#renameId").val(id);
    console.log($("#renameId").val());
}
function renameItemFin() {
    var name = document.getElementById("rename").value;
    var id = $("#renameId").val();
    console.log(name);
    console.log(id);
    location.href = "/rename/" + id + "/" + name;
}
function downloadItem(id) {
    location.href = "/download/" + id;
}

function resetFitxers() {

    var pare = document.getElementById("formFitxer");

    var children = pare.children;

    var child = pare.lastChild;

    while (child.tagName == 'INPUT'){
        pare.removeChild(child);
        child = pare.lastChild;
    }


}

function nameValid(name) {

    if (name.indexOf('.') > -1) {

        //aixo significa que el nom que li ha posat te un punt

    }
}
function plusFitxer() {


    var nom = document.getElementById("formFitxer").lastElementChild.name;
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


function resetFitxers2() {

    var pare = document.getElementById("formFitxer2");

    var children = pare.children;

    var child = pare.lastChild;

    while (child.tagName == 'INPUT'){
        pare.removeChild(child);
        child = pare.lastChild;
    }


}

function plusFitxer2() {


    var nom = document.getElementById("formFitxer2").lastElementChild.name;
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
    document.getElementById('formFitxer2').appendChild(i);


}