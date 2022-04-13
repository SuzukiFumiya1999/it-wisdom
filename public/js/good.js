$(function () {
    var $good = $('.good-btn'), goodPostsId;

    $good.on('click', function (e) {
        e.stopPropagation();
        var $this = $(this);
        //カスタム属性(question_id)に格納された投稿ID取得
        goodPostsId = $this.data('question_id');

        $.ajax({
            type: 'POST',
            url: './Goods.php',
            data: { question_id: goodPostsId }
        }).done(function (data) {
            console.log('Ajax Succes');
            console.log(data);
            $('.good-count').text(data);

            $this.children('i').toggleClass('fa-solid');
            $this.children('i').toggleClass('fa-regular');
        }).fail(function (msg) {
            console.log('Ajax Error');
        });
    });
});