jQuery(document).ready(function(){
    jQuery(".timespan-from").timepicker({
        timeFormat: 'H:i',
        interval: 30,
        minTime: '10',
        maxTime: '18:00',
        defaultTime: '11',
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: false
    });
    jQuery(".timespan-to").timepicker({
        timeFormat: 'H:i',
        interval: 30,
        minTime: '10',
        maxTime: '18:00',
        defaultTime: '11',
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: false
    });
});

