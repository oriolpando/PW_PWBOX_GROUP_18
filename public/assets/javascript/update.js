var file;

function noError() {
    var errorUpdate = document.getElementById("errUpdate");
    errorUpdate.style.display = "none";
}

function updateDb(event) {

    var image = document.getElementById("NewImageUser");
    var email = document.getElementById("mailUp").value;
    var psw = document.getElementById("passUp").value;
    var confirmPsw = document.getElementById("passConfUp").value;

    var errorImage = false;
    var errorEmail = false;
    var errorPsw = false;
    var errorConfirmPsw = false;

    var spanEmail = document.getElementById("spanEmailUpdate");
    spanEmail.style.display = "none";
    var spanPsw = document.getElementById("spanPswUpdate");
    spanPsw.style.display = "none";
    var spanCpsw = document.getElementById("spanCpswUpdate");
    spanCpsw.style.display = "none";
    var errorUpdate = document.getElementById("errUpdate");
    errorUpdate.style.display = "none";

    psw.onfocus = function () {
        document.getElementById("message").style.display = "block";
    }

    if (!(file == null)) {
        var mesureImage = Math.round((file["size"] / 1024));

        if (mesureImage >= 0) {
            errorImage = GetFileSize(mesureImage);
        }
    }

    if (!validateEmail(email)) {
        errorEmail = true;
        spanEmail.style.display = "block";
    }
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;

    if ((psw.length < 6) || (psw.length > 12) || (!(psw.match(numbers))) || (!psw.match(upperCaseLetters))) {
        errorPsw = true;
        spanPsw.style.display = "block";
    }
    if (confirmPsw == "") {
        errorConfirmPsw = true;
        spanCpsw.style.display = "block";
    }

    if ((!errorPsw && !errorConfirmPsw)) {

        if (psw != confirmPsw) {
            spanCpsw.style.display = "block";
        }

    }
    if((errorPsw) || (errorConfirmPsw) || (errorEmail) || (errorImage)){
        event.preventDefault();

    }else{

        var fd = new FormData();
        // These extra params aren't necessary but show that you can include other data.
        fd.append("email", email);
        fd.append("psw", psw);
        fd.append("pswConf", confirmPsw);
        fd.append("img", file);

        console.log(email + psw + confirmPsw);
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
                    document.getElementById("mail").innerHTML = email;
                    updateImage(x);
                }else {
                    var errorUpdate = document.getElementById("errUpdate");
                    errorUpdate.style.display = "block";
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
function editInformation() {
    var editInf = document.getElementById("editInformation");
    editInf.style.display = "block";
}

function deleteUs(){
    location.href = "/deleteUser";
}

function readURL(input) {
    var x = document.getElementById("buttonChange");
    console.log(x.files[0]);
    file = x.files[0];
}

function validatePsw() {

    var numbers = /[0-9]/g;
    if(passUp.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
    } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
    }
    var upperCaseLetters = /[A-Z]/g;
    if(passUp.value.match(upperCaseLetters)) {
        uppercase.classList.remove("invalid");
        uppercase.classList.add("valid");
    } else {
        uppercase.classList.remove("valid");
        uppercase.classList.add("invalid");
    }

    // Validate length
    if(passUp.value.length >= 6) {
        lengthmin.classList.remove("invalid");
        lengthmin.classList.add("valid");
    } else {
        lengthmin.classList.remove("valid");
        lengthmin.classList.add("invalid");
    }

    // Validate length
    if(passUp.value.length > 12) {
        lengthmax.classList.remove("valid");
        lengthmax.classList.add("invalid");
    } else {
        lengthmax.classList.remove("invalid");
        lengthmax.classList.add("valid");
    }
}

function correctPsw() {

    var psw = document.getElementById("psw").value;
    var confirmPsw = document.getElementById("confirmPsw").value;

    var msg2 = document.getElementById("message2");

    if (psw != confirmPsw) {
        errorConfirmPsw = true;
    }
    if (psw == confirmPsw){
        msg2.style.display = "block";
    }
}

function focusFunction() {
    document.getElementById("message").style.display = "block";
}

function blurFunction() {
    document.getElementById("message").style.display = "none";
}

function GetFileSize(mesureImage) {



        console.log(mesureImage);

        if (mesureImage > 500){
            document.getElementById('fp2').innerHTML =
                document.getElementById('fp2').innerHTML + 'The image size is<br /> ' +
                '<b>' + mesureImage + '</b> KB and it has to be less than 500KB';

            return true;

        }else{
            return false;
        }


}