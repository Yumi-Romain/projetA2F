function arr_delete(arr = [], val) {
    var a = [];
    for (var key in arr) {
        if (val != arr[key]) {
            a.push(arr[key]);
        }
    }
    return a;
}

class Search {

    constructor() {

        this.comp = [];
        this.clients = [];
        this.arr = {};
        this.table = {
            "1 ou plus" : ">= 1",
            "2 ou plus" : ">= 2",
            "3" : "= 3",
            "3 ou moins" : "<= 3",
            "2 ou moins" : "<= 2",
            "1 ou moins" : "<= 1",
            "0" : "= 0"
        }
        Search.load();

    }

    select() {
        var tmp = "<select>";
        for (let option in this.table) {
            if (option == "1 ou plus") {
                tmp += "<option selected>" + option + "</option>";
            } else {
                tmp += "<option>" + option + "</option>";
            }
        }
        tmp += "</select>";
        return tmp;
    }

    static load() {

        console.log("loading search")

        document.querySelectorAll('.competence').forEach(e => {

            e.addEventListener("click", function(a) {

                search.addComp(a.target.e);

            })
            e.e = e;

        });

        document.querySelectorAll('.client').forEach(e => {

            e.addEventListener("click", function(a) {

                search.addClient(a.target.e);

            })
            e.e = e;

        })

    }

    addComp(e) {

        if (!this.comp.includes(e.dataset.id)) {

            this.comp.push(e.dataset.id);

            console.log(this.comp);
                    
            document.querySelector(".divCompListS").innerHTML += "<div data-name=\"" + e.dataset.name + "\" data-id=\"" + e.dataset.id + "\" class=\"compSelected\">" + e.dataset.name + "&nbsp;&nbsp;|&nbsp;&nbsp;niveau : " + this.select() + "<div onclick=\"search.delComp('" + e.dataset.id + "')\" class=\"closeBtn\">&times;</div></div>";
            
        } else {

            console.log("allready in array");

        }

    }

    addClient(e) {

        if (!this.clients.includes(e.dataset.id)) {

            this.clients.push(e.dataset.id);
            
            console.log(this.clients);
            
            document.querySelector(".divClientList").innerHTML += "<div data-id=\"" + e.dataset.id + "\" class=\"clientS\">" + e.dataset.name + "<div onclick=\"search.delClient('" + e.dataset.id + "')\" class=\"closeBtn\">&times;</div></div>";
            document.querySelector(".divClientListS").innerHTML += "<div data-id=\"" + e.dataset.id + "\" class=\"clientSelected\">" + e.dataset.name + "<div onclick=\"search.delClient('" + e.dataset.id + "')\" class=\"closeBtn\">&times;</div></div>";
            
        } else {

            console.log("allready in array");

        }

    }

    delClient(e) {

        if (this.clients.includes(e)) {

            console.log('pass'); // debug

            this.clients = arr_delete(this.clients, e);

            console.log(this.clients);

            document.querySelector(".clientS[data-id='" + e + "']").remove();
            document.querySelector(".clientSelected[data-id='" + e + "']").remove();
            
        } else {

            console.log("not in array");
        
        }

    }

    delComp(e) {

        if (this.comp.includes(e)) {

            this.comp = arr_delete(this.comp, e);

            console.log(this.comp);

            document.querySelector(".compSelected[data-id='" + e + "']").remove();
            let t = document.querySelector(".competenceS[data-id='" + e + "']");
            if (t != null) t.remove();
            
        } else {

            console.log("not in array");
        
        }

    }

