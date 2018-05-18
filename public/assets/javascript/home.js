

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

function validatePsw() {

    var numbers = /[0-9]/g;
    if(psw.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
    } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
    }
    var upperCaseLetters = /[A-Z]/g;
    if(psw.value.match(upperCaseLetters)) {
        uppercase.classList.remove("invalid");
        uppercase.classList.add("valid");
    } else {
        uppercase.classList.remove("valid");
        uppercase.classList.add("invalid");
    }

    // Validate length
    if(psw.value.length >= 6) {
        lengthmin.classList.remove("invalid");
        lengthmin.classList.add("valid");
    } else {
        lengthmin.classList.remove("valid");
        lengthmin.classList.add("invalid");
    }

    // Validate length
    if(psw.value.length > 12) {
        lengthmax.classList.remove("valid");
        lengthmax.classList.add("invalid");
    } else {
        lengthmax.classList.remove("invalid");
        lengthmax.classList.add("valid");
    }
}

function showLogin() {
    var login = document.getElementById("login");
    login.style.display = "block";
    var register = document.getElementById("register");
    register.style.display = "none";
    var landingPage = document.getElementById("principalPage");
    landingPage.style.display = "none";

}
function showRegister() {
    var landingPage = document.getElementById("principalPage");
    landingPage.style.display = "none";
    var login = document.getElementById("login");
    login.style.display = "none";
    var register = document.getElementById("register");
    register.style.display = "block";
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();

    if(dd<10){
        dd='0'+dd
    }
    if(mm<10){
        mm='0'+mm
    }

    today = yyyy+'-'+mm+'-'+dd;
    var date = document.getElementById("birth").setAttribute("max", today   );
}

function controlLogin(event) {

    var nameEmailU = document.getElementById("nameEmailU").value;
    var pswU = document.getElementById("pswU").value;

    var errorNameEmail = false;
    var errorPsw = false;

    var spanNameEmail = document.getElementById("spanNameU");
    spanNameEmail.style.display = "none";
    var spanNameEmailE = document.getElementById("spanNameUnotExists");
    spanNameEmailE.style.display = "none";
    var spanPsw = document.getElementById("spanPswU");
    spanPsw.style.display = "none";


    if (nameEmailU.indexOf('@') > -1) {

        if ((!validateEmail(nameEmailU)) || (nameEmailU == "")){
            errorNameEmail = true;
            spanNameEmail.style.display = "block";
        }

    }else{

        if (nameEmailU == "" || (nameEmailU.length > 20) || (nameEmailU.match("/^[0-9a-zA-Z]+$/"))){
            errorNameEmail = true;
            spanNameEmail.style.display = "block";
        }
    }

    function validateEmail(nameEmailU) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(nameEmailU).toLowerCase());
    }

    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;

    if((pswU == "") || (pswU.length < 6) || (pswU.length > 12) || (!(pswU.match(numbers))) || (!pswU.match(upperCaseLetters))) {
        errorPsw = true;
        spanPsw.style.display = "block";
    }

    console.log(errorNameEmail);
    console.log(errorPsw);

    if((errorNameEmail) || (errorPsw)){
        event.preventDefault();
        var login = document.getElementById("login");
        login.style.display = "block";
        var register = document.getElementById("register");
        register.style.display = "none";
        var landingPage = document.getElementById("principalPage");
        landingPage.style.display = "none";
    }
}

function controlRegister(event) {

    //comprovacions registre

    var name = document.getElementById("nameR").value;
    var username = document.getElementById("username").value;
    var surname = document.getElementById("surname").value;
    var email = document.getElementById("email").value;
    var psw = document.getElementById("psw").value;
    var confirmPsw = document.getElementById("confirmPsw").value;
    var birth = document.getElementById("birth").value;
    var image = document.getElementById("image");

    var errorName = false;
    var errorUsername = false;
    var errorSurname = false;
    var errorEmail = false;
    var errorPsw = false;
    var errorConfirmPsw = false;
    var errorBirth = false;
    var errorImage = false;

    var spanName = document.getElementById("spanName");
    spanName.style.display = "none";
    var spanUsername = document.getElementById("spanUsername");
    spanUsername.style.display = "none";
    var spanSurname = document.getElementById("spanSurname");
    spanSurname.style.display = "none";
    var spanEmail = document.getElementById("spanEmail");
    spanEmail.style.display = "none";
    var spanEmailExists = document.getElementById("spanEmailExists");
    spanEmailExists.style.display = "none";
    var spanBirth = document.getElementById("spanBirth");
    spanBirth.style.display = "none";
    var spanPsw = document.getElementById("spanPsw");
    spanPsw.style.display = "none";
    var spanCpsw = document.getElementById("spanCpsw");
    spanCpsw.style.display = "none";
    var msg2 = document.getElementById("message2");
    msg2.style.display = "none";

    psw.onfocus = function () {
        document.getElementById("message").style.display = "block";
    }

    //ERRORS REGISTRE

    errorImage = GetFileSize(image);

    if (name == "") {
        errorName = true;
        spanName.style.display = "block";
    }

    if ((username == "") || (username.length > 20) || (username.match("/^[0-9a-zA-Z]+$/"))) {
        errorUsername = true;
        spanUsername.style.display = "block";
    }

    if (surname == "") {
        errorSurname = true;
        spanSurname.style.display = "block";
    }

    if (!validateEmail(email)) {
        errorEmail = true;
        spanEmail.style.display = "block";
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
    // Match the date format through regular expression
   /* var now = new Date();
    if (selectedDate < now) {
        alert("Date must be in the future");
    }*/
    if(birth.match(dateformat) || (birth == "")){
        errorBirth = true;
        spanBirth.style.display = "block";
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
        } else {
            msg2.style.display = "block";
        }

    }

    if((errorName) || (errorSurname) || (errorUsername) || (errorPsw) || (errorConfirmPsw) || (errorEmail) || (errorBirth) || (errorImage)){

        event.preventDefault();

    }
}

function GetFileSize(image) {

    var fsize = image.files.item(0).size;
    var mesureImage = Math.round((fsize / 1024));

    if (mesureImage > 500){
        console.log(Math.round((fsize / 1024)));
        document.getElementById('fp').innerHTML =
            document.getElementById('fp').innerHTML + 'The image size is<br /> ' +
            '<b>' + Math.round((fsize / 1024)) + '</b> KB and it has to be less than 500KB';

        return true;

    }else{
        return false;
    }

}

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imageUser')
                .attr('src', e.target.result)
                .width(50)
                .height(50);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function focusFunction() {
        document.getElementById("message").style.display = "block";
}

function blurFunction() {
    document.getElementById("message").style.display = "none";
    document.getElementById("message2").style.display = "none";
}