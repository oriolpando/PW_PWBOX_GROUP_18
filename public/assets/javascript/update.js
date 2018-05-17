var file;


function updateDb(event) {
    var mail = document.getElementById("mailUp").value;
    var psw = document.getElementById("passUp").value;
    var confPsw = document.getElementById("passConfUp").value;

    if (psw != confPsw){
        event.preventDefault();
        alert("Password sobra!");
    }else{
        if (!validateEmail(mail)) {
            event.preventDefault();
            alert("Mail sobra!")
        }else{
            console.log(file);
            var fd = new FormData();
            // These extra params aren't necessary but show that you can include other data.
            fd.append("email", mail);
            fd.append("psw", psw);
            fd.append("pswConf", confPsw);
            fd.append("img", file);


            console.log(mail + psw + confPsw);
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open('POST', '/updateUser', true);

            xmlhttp.send(fd);

            xmlhttp.onreadystatechange = function () {
                var DONE = 4; // readyState 4 means the request is done.
                var OK = 200; // status 200 is a successful return.
                if (xmlhttp.readyState === DONE) {
                    if (xmlhttp.status === OK){
                        var x = xmlhttp.response;
                        console.log(x);
                        $('#EditInformation').modal('hide');
                        document.getElementById("mail").innerHTML = mail;
                        updateImage(x);

                    }else {
                        alert("Error on update!");
                    }
                }

            }

        }

    }

}
function updateImage(x) {
    document.getElementById("CurrentImageUser").src = x + "?" + Math.random();
    document.getElementById("NewImageUser").src = x + "?" + Math.random();
    document.getElementById("imageUser").src = x + "?" + Math.random();
    setTimeout(updateImage, 60000);
    return;
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function editInformation() {
    var editInf = document.getElementById("editInformation");
    editInf.style.display = "block";
}

function deleteUs(){
    location.href = "/deleteUser";
    alert("The user has deleted!");
}

function readURL(input) {
    var x = document.getElementById("buttonChange");
    console.log(x.files[0]);
    file = x.files[0];
}
