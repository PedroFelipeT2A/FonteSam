function clique(modulo, id, link, tipo) {
    var link_decode = atob(link);
    var token_send = btoa(modulo + "|" + id + "|" + link);
    dataString = "token=" + token_send;
    $.ajax({
        type: "POST",
        url: "https://pa-def.srvsite.com/site/cliques/6497",
        data: dataString,
        success: function (data) {
            if (data.search("sucesso")) {
                window.open(link_decode, "_blank")
            }
        }
    })
}
const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
if (vw > "820") {
    $(".sg-pubmeioroll").slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3500,
        vertical: false,
        verticalSwiping: false
    })
} else {
    $(".sg-pubmeioroll").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3500,
        vertical: false,
        verticalSwiping: false
    })
}