document.getElementById('edit-image').addEventListener('change',function(e) {
    var file = e.target.files[0];

    var blobUrl = window.URL.createObjectURL(file);

    var img = document.getElementById('edit-preview');
    img.src = blobUrl;
});

$(function () {
    $("#e-content").on('keydown keyup keypress change', function() {
        var count = $(this).val().length;
        var limit = 512 - count;
        if (limit <= 512) {
            $('#num4').text(limit);
            $("input[type='submit']").prop('disabled', false).removeClass('disabled');
            if (limit <= 0) {
                $('#num').text('0');
                $("input[type='submit']").prop('disabled', true).addClass('disabled');
            }
        }
    })
})