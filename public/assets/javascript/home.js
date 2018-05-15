

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

function controlRegister(event) {
   /* var login = document.getElementById("login");
    login.style.display = "block";
    var register = document.getElementById("register");
    register.style.display = "none";*/
    //comprovacions registre

    var name = document.getElementById("name").value;
    var username = document.getElementById("username").value;
    var surname = document.getElementById("surname").value;
    var email = document.getElementById("email").value;
    var psw = document.getElementById("psw").value;
    var confirmPsw = document.getElementById("confirmPsw").value;
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

    //ERRORS REGISTRE



    if (name == null){
        errorName = true;
        var spanName = document.getElementById("spanName");
        spanName.style.display = "block";

    }

    if((username == null) || (username.length > 20) || (username.match("/^[0-9a-zA-Z]+$/")) ){
        errorUsername = true;
        var spanUsername = document.getElementById("spanUsername");
        spanUsername.style.display = "block";
    }

    if(surname == null){
        errorSurname = true;
        var spanSurname = document.getElementById("spanSurname");
        spanSurname.style.display = "block";
    }

    if (!validateEmail(email)){
        errorEmail = true;
       // var spanEmail = document.getElementById("spanEmail");
       // spanEmail.style.display = block;
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
    var spanBirth = document.getElementById("spanBirth");
    if(!pattern.test(birth)){
        errorBirth = true;
        spanBirth.style.display = "block";
    }

    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;

    if((psw.length < 6) || (psw.length > 12) || (!(psw.value.match(numbers))) || (!psw.value.match(upperCaseLetters))) {
        errorPsw = true;
        var spanPsw = document.getElementById("spanPsw");
        spanPsw.style.display = "block";
    }else{
        if(psw != confirmPsw){
            errorConfirmPsw = true;
            var spanCpsw = document.getElementById("spanCpsw");
            spanCpsw.style.display = "block";
        }else{
            var msg2 = document.getElementById("message2");
            msg2.style.display = "block";
        }
    }

    //event.preventDefault();
    var path = "assets/resources/perfils/";
    //event.preventDefault();
    if((errorName) || (errorSurname) || (errorUsername) || (errorPsw) || (errorConfirmPsw) || (errorEmail) || (errorBirth) || (errorImage)){
       // event.preventDefault();

       //dona error
        console.log("buu");
        console.log(errorName);
        console.log(errorSurname);
        console.log(errorUsername);
        console.log(errorPsw);
        console.log(errorConfirmPsw);
        console.log(confirmPsw);
        console.log(psw);
        console.log(errorEmail);
        console.log(errorEmail);
        console.log(errorBirth);
        console.log(errorImage);


        //return false;
    }else{
       console.log("hey");
        var register = document.getElementById("register");
        register.style.display = "none";
         var login = document.getElementById("login");
         login.style.display = "block";

        //return true;
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
    document.getElementById("message").style.display = "none"
    document.getElementById("message2").style.display = "none";
}