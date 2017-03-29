jQuery(document).ready(function($){
    $( "#edit-klijent" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/klijenti/autocomplete",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });
});