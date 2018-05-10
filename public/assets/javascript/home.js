


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
    var birth = document.getElementById("psw");
    var image = document.getElementById("image");

    var errorName = false;
    var errorUsername = false;
    var errorSurname = false;
    var errorEmail = false;
    var errorPsw = false;
    var errorBirth = false;
    var errorImage = false;

    //validacio nom

    if (name == null){
        errorName = true;
    }

    if((username == null) || (length(username) > 12) || (length(username) < 6) || (username.value.match("/^[0-9a-zA-Z]+$/")) ){
        errorUsername = true;
    }


}

function readURL(input) {
    var login = document.getElementById("imageUser");
    login.style.display = "block";
    var login = document.getElementById("buttonLogin");
    login.style.display = "none";
    var login = document.getElementById("buttonRegister");
    login.style.display = "none";
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


