jQuery(document).ready(function($){

    var timespanInterval = 30;
    var timespanFrom = '09:00';
    var timespanTo = '21:00';
    if (Drupal.settings.myTimespan != undefined && Drupal.settings.myTimespan.interval != undefined) {
        timespanInterval = Drupal.settings.myTimespan.interval;
    }
    if (Drupal.settings.myTimespan != undefined && Drupal.settings.myTimespan.from != undefined) {
        timespanFrom = Drupal.settings.myTimespan.from;
    }
    if (Drupal.settings.myTimespan != undefined && Drupal.settings.myTimespan.to != undefined) {
        timespanTo = Drupal.settings.myTimespan.to;
    }
    jQuery(".timespan-from").timepicker({
        timeFormat: 'H:i',
        interval: timespanInterval,
        minTime: timespanFrom,
        maxTime: timespanTo,
        dynamic: false,
        dropdown: true,
        scrollbar: false
    });
    jQuery(".timespan-to").timepicker({
        timeFormat: 'H:i',
        interval: timespanInterval,
        minTime: timespanFrom,
        maxTime: timespanTo,
        dynamic: false,
        dropdown: true,
        scrollbar: false
    });

    var availableClients = [];
    var clientsToId;
    if (Drupal.settings.clients != undefined && Drupal.settings.clients.availableClients != undefined) {
        availableClients = $.map(jQuery.parseJSON(Drupal.settings.clients.availableClients), function(el) { return el });
    }
    if (Drupal.settings.clients != undefined && Drupal.settings.clients.availableClientsIds != undefined) {
        clientsToId =jQuery.parseJSON(Drupal.settings.clients.availableClientsIds);
    }
    $("#edit-klijent").autocomplete({
        source: availableClients,
        select: function (event, ui) {
            var value = ui.item.value;
            if (clientsToId[value] != undefined) {
                var id = clientsToId[value];
                $("input[name='id_klijent']").val(id);
            }
        }
    });
    var host = 'http://138.201.172.173/beautyspa';
    $('#edit-grupa-usluga').change(function() {
        var groupId = $(this).val();
        $.ajax({
            url: host + "/services/autocomplete",
            type: "POST",
            data: {groupId : groupId},
            dataType: "json",
            success: function(response) {
                if (response != undefined ) {
                    var $el = $("#edit-id-usluge");
                    $el.empty();
                    $el.append($("<option></option>").attr("value", "").attr("selected", "selected").text('- Select -'));
                    $.each(response, function(key,value) {
                        $el.append($("<option></option>").attr("value", key).text(value));
                    });
                }
            }
        });
    });
});