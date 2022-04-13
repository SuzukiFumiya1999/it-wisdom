document.getElementById('user_detail-image').addEventListener('change', function (e) {
    var file = e.target.files[0];

    var blobUrl = window.URL.createObjectURL(file);

    var img = document.getElementById('edit-user_image_preview');
    img.src = blobUrl;
});