var toto = 8;
function amountscrolled() {
    const winheight = $(window).height()
    const docheight = $(document).height()
    const scrollTop = $(window).scrollTop() + $(window).height();
    const trackLength = scrollTop / docheight * 100
    return Math.floor(trackLength)
}

$(document).ajaxStart(() => {
    $(".loader").show();
});

// masquer le loader lorsque le code AJAX est terminé
$(document).ajaxStop(() => {
    $(".loader").hide();
});
// if (!inRequest) {
// console.log(inRequest)

let totalTweets;
$.get("totaltweet.php", (data) => {
    totalTweets = parseInt(data);
})
let inRequest = false;
$(document).scroll(() => {

    if (amountscrolled() >= 80 && inRequest == false) {
        console.log("coucou2")
            inRequest = true
            // setTimeout(() => {
                console.log("COUCOU")
            //     inRequest = false
            // }, "4000");
            toto = toto + 8;
            if (toto <= totalTweets) {
                $.ajax({
                    type: "GET",
                    url: "/tweets.php?page=" + toto,
                    success: (result) => {
                        $(".boitetweet").html(result);
                        inRequest = false
                    }
                });
            }
    }
});
// })

// }