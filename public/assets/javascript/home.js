


function showLogin() {
    console.log("fuck2");
    var login = document.getElementById("login");
    console.log(login);
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
}