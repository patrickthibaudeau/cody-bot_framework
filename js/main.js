$(document).ready(function () {
    $('#prompt-form').submit(function (e) {
        e.preventDefault();

        let prompt = $('#prompt').val();
        let promptIn = `<li class="in">
                            <div class="chat-body">
                                <div class="chat-message">
                                    <p>${prompt}</p>
                                </div>
                            </div>
                        </li>`;

        $('.chat-list').append(promptIn);

        let randomElemNumber = Math.floor(Math.random() * 1000000);

        let promptOut = `<li class="out">
                            <div class="chat-body">
                                <div class="chat-message">
                                    <p>
                                        <div id="repsonse-${randomElemNumber}">
                                            <div class="spinner-grow  spinner-grow-sm" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <div class="spinner-grow  spinner-grow-sm" role="status" >
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <div class="spinner-grow  spinner-grow-sm" role="status" >
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </li>`;
        $('.chat-list').append(promptOut);

        let form_data = $(this).serializeArray();
        $.ajax({
            url: "ajax.php",
            data: form_data,
            type: "POST",
            dataType: "json",
            success: function(response) {
                console.log(response.data.content);
                $('#repsonse-' + randomElemNumber).html('');
                let typing = $('#repsonse-' + randomElemNumber);
                $('.chat-list .out .chat-message').css('width', '85%');
                typing.typer([response.data.content],{
                    delay: 1,
                    duration: 10,
                    endless: false
                });
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });

    });
});

