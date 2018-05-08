function updateDb() {
    var mail = document.getElementById("mailUp").value;
    var psw = document.getElementById("passUp").value;
    var confPsw = document.getElementById("passConfUp").value;

    if (psw != confPsw){
        console.log("fuck2");
    }else{
        console.log(mail + psw + confPsw);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET","/updateUser?email="+mail,true);
        xmlhttp.send();

        console.log(xmlhttp.status);
        if(xmlhttp.status != 500){
            document.getElementById("mailUp").innerHTML = mail;
        }else{
            console.log("fuck2");
        }
    }
}
