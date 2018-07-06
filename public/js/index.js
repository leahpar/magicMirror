jQuery.fn.exists = function () {
    return this.length > 0;
};

$(function () {


    if ($(".rss").exists()) {

        /**
         * Affichage des flux rss
         */
        const timing = 1500;
        const delay  = 5000;
        var next;
        setInterval(function () {
            $(".rss p").fadeOut(timing);

            setTimeout(function () {
                next = $(".rss p")[Math.floor(Math.random() * $(".rss p").length)];
                $(next).fadeIn(timing);
            }, timing);
        }, delay);
        next = $(".rss p")[Math.floor(Math.random() * $(".rss p").length)];
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
