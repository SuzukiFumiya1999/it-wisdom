document.getElementById('a-image').addEventListener('change',function(e) {
    var file = e.target.files[0];

    var blobUrl = window.URL.createObjectURL(file);

    var img = document.getElementById('a-preview');
    img.src = blobUrl;
});

$(function () {
    $("#a-content").on('keydown keyup keypress change', function() {
        var count = $(this).val().length;
        var limit = 512 - count;
        if (limit <= 512) {
            $('#num3').text(limit);
            $("input[type='submit']").prop('disabled', false).removeClass('disabled');
            if (limit <= 0) {
                $('#num').text('0');
                $("input[type='submit']").prop('disabled', true).addClass('disabled');
            }
        }
    })
})