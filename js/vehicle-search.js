jQuery( document ).ready(function() {

    //ajax call for the
    jQuery( ".btn-manufacturer" ).click( function () {

        jQuery( "#manufacturer_code" ).val( jQuery( this ).attr( "data-manufacturer_code" ) );
        jQuery( "#action" ).val( "manufacturer_year_list" );
        hide_step( "step-1" );
        jQuery.when( ajax_get_next_step_data() ).done( function (result) {
            show_step( "step-2" );
            render_html( "years-list", result );
        });
    });
    jQuery( document.body ).on( "click", ".btn-year", function () {

        jQuery( "#year" ).val( jQuery( this ).attr( "data-year" ) );
        jQuery( "#action" ).val( "year_model_list" );
        hide_step( "step-2" );
        jQuery.when( ajax_get_next_step_data() ).done( function (result) {
            show_step( "step-3" );
            render_html( "models-list", result );
        });
    });
    jQuery( document.body ).on( "click", ".btn-model", function () {

        jQuery( "#model" ).val( jQuery( this ).attr( "data-model" ) );
        jQuery( "#action" ).val( "parts_list" );
        hide_step( "step-3" );
        jQuery.when( ajax_get_next_step_data() ).done( function (result) {
            show_step( "step-4" );
            render_html( "parts-list", result );
        });
    });
});

function ajax_get_next_step_data() {
    if ( action == "" ){
        return false;
    }
    var postData = jQuery( "#car-data" ).serialize();
    var response = "";
    return jQuery.ajax({
        type : 'POST',
        url : gm_ajaxurl,
        data : postData,
        beforeSend : function (){
            jQuery( "#overlay" ).css( "display", "flex" );
        },
        success : function (returnData) {
            jQuery( "#overlay" ).css( "display", "none" );
            return  returnData;
        },
        error : function (xhr, textStatus, errorThrown) {
            jQuery( "#overlay" ).css( "display", "none" );
            return "";
        }
    });
}

function show_step( step_id ) {
    jQuery( "#"+step_id ).show();
}

function hide_step( step_id ) {
    jQuery( "#"+step_id ).hide();
}

function render_html( divID, html ) {
    jQuery( "#"+divID ).html( html );
}