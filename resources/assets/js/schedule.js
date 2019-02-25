$('.availability_change').each(function (i, e) {
    var button = $(e);
    button.on('click', function() {
        $.post('/api/schedules/' + $('#schedule_name').data('id') +
            '/candidates/'+ button.data('candidate'),
            { availability: button.data('availability') }
            ,"json")
            .done(function(data) {
              alert( "second success" + data.availability);
            })
            .fail(function() {
              alert( "error" );
            })
            .always(function() {
              alert( "finished" );
            });
    });
});
