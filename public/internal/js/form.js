$('.form-post').submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).data('uri'),
        type: "post",
        data: $(this).serialize(),
        dataType: 'JSON',
        beforeSend: function() {
            $('.loader').show();
        },
        success: function (data) {
            $('.loader').hide();
            if (data.response == 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Selamat !',
                    text: data.msg,
                    type: "success"
                }).then(function () {
                    window.location = data.url;
                });
            } else if (data.response == 'failed') {

                $('.information').css('display', 'block');
                $('.information').addClass('alert alert-danger');
                $('.information').html(data.msg);
                Swal.fire({
                    icon: 'error',
                    title: 'Ada Kesalahan !',
                    text: 'terjadi kesalahan',
                    type: "error"
                });

            }
        }
    });
});