

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

}
function showRegister() {
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

function controlLogin() {
    var login = document.getElementById("login");
    login.style.display = "block";
    var register = document.getElementById("register");
    register.style.display = "none";
}

function controlRegister() {

    var login = document.getElementById("login");
    login.style.display = "block";
    var register = document.getElementById("register");
    register.style.display = "none";

    //comprovacions registre

    var name = document.getElementById("name");
    var username = document.getElementById("username");
    var surname = document.getElementById("surname");
    var email = document.getElementById("email");
    var psw = document.getElementById("psw");
    var confirmPsw = document.getElementById("confirmPsw");
    var birth = document.getElementById("birth");
    var image = document.getElementById("image");

    var errorName = false;
    var errorUsername = false;
    var errorSurname = false;
    var errorEmail = false;
    var errorPsw = false;
    var errorConfirmPsw = false;
    var errorBirth = false;
    var errorImage = false;

    psw.onfocus = function() {
        document.getElementById("message").style.display = "block";
    }

    if (name == null){
        errorName = true;
    }

    if((username == null) || (length(username) > 12) || (username.value.match("/^[0-9a-zA-Z]+$/")) ){
        errorUsername = true;
    }

    if (!validateEmail(email)){
        errorEmail = true;
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
    if(!pattern.test(birth)){
        errorBirth = true;
    }

    if(psw != confirmPsw){
        errorConfirmPsw = true;
    }else{
        var msg2 = document.getElementById("message2");
        msg2.style.display = "block";
    }
}

function readURL(input) {
    var login = document.getElementById("imageUser");
    login.style.display = "block";
    var buttonL = document.getElementById("buttonLogin");
    buttonL.style.display = "none";
    var buttonR = document.getElementById("buttonRegister");
    buttonR.style.display = "none";
    var buttonLout = document.getElementById("buttonLogout");
    buttonLout.style.display = "none";

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
    document.getElementById("message").style.display = "none"
    document.getElementById("message2").style.display = "none";
}