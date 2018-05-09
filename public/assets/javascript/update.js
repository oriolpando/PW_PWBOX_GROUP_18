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


        xmlhttp.onreadystatechange = function () {
            var DONE = 4; // readyState 4 means the request is done.
            var OK = 200; // status 200 is a successful return.
            if (xmlhttp.readyState === DONE) {
                if (xmlhttp.status === OK){
                    console.log("fuck11");
                document.getElementById("mail").innerHTML = mail;
                }else {
                    console.log("fuckv2")
                }
            }

        }
    }

}
