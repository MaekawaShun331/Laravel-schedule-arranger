var availability_labels = ['欠', '？', '出'];

$('.availability_change').each(function (i, e) {
    var button = $(e);
    button.on('click', function() {
        $.post('/api/schedules/' + $('#schedule_name').data('id') +
            '/candidates/'+ button.data('candidate'),
            { availability: button.data('availability') }
            ,"json")
            .done(function(data) {
                button.data('availability', data.availability);
                button.text(availability_labels[data.availability]);
            })
            .fail(function() {
                if (XMLHttpRequest.status = 404){
                    alert("不正なリクエストです！")
                }else{
                    alert("サーバ内部エラーです。");
                }
            });
    });
});
