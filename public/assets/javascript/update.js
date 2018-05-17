var reader;
var change = 0;


function updateDb() {
    var mail = document.getElementById("mailUp").value;
    var psw = document.getElementById("passUp").value;
    var confPsw = document.getElementById("passConfUp").value;
    var im = document.getElementById("newImageUser").src;

    if (psw != confPsw){
        console.log("fuck2");
    }else{
        console.log(mail + psw + confPsw);
        var xmlhttp = new XMLHttpRequest();
        if (change == 0){
            xmlhttp.open("GET","/updateUser?email="+mail+"&psw="+psw+"&pswConf="+confPsw,true);
        }else{
            xmlhttp.open("GET","/updateUser?email="+mail+"&psw="+psw+"&pswConf="+confPsw+"&image="+reader,true);
        }
        change = 0;
        xmlhttp.send();

        xmlhttp.onreadystatechange = function () {
            var DONE = 4; // readyState 4 means the request is done.
            var OK = 200; // status 200 is a successful return.
            if (xmlhttp.readyState === DONE) {
                if (xmlhttp.status === OK){

                    $('#EditInformation').modal('hide');
                    console.log("fuck11");
                    document.getElementById("mail").innerHTML = mail;
                }else {
                    console.log("fuckv2")
                }
            }

        }
    }

}

function editInformation() {
    var editInf = document.getElementById("editInformation");
    editInf.style.display = block;
}

function deleteUs(){
    location.href = "/deleteUser";
    alert("The user has deleted!");
}

function readURL(input) {
    if (input.files && input.files[0]) {
        reader = new FileReader();

        reader.onload = function (e) {
            $('#imageUser')
                .attr('src', e.target.result)
                .width(50)
                .height(50);
        };

        reader.readAsDataURL(input.files[0]);
        change = 1;
    }
}
