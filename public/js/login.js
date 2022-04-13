$(function () {
    $('.toggle-pass').on('click', function () {
        $(this).toggleClass('fa-eye-slash');
        var input = $(this).prev('input');
        if (input.attr('type') == 'text') {
            input.attr('type', 'password');
        } else {
            input.attr('type', 'text');
        }
    });
});