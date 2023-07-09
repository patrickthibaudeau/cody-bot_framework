$(document).ready(function () {
    $('.bot').off();
    $('.bot').on('click', function () {
        let id = $(this).data('id');
        $('#bot_id').text(id);
        $.ajax({
            url: "conversations.php?id=" + id,
            type: "GET",
            dataType: "html",
            success: function(response) {
                $('#available-conversations').html(response);
                $('#conversationModal').modal('show');
                // Delete conversation
                $('.delete-conversation').off();
                $('.delete-conversation').on('click', function () {
                    let conversation_id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this conversation?')) {
                        $.ajax({
                            url: "delete_conversation.php?id=" + conversation_id + "&bot_id=" + id,
                            type: "GET",
                            dataType: "html",
                            success: function(response) {
                                $('tr').remove('.row-' + conversation_id);
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                })
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
});