    send() {

        this.arr = {
            "competences":{
                "id_competence":[],
                "niveau":[]
            },
            "poles":{
                "id_pole":[]
            },
            "clients":{
                "id_client":[]
            },
            "disponibilites":{
                "id_disponibilite":[]
            },
            "consultant":null,
            "archive": null
        }

        var archive = document.getElementById('archive');
        var poleIndus = document.getElementById('poleIndus');
        var poleDatabase = document.getElementById('poleDatabase');
        var poleSi = document.getElementById('poleSi');

        var dispMtn = document.getElementById('dispMtn');
        var disp1M = document.getElementById('disp1M');
        var disp2M = document.getElementById('disp2M');
        var disp3M = document.getElementById('disp3M');

        if (archive != null && archive.checked ) {
            this.arr.archive = 1;
        } else {
            this.arr.archive = 0;
        }
        if (poleIndus.checked) {
            this.arr.poles.id_pole.push(1);
        }
        if (poleDatabase.checked) {
            this.arr.poles.id_pole.push(3);
        }
        if (poleSi.checked) {
            this.arr.poles.id_pole.push(2);
        }
        if (dispMtn.checked) {
            this.arr.disponibilites.id_disponibilite.push(1);
        }
        if (disp1M.checked) {
            this.arr.disponibilites.id_disponibilite.push(2)
        }
        if (disp2M.checked) {
            this.arr.disponibilites.id_disponibilite.push(3);
        }
        if (disp3M.checked) {
            this.arr.disponibilites.id_disponibilite.push(4);
        }
        for (var comp in this.comp) {
            if (!this.arr.competences.id_competence.includes(this.comp[comp])) {
                this.arr.competences.id_competence.push(this.comp[comp]);
                var a = document.querySelector(".compSelected[data-id='" + this.comp[comp] + "']>select");
                var id = a.options[a.selectedIndex].text;
                this.arr.competences.niveau.push(this.table[id]);
            }
        }

        for (var client in this.clients) {
            if (!this.arr.clients.id_client.includes(this.clients[client])) {
                this.arr.clients.id_client.push(this.clients[client]);
            }
        }

        var input = document.getElementById('inputCons').value
        if (input != "") {
            this.arr.consultant = document.getElementById('inputCons').value;
        }

        location.href = "/recherche/resultat/?filter=" + encodeURI(JSON.stringify(this.arr));

    }

    onPopupClose() {

        document.querySelectorAll('.competenceS').forEach(e => {
            e.remove();
        })

        document.querySelectorAll('.compSelected').forEach(e => {
            var a = document.querySelector(".compSelected[data-id='" + e.dataset.id + "']>select");
            var id = a.options[a.selectedIndex].text;
            console.log(id);
            document.querySelector(".divCompList").innerHTML += "<div data-id=\"" + e.dataset.id + "\" class=\"competenceS\">" + e.dataset.name + "&nbsp;&nbsp;|&nbsp;&nbsp;niveau : " + id + "<div onclick=\"search.delComp('" + e.dataset.id + "')\" class=\"closeBtn\">&times;</div></div>";
        })

    }

    static test() {

        var arr = {
            "competences":{
                "id_competence":[192,193,194],
                "niveau":[2,1,3]
            },
            "poles":{
                "id_pole":[1,2]
            },
            "clients":{
                "id_client":[1,2]
            },
            "disponibilites":{
                "id_disponibilite":[1]
            },
            "consultant":{
                "nom":"corfa"
            },
            "archive": 0
        }

        return encodeURI(JSON.stringify(arr));

    }

}


// note l'ajout d'une comp reset le select des autres

class S {

    constructor() {

        var input = document.getElementById('input');
        var inputFilter = document.getElementById('inputFilter');
        var textInput = document.getElementById('textInput');

        this.DOMelem = {
            input,
            inputFilter,
            textInput
        };

        this.DOMelem.input.Object = this;
        this.DOMelem.inputFilter.Object = this;
        this.DOMelem.textInput.Object = this;
        this.idIncrement = 0;
        this.table = {
            "1 ou plus" : ">= 1",
            "2 ou plus" : ">= 2",
            "3" : "= 3",
            "3 ou moins" : "<= 3",
            "2 ou moins" : "<= 2",
            "1 ou moins" : "<= 1",
            "0" : "= 0"
        };

        this.DOMelem.input.onclick = function(event) {

            if (typeof event.target.Object !== "undefined") {
                event.target.Object.DOMelem.textInput.focus();
            }

        }

        this.DOMelem.textInput.onkeydown = function(event) {            
            if (event.keyCode == 13 || event.keyCode == 188) {
                return false;
            }
        };

        this.DOMelem.textInput.addEventListener("keyup", function(event) {

            event.target.Object.keyup(event);

        });

    }

    onPopupOpen() {

        document.querySelector(".main-wrapper").style.width = "70vw";

    }

    onPopupClose() {

        document.querySelector(".main-wrapper").style.width = "100vw";

    }

    select() {
        var tmp = "<select>";
        for (let option in this.table) {
            if (option == "1 ou plus") {
                tmp += "<option selected>" + option + "</option>";
            } else {
                tmp += "<option>" + option + "</option>";
            }
        }
        tmp += "</select>";
        return tmp;
    }

    genId() {

        this.idIncrement += 1;
        return "genId" + this.idIncrement;

    }

    keyup(event) {

        console.log(event);
        if (event.keyCode == 188) {

            var obj = event.target.Object;

            var text = obj.DOMelem.textInput.innerText;
            obj.DOMelem.textInput.innerText = "";

            var id = obj.genId();

            obj.addFilter({
                "text" : text,
                "dataset" : {
                    "type" : "consultant",
                    "id" : id,
                    "name" : text
                },
                "onclick" : "s.delFilter('consultant', '" + id + "')"
            })

        }

    }

    addFilter(arr) {

        var a = document.querySelector("[data-id='" + arr.dataset.id + "'][data-type='" + arr.dataset.type + "']")

        if (a != null) return;

        var div = document.createElement("div");
        div.innerText = arr.text;
        div.dataset.id = arr.dataset.id;
        div.dataset.type = arr.dataset.type;
        if (arr.dataset.type == "competence") {
            div.innerHTML += "&nbsp;&nbsp;|&nbsp;&nbsp;niveau : " + this.select();
        }
        div.dataset.name = arr.dataset.name;
        if (typeof arr.class !== 'undefined') {
            div.classList.add(arr.class)
        }
        if (typeof arr.onclick !== 'undefined') {
            div.innerHTML += '<div onclick="' + arr.onclick + '" class="closeBtn">&times;</div>';
        }
        
        this.DOMelem.inputFilter.insertBefore(div, this.DOMelem.textInput);

    }

    delFilter(type, id) {

        var a = document.querySelector("[data-id='" + id + "'][data-type='" + type + "']")

        if (a != null) a.remove();

    }

    load() {

        document.querySelectorAll('.competence').forEach(e => {

            e.addEventListener("click", function(a) {

                s.addFilter({
                    "text" : e.dataset.name,
                    "dataset" : {
                        "type" : "competence",
                        "id" : e.dataset.id,
                        "name" : e.dataset.name
                    },
                    "onclick" : "s.delFilter('competence', '" + e.dataset.id + "')"
                });

            })
            e.e = e;

        });

        document.querySelectorAll('.client').forEach(e => {

            e.addEventListener("click", function(a) {

                s.addFilter({
                    "text" : e.dataset.name,
                    "dataset" : {
                        "type" : "client",
                        "id" : e.dataset.id,
                        "name" : e.dataset.name
                    },
                    "onclick" : "s.delFilter('client', '" + e.dataset.id + "')"
                });

            })
            e.e = e;

        })

    }

    send() {

        this.arr = {
            "competences":{
                "id_competence":[],
                "niveau":[]
            },
            "poles":{
                "id_pole":[]
            },
            "clients":{
                "id_client":[]
            },
            "disponibilites":{
                "id_disponibilite":[]
            },
            "consultant":[],
            "archive": null
        }

        var archive = document.getElementById('archive');
        var poleIndus = document.getElementById('poleIndus');
        var poleDatabase = document.getElementById('poleDatabase');
        var poleSi = document.getElementById('poleSi');

        var dispMtn = document.getElementById('dispMtn');
        var disp1M = document.getElementById('disp1M');
        var disp2M = document.getElementById('disp2M');
        var disp3M = document.getElementById('disp3M');

        if (archive != null && archive.checked ) {
            this.arr.archive = 1;
        } else {
            this.arr.archive = 0;
        }
        if (poleIndus.checked) {
            this.arr.poles.id_pole.push(1);
        }
        if (poleDatabase.checked) {
            this.arr.poles.id_pole.push(3);
        }
        if (poleSi.checked) {
            this.arr.poles.id_pole.push(2);
        }
        if (dispMtn.checked) {
            this.arr.disponibilites.id_disponibilite.push(1);
        }
        if (disp1M.checked) {
            this.arr.disponibilites.id_disponibilite.push(2)
        }
        if (disp2M.checked) {
            this.arr.disponibilites.id_disponibilite.push(3);
        }
        if (disp3M.checked) {
            this.arr.disponibilites.id_disponibilite.push(4);
        }

        document.querySelectorAll("[data-type='competence']").forEach(e => {

            this.arr.competences.id_competence.push(e.dataset.id);
            var a = document.querySelector("[data-type='competence'][data-id='" + e.dataset.id + "']>select");
            var id = a.options[a.selectedIndex].text;
            this.arr.competences.niveau.push(this.table[id]);

        });

        document.querySelectorAll("[data-type='client'").forEach(e => {

            this.arr.clients.id_client.push(e.dataset.id);

        });

        document.querySelectorAll("[data-type='consultant']").forEach(e => {

            this.arr.consultant.push(e.dataset.name);

        });

        location.href = "/recherche/resultat/?filter=" + encodeURI(JSON.stringify(this.arr));

    }

}