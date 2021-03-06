class S {

    constructor() {

        this.DOMelem = {
            input : document.getElementById('input'),
            inputFilter : document.getElementById('inputFilter'),
            textInput : {
                input : document.querySelector('#textInput input'),
                container : document.getElementById('textInput')
            },
            sugest : document.querySelector(".sugest")
        };

        this.DOMelem.input.Object = this;
        this.DOMelem.inputFilter.Object = this;
        this.DOMelem.textInput.input.Object = this;
        this.idIncrement = 0;
        this.table = {
            "égal à 0" : "=0",
            "inf./égal à 1" : "m=1",
            "sup./égal à 1" : "p=1",
            "inf./égal à 2" : "m=2",
            "sup./égal à 2" : "p=2",
            "égal à 3" : "=3"
        };
        this.sugest = {
            "competence" : {},
            "client" : {}
        }

        this.DOMelem.input.onclick = function(event) {

            if (typeof event.target.Object !== "undefined") {
                event.target.Object.DOMelem.textInput.input.focus();
            }

        }

        this.DOMelem.textInput.input.onkeydown = function(event) {            
            if (event.keyCode == 13 || event.keyCode == 188) {
                return false;
            }
        };

        this.DOMelem.textInput.input.addEventListener("keyup", function(event) {

            event.target.Object.keyup(event);

        });

        this.btnSlide = {
            slider : document.querySelector(".btnSlideContainer>div:nth-child(3)"),
            consultant : document.querySelector(".btnSlideContainer>div:nth-child(1)"),
            candidat : document.querySelector(".btnSlideContainer>div:nth-child(2)"),
            container :  document.querySelector(".btnSlideContainer"),
            status : false
        }


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
            if (option == "sup./égal à 1") {
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

        if (event.keyCode == 188 || event.keyCode == 13) {

            var text = this.DOMelem.textInput.input.value.trim();

            if (text == "") return;

            this.DOMelem.textInput.input.value = "";

            var id = this.genId();

            this.addFilter({
                "text" : text,
                "dataset" : {
                    "type" : "consultant",
                    "id" : id,
                    "name" : text
                },
                "onclick" : "s.delFilter('consultant', '" + id + "')"
            })

        } else {

            var text = event.target.Object.DOMelem.textInput.input.value.trim();

            if (text == "") {
                var compContainer = document.querySelector(".sugestedComp");
                var clientContainer = document.querySelector(".sugestedClient")

                compContainer.innerHTML = "";
                clientContainer.innerHTML = "";

                this.DOMelem.sugest.style.display = "none";

                return;

            }

            var antiFreez = {
                "competence" : 0,
                "client" : 0
            };

            var compContainer = document.querySelector(".sugestedComp");
            var clientContainer = document.querySelector(".sugestedClient")

            compContainer.innerHTML = "";
            clientContainer.innerHTML = "";

            for (var e in this.sugest.competence){

                if (e.toLowerCase().indexOf(text.toLowerCase()) > -1 && antiFreez.competence < 16) {

                    compContainer.innerHTML += "<div onclick='s.addFComp(this.dataset.id, this.dataset.name)' data-name='" + e + "' class='sugestedItem' data-id='" + this.sugest.competence[e] + "'>" + e + "</div>";
                    antiFreez.competence++;

                }

            };

            for (var e in this.sugest.client){

                if (e.toLowerCase().indexOf(text.toLowerCase()) > -1) {

                    clientContainer.innerHTML += "<div onclick='s.addFClient(this.dataset.id, this.dataset.name)' data-name='" + e + "' class='sugestedItem' data-id='" + this.sugest.client[e] + "'>" + e + "</div>";
                    antiFreez.client++;                    

                }

            };

            if (antiFreez.client > 0 || antiFreez.competence > 0) {
                this.DOMelem.sugest.style.display = "grid";                
            } else {
                this.DOMelem.sugest.style.display = "none";                
            }

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
        
        this.DOMelem.inputFilter.insertBefore(div, this.DOMelem.textInput.container);

        this.DOMelem.textInput.input.value = "";

    }

    delFilter(type, id) {

        var a = document.querySelector("[data-id='" + id + "'][data-type='" + type + "']")

        if (a != null) a.remove();

    }

    addFComp(id, name) {

        s.addFilter({
            "text" : name,
            "dataset" : {
                "type" : "competence",
                "id" : id,
                "name" : name
            },
            "onclick" : "s.delFilter('competence', '" + id + "')"
        });

    }

    addFClient(id, name) {

        s.addFilter({
            "text" : name,
            "dataset" : {
                "type" : "client",
                "id" : id,
                "name" : name
            },
            "onclick" : "s.delFilter('client', '" + id + "')"
        });

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

        document.querySelectorAll(".competence").forEach(e => {

            this.sugest.competence[e.dataset.name] = e.dataset.id;

        });

        document.querySelectorAll(".client").forEach(e => {

            this.sugest.client[e.dataset.name] = e.dataset.id;

        });
        
        this.show_consultant();

    }

    show_consultant() {

        if (this.btnSlide.slider != null) {
            this.btnSlide.slider.style.left = "0";
            this.btnSlide.consultant.style.color = "var(--auto-color)";
            this.btnSlide.candidat.style.color = "rgb(161, 161, 161)";
            this.btnSlide.container.style.borderColor = "var(--main-color)";
            this.btnSlide.slider.style.backgroundColor = "var(--main-color)";
            this.btnSlide.status = false;
        }

        document.querySelectorAll(".consultantOnly").forEach(e => {

            e.style.display = "inline-block";

        });

        document.querySelectorAll(".candidatOnly").forEach(e => {

            e.style.display = "none";

        })

    }

    show_candidat() {

        this.btnSlide.slider.style.left = "calc(50% - 4px)";
        this.btnSlide.consultant.style.color = "rgb(161, 161, 161)";
        this.btnSlide.candidat.style.color = "rgb(255, 255, 255)";
        this.btnSlide.container.style.borderColor = "var(--color-df)";
        this.btnSlide.slider.style.backgroundColor = "var(--color-df)";
        this.btnSlide.status = true;

        document.querySelectorAll(".consultantOnly").forEach(e => {

            e.style.display = "none";

        });

        document.querySelectorAll(".candidatOnly").forEach(e => {

            e.style.display = "inline-block";

        })

    }

    send() {

        s.keyup({keyCode: 13});

        this.arr = {
            "candidats": this.btnSlide.status,
            "etape": [],
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
            "consultant":[],
            "archive": null
        }

        var archive = document.getElementById('archive');
        var poleIndus = document.getElementById('poleIndus');
        var poleDatabase = document.getElementById('poleDatabase');
        var poleSi = document.getElementById('poleSi');

        var av1 = document.getElementById('av1');
        var av2 = document.getElementById('av2');
        var av3 = document.getElementById('av3');
        var av4 = document.getElementById('av4');

        if (archive != null && archive.checked ) {
            this.arr.archive = 1;
        } else {
            this.arr.archive = 0;
        }
        if (poleIndus.checked) {
            this.arr.poles.id_pole.push(2);
        }
        if (poleDatabase.checked) {
            this.arr.poles.id_pole.push(3);
        }
        if (poleSi.checked) {
            this.arr.poles.id_pole.push(1);
        }
        if (av1.checked) {
            this.arr.etape.push(1);
        }
        if (av2.checked) {
            this.arr.etape.push(2);
        }
        if (av3.checked) {
            this.arr.etape.push(3);
        }
        if (av4.checked) {
            this.arr.etape.push(4);
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
