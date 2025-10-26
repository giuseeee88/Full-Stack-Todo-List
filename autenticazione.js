const fields = () => {
    let username = document.getElementById("username");
    let email = document.getElementById("emailAddress");
    let password = document.getElementById("password");
    let passwordConfirmation = document.getElementById("passwordConfirmation");

    const elementi = Array.from([username, email, password, passwordConfirmation]);

    return elementi;
}

const addElements = (elementi) => {
    const popupSuccess = document.getElementById("popupSuccess")

    var formData = new FormData()
    
    elementi.map((elemento) => {
        formData.append(elemento.name, elemento.value);
    })

    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function()
    {
        if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
        {
            popupSuccess.innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.open("post", "autenticazione.php?azione=registrazione", true); 
    xmlHttp.send(formData);
}

const checkData = () => {
    const popupError = document.getElementById("popupError");
    let elementi = fields();
    elementi.map((elemento) => {
        elemento.value.trim()
        if(elemento.value === "" || elemento.value.length === 0){
            popupError.style.display = "block";
            emptyFields();
        }
    })

    password = elementi[2].value;
    passwordConfirmation = elementi[3].value;

    if(password === passwordConfirmation) addElements(elementi)

    else{
        popupError.style.display = "block";
        emptyFields();
    }
}

const emptyFields = () => {
    let elementi = fields();
    elementi.map((elemento) => elemento.value = "")
}

const logout = () => {
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function()
    {
        if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
        {
            console.log(xmlHttp.responseText)
        }
    }
    xmlHttp.open("post", "autenticazione.php?azione=logout", true); 
    xmlHttp.send();

    window.location.assign("login.html");
}