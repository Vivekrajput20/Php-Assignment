function validate(str , str2 , str3) {
    var unamecheck = /^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$/;
    if(unamecheck.test(str)) {
        console.log("hi");
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(str2).innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "/php_assign/vivek/includes/ajax.php?value="+str+"&field="+str3 , true);
        xmlhttp.send();
    }
    else{
       document.getElementById(str2).innerHTML = ""; 
    }
}