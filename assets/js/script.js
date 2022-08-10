// jQuery(document).ready(function($) {
//     // Add your code here
//     $('#reservationtime').daterangepicker({
//         timePicker: true,
//         timePickerIncrement: 30,
//         locale: {
//             format: 'MM/DD/YYYY hh:mm A'
//         }
//     });
// });

//// BOOK ROOM ////
function unb_book_room_submit(button) {

    var fd = new FormData();
    
    nonce = $(button).attr("data-nonce");
    room_id = $(button).attr("data-room-id");

    fd.append('nonce', 		nonce);
    fd.append('action', 	"unb_book_room");
    fd.append('room_id',	room_id);

    ajax_submit(fd, unb_book_room_callback, "#unb_book_room_suc", "#unb_book_room_alert");
}

function unb_book_room_callback(data) {
    var jdata = JSON.parse(data);
    var success = jdata.success;
    var url = jdata.url;
    if (success == 1) {
        window.location.replace(url);
    }
}

function ajax_submit(data, callback, suc_div, alert_div)
{
    $.ajax({
        url: admin_url_object.ajaxurl,
        method:'post',
        data:data,
        contentType:false,
        processData:false,
        success: function ( response ) { callback( response ); submit_callback( response, suc_div, alert_div ); },
    });
}

function submit_callback(data, suc_div, alert_div) {

    var jdata = JSON.parse(data);

    var success = jdata.success;
    var mess = jdata.message;

    if ( success == 1) {
        $(suc_div).show();
        $(suc_div).html('<div class="fs-6">' + mess + '</div>');
        $(suc_div).delay(2500).fadeOut(500, function() { $(this).hide(); });
    }

    if ( success == 2) {
        //alert(mess);
        $(alert_div).show();
        $(alert_div).html('<div class="fs-6">' + mess + '</div>');
        $(alert_div).delay(2500).fadeOut(500, function() { $(this).hide(); });
    }
    
    if (success == 3 ) {
        window.location.replace('http://localhost/wordpress/login');
    }
}

jQuery(document).ready(function($) {

    var tomorrow = new Date();
    tomorrow.setDate(new Date().getDate() + 1);

    $("#in").datepicker({
        minDate: tomorrow,
        onSelect: function(dateText, inst) {
            // Get the selected date
            var inDate = new Date($(this).val());
            $("#out").datepicker('option', 'minDate', inDate);
        }
    });

    $('#out').datepicker({
        minDate: tomorrow
    });
})
