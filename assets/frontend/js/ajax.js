if(!!document.getElementsByClassName('js-seachresults')[0]) {

    var getUrlParameter = function getUrlParameter(sParam) {
        let sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };

    var filter = document.getElementById('filter');

    function checkWidth() {
        var windowsize = $(window).width();
        if (windowsize <= 991) {
            $(filter).detach().appendTo('#filterBody');
        }
        else {
            $(filter).detach().appendTo('#filterParent');
        }
    }

    if ( window.location.hash === '#filter' ) {
        $('#filterModal').modal('show');
        history.replaceState({}, document.title, window.location.href.split('#')[0]);
    }
    // Execute on load
    checkWidth();
    // Bind event listener
    $(window).resize(checkWidth);
    
    $('.js-filtersubmit').click(function(event) {
        event.preventDefault();

        ajaxSearch($('#locaties').val(), $('#functies :selected').val());
    });

    $( document ).ready(function() {
        let location = getUrlParameter('vestiging');
        let functions = getUrlParameter('functie');

        if (location.length >0 || functions.length > 0) {
            ajaxSearch(location, functions);
        }

    });
}

function ajaxSearch(locationValue, functionProfileValue) {
    $.ajax({
        type:"GET",
        cache:false,
        data: { location: locationValue, function_profile: functionProfileValue },
        url:"/api/jobsearch",
        success: function (result) {
            $( '.js-seachresults' ).html( result.job_html );
            $('.js-jobsnum').html(result.count + ' vacature' + ((result.count !== 1) ? 's' : '') + ' gevonden');
            if ($('#filterModal').hasClass('show')) {
                $('#filterModal').modal('hide');
            }
        }
    });
}
if(!!document.getElementsByClassName('js-findsubmit')[0]) {

    $('.js-findsubmit').click(function(event) {
        event.preventDefault();

        window.location.href = '/vacatures/zoeken?functie=' + encodeURIComponent($('#functies :selected').val())
            + '&vestiging=' + encodeURIComponent($('#locaties').val());
    });

}

