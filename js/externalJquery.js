    $(document).ready(function() {

        $('#search').autocomplete({
            source: function(request, response) {
                jQuery.get('search.php', {
                    query: request.term
                }, function(data) {
                    data = JSON.parse(data);
                    response(data);
                });
            },
            delay: 100
        });
    });

    $(document).ready(function() {
        $('#searchIns').autocomplete({
            source: function(request, response) {
                $.get('search.php', {
                        query: request.term,
                        corso: 'on'
                    },
                    function(data) {
                        data = JSON.parse(data);
                        response(data);
                    });
            },
            delay: 100
        });
    });


$(document).ready(function() {
        $("#anagraficaButton a").click(function() {
            $("#anagrafica").toggle();
            $("#post").hide();
            $("#punti").hide();
        });
    });
    $(document).ready(function() {
        $("#puntiButton a").click(function() {
            $("#punti").toggle();
            $("#anagrafica").hide();
            $("#post").hide();
        });
    });
    $(document).ready(function() {
        $("#postButton a").click(function() {
            $("#post").toggle();
            $("#anagrafica").hide();
            $("#punti").hide();
        });
    });
    $(document).ready(function() {
        $("#modifica a").click(function() {
            $("#modify").toggle();
        });
    });
    $(document).ready(function() {
        $(".puntiSingoli a").click(function() {
            $("#showSingle").toggle();
        });
    });

    $(document).ready(function() {
        $('#tags').tokenfield({
            autocomplete: {
                source: function(request, response) {
                    jQuery.get('search.php', {
                        query: request.term
                    }, function(data) {
                        data = JSON.parse(data);
                        response(data);
                    });
                },
                delay: 100
            }
        });
    });

    $(document).ready(function() {
        $('#tagsThread').tokenfield({
            autocomplete: {
                source: function(request, response) {
                    jQuery.get('search.php', {
                        query: request.term
                    }, function(data) {
                        data = JSON.parse(data);
                        response(data);
                    });
                },
                delay: 100
            }
        });
    });