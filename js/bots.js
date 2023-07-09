$(document).ready(function () {
    $('.bot').off();
    $('.bot').on('click', function () {
        let id = $(this).data('id');
        $.ajax({
            url: "conversations.php?id=" + id,
            type: "GET",
            dataType: "html",
            success: function(response) {
                 $('#available-conversations').html(response);
                 $('#conversationModal').modal('show');
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
});

