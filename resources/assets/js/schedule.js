var availability_labels = ['欠', '？', '出'];
var buttonStyles = ['btn-danger', 'btn-secondary', 'btn-success'];

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.availability_change').each(function (i, e) {
    var button = $(e);
    button.on('click', function() {
        $.post('/api/schedules/' + $('#schedule_name').data('id') +
            '/candidates/'+ button.data('candidate'),
            { availability: button.data('availability') },
            "json")
            .done(function(data) {
                button.data('availability', data.availability);
                button.text(availability_labels[data.availability]);
                button.removeClass('btn-danger btn-secondary btn-success');
                button.addClass(buttonStyles[data.availability]);
            })
            .fail(function(xhr) {
                ajaxFail(xhr);
            });
    });
});

$('#comment_edit').on('click', function() {
    input_comment = prompt("コメントを255文字以内で入力してください", "");
    if(!input_comment){
      return;
    }
    $.post('/api/schedules/' + $('#schedule_name').data('id') + '/comment/',
        { comment: input_comment },
        "json")
        .done(function(data) {
            $('#comment_self').text(data.comment);
        })
        .fail(function(xhr) {
            ajaxFail(xhr);
        });
});

function ajaxFail(xhr) {
    var status =  xhr.status;
    if (status == 400 || status == 404){
        alert("不正なリクエストです！");
    }else if (status == 422){
        var json = xhr.responseJSON
        for(var key in json) {
            alert(json[key]);
        }
    }else{
        alert("サーバ内部エラーです。");
    }
}
