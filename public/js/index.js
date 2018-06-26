jQuery.fn.exists = function () {
    return this.length > 0;
};

$(function () {


    if ($(".rss").exists()) {

        /**
         * Affichage des flux rss
         */
        setInterval(function () {
            $(".rss p:visible").fadeOut(1500, function () {
                var next = $(".rss p")[Math.floor(Math.random() * $(".rss p").length)];
                $(next).fadeIn(1500);
            });
        }, 15000);
        var next = $(".rss p")[Math.floor(Math.random() * $(".rss p").length)];
        $(next).show();
    }


    if ($(".horloge").exists()) {

        /**
         * Affichage de l'heure
         */
        setInterval(function () {
            timeDislay()
        }, 5000);

        function timeDislay() {
            var d = new Date();
            $('#timer').html(d.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}));
        }

        timeDislay();
    }
});

var toto = {
    "time": {
        "updated": "Jun 26, 2018 13:07:00 UTC",
        "updatedISO": "2018-06-26T13:07:00+00:00",
        "updateduk": "Jun 26, 2018 at 14:07 BST"
    },
    "disclaimer": "This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org",
    "bpi": {
        "USD": {
            "code": "USD",
            "rate": "6,218.9063",
            "description": "United States Dollar",
            "rate_float": 6218.9063
        },
        "EUR":
            {"code": "EUR", "rate": "5,328.4584", "description": "Euro", "rate_float": 5328.4584}
    }
};
