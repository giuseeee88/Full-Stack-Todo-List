let deleteOneTask = true;

document.addEventListener("keydown", e => {
    if (e.key.toLowerCase() === "a" && e.ctrlKey) {
        e.preventDefault();
        document.getElementById("aggiungi-task").click();
    }

    else if(e.key.toLowerCase() === "e" && e.ctrlKey && deleteOneTask){
        e.preventDefault();
        deleteLastTask();
    }

    else if(e.key.toLowerCase() === "q" && e.ctrlKey){
        e.preventDefault();
        document.getElementById("statistiche").click();
    }
});

const deleteLastTask = () => {
    const contenuto = document.getElementById("contenuto");
    const zonadinamica = document.getElementById("zonadinamica");
    var httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == XMLHttpRequest.DONE && httpRequest.status == 200) {
            contenuto.style.display = "none";
            zonadinamica.innerHTML = httpRequest.responseText;
        }
    };
    httpRequest.open("GET", "../backend/azioni.php?elementi=last&azione=aggiorna", true);
    httpRequest.send();
}

const deleteTask = (elementi) => {
    const contenuto = document.getElementById("contenuto");
    const zonadinamica = document.getElementById("zonadinamica");
    const elementiArray = Array.from(elementi);
    const elementiFiltrati = elementiArray.filter((elemento) => elemento.checked);
    const valoriElementi = elementiFiltrati.map((elemento) => elemento.value);

    if(elementiFiltrati.length > 0){
        var httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == XMLHttpRequest.DONE && httpRequest.status == 200) {
                contenuto.style.display = "none";
                zonadinamica.innerHTML = httpRequest.responseText;
            }
        };
        httpRequest.open("GET", "../backend/azioni.php?elementi=" + valoriElementi + "&azione=aggiorna", true);
        httpRequest.send();
    }
};

const addTask = () => {
    deleteOneTask = false;
    const contenuto = document.getElementById("contenuto")
    const zonadinamica = document.getElementById("zonadinamica")
    const tabella = document.createElement("table")
    const riga = document.createElement("tr")
    const cella1 = document.createElement("td")
    const cella2 = document.createElement("td")
    const input = document.createElement("input")
    const button1 = document.createElement("button")
    const button2 = document.createElement("button")
    const span1 = document.createElement("span")
    const span2 = document.createElement("span")
    
    const aggiungiTask = document.getElementById("aggiungi-task")

    aggiungiTask.disabled = true;

    input.type = "text"
    input.placeholder = "Inserire il nome della task"

    button1.className = button2.className = "comando"

    span1.className = span2.className = "material-symbols-outlined"
    span1.innerHTML = "check"
    span2.innerHTML = "close"

    riga.className = "elemento"

    tabella.id = "task"

    button1.appendChild(span1)
    button2.appendChild(span2)
    cella1.appendChild(input)
    cella2.appendChild(button1)
    cella2.appendChild(button2)
    riga.appendChild(cella1)
    riga.appendChild(cella2)
    tabella.appendChild(riga)
    zonadinamica.appendChild(tabella)

    button1.addEventListener("click", () => {
        var httpRequest = new XMLHttpRequest();
        const descrizione = input.value;
        const stato = 0;
        if(descrizione.length > 0){
            httpRequest.onreadystatechange = function() {
                if (httpRequest.readyState == XMLHttpRequest.DONE && httpRequest.status == 200) {
                    deleteOneTask = true;
                    aggiungiTask.disabled = false;
                    contenuto.style.display = "none";
                    zonadinamica.innerHTML = httpRequest.responseText;
                }
            }
            httpRequest.open("GET", `../backend/azioni.php?descrizione=${descrizione}&stato=${stato}&azione=aggiungi`, true);
            httpRequest.send();
        };
    })

    button2.addEventListener("click", () => {
        deleteOneTask = true;
        riga.style.display = "none"
        aggiungiTask.disabled = false;
    })
}

const editTask = (button) => {
    const contenuto = document.getElementById("contenuto");
    const zonadinamica = document.getElementById("zonadinamica");
    const numero_elemento = button.value;
    const comando_task = document.getElementById(`task-${numero_elemento}`);
    const nuova_icona = document.createElement("span");
    const button2 = document.createElement("button");
    const elementoFiltrato = document.getElementById(`descrizione-${numero_elemento}`);
    const lista = document.createElement("select");

    const buttons = Array.from(document.querySelectorAll(".elemento td button"));
    const nuove_task = Array.from(document.querySelectorAll(".elemento input"));

    buttons.map((single_button => single_button.style.display = "none"));
    nuove_task.map((single_task => single_task.style.display = "none"));

    elementoFiltrato.contentEditable = "true";
    button.style.display = "none";
    button2.className = "comando";

    nuova_icona.className = "material-symbols-outlined";
    nuova_icona.innerHTML = "check";
    button2.appendChild(nuova_icona);

    const option1 = document.createElement("option");
    const option2 = document.createElement("option");
    option1.innerHTML = "Incompleto";
    option1.value = "Incompleto";
    option2.innerHTML = "Completato";
    option2.value = "Completato";
    lista.appendChild(option1);
    lista.appendChild(option2);

    comando_task.appendChild(lista);
    comando_task.appendChild(button2);

    button2.addEventListener("click", () => {
        let nuovo_stato = lista.value === "Incompleto" ? 0 : 1;
        const nuova_descrizione = elementoFiltrato.innerHTML;
        elementoFiltrato.contentEditable = "false";

        var httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == XMLHttpRequest.DONE && httpRequest.status == 200) {
                contenuto.style.display = "none";
                zonadinamica.innerHTML = httpRequest.responseText;
            }
        }
        httpRequest.open("GET", "../backend/azioni.php?numero_elemento=" + numero_elemento + "&nuova_descrizione=" + nuova_descrizione + "&nuovo_stato=" + nuovo_stato + "&azione=modifica", true);
        httpRequest.send();
    });
};