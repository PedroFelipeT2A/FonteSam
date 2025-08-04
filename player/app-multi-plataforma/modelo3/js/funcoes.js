const URL_STREAMING = "https://stm1.livehd.com.br:7002/;";
const playerStream = new Audio(URL_STREAMING);
var isPlaying = false;
var mouseclicked = false;
var btnPlayer;
playerStream.onloadeddata = async (event) => {
    if (isPlaying) {
        playerStream.play().then(async () => {
            await atualizaButton("stop")
        }).catch(async (e) => {
            await atualizaButton("start")
        })
    }
};
playerStream.onerror = async (event) => {
    setTimeout(() => {
        playerStream.load()
    }, 1000)
};
playerStream.onended = async (event) => {
    const timer = setInterval(() => {
        playerStream.load();
        if (!playerStream.ended) {
            clearInterval(timer)
        }
    }, 1000)
};
$(document).ready(function () {
    btnPlayer = document.getElementById("playerButton");
    btnPlayer.addEventListener("click", togglePlay);
    window.addEventListener("click", (event) => {
        if (!mouseclicked) {
            mouseclicked = true;
            if (!(event.target.className == "fas fa-play-circle" || event.target.className == "fas fa-play" || event.target.id == "playerButton" || event.target.id == "3icone1" || event.target.id == "icone1")) {
                btnPlayer.click()
            }
        }
    })
});
$(document).ready(function () {
    $("body").bind("cut copy paste", function (e) {
        alert("Acesso Negado!");
        e.preventDefault()
    });
    $("body").on("contextmenu", function (e) {
        alert("Acesso Negado!");
        return false
    })
});

function carregaTV(iddiv, acao, id) {
    $("#" + iddiv).html("<div class='sg-modalfundo'><div id='modalResultado'></div><button class='bt-fechar'><i class='fas fa-times' onclick='fecharModal(\"" + iddiv + "\");'></i></button></div>");
    setTimeout(function () {
        $(".sg-modalfundo").toggle(150)
    }, 300);
    $("#modalResultado").load("https://pa-def.srvsite.com/site/reload/6497/" + acao + "/" + id, function () {})
}

function fecharModal(iddiv) {
    $(".sg-modalfundo").toggle(150);
    setTimeout(function () {
        $("#" + iddiv).html("")
    }, 300)
}

function setVolume() {
    if (playerStream) {
        playerStream.volume = document.getElementById("volume").value
    }
}

function setMute(e) {
    if (playerStream) {
        playerStream.muted = !playerStream.muted;
        e.target.className = (playerStream.muted) ? "fa fa-volume-mute cortexto" : "fa fa-volume-up cortexto"
    }
}
async function stop() {
    if (playerStream) {
        playerStream.pause();
        playerStream.src = playerStream.src;
        isPlaying = false
    }
    await atualizaButton("start")
}
async function play() {
    await atualizaButton("load");
    isPlaying = true;
    if (playerStream && playerStream.readyState != 0) {
        playerStream.load();
        playerStream.play().then(async () => {
            await atualizaButton("stop")
        }).catch(async (e) => {
            await atualizaButton("start")
        })
    }
}

function togglePlay() {
    if (isPlaying) {
        stop()
    } else {
        play()
    }
};
async function atualizaButton(status) {
    switch (status) {
    case "load":
        document.getElementById("playerButton").className = "sg-carrega-load";
        break;
    case "start":
        document.getElementById("playerButton").className = "fas fa-play play";
        break;
    case "stop":
        document.getElementById("playerButton").className = "fas fa-pause play";
        break;
    default:
        document.getElementById("playerButton").className = "fas fa-play play";
        break
    }
}

function carregaNoAr(id) {
    dadosNoAr(id);
    setInterval(function () {
        dadosNoAr(id)
    }, 50000)
}

function carregaProgramas(id) {
    dadosProgramas(id);
    setInterval(function () {
        dadosProgramas(id)
    }, 60000)
}

function dadosNoAr(id) {
    var url = "https://pa-def.srvsite.com/site/noar/6497/" + id;
    $.getJSON(url, function (data) {
        $("#imgNoAr").css("background-image", 'url("' + data[0].foto + '")');
        if (data[0].programa) {
            $("#tituloNoAr").html("NO AR");
            if (data[0].locutor) {
                $("#progNoAr").html(data[0].programa);
                $("#locutorNoAr").html(data[0].locutor);
                $("#horaNoAr").html("at&eacute; &agrave;s " + data[0].horafim)
            } else {
                $("#progNoAr").html(data[0].programa);
                $("#locutorNoAr").html(data[0].locutor);
                $("#horaNoAr").html(data[0].horafim);
                $("#horaNoAr").html("at&eacute; &agrave;s " + data[0].horafim)
            }
        } else {
            $("#progNoAr").html("<h4>...</h4><h2>...</h2>")
        }
    })
}

function dadosProgramas(id) {
    var url = "https://pa-def.srvsite.com/site/noar/6497/" + id;
    var divnome = document.getElementById("OutrosProgramas");
    $.getJSON(url, function (data) {
        $("#tituloQueVem").html("O que vem por a&iacute;");
        divnome.innerHTML = "";
        for (var i = 0; i < data.length; i++) {
            divnome.innerHTML += `<div class='sg-col1'><div class='sg-programas'><div class='sg-programas1'style='background-image:url(${data[i].foto})'></div><div class='sg-programas2'><h3>Come&ccedil;a&nbsp;&agrave;s ${data[i].horainicio}h</h3><h4>${data[i].programa}</h4><h2>${data[i].locutor}</h2></div></div></div>`
        }
    })
}
carregaNoAr("b4w203a496w2c4o284s2o2y25496i594g4y5f5");
carregaProgramas("b4w203a496w2c4o284s2o2y25496x5k4g446n5e4h4x576r2");