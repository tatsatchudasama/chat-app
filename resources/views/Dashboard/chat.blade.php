@extends('dashboard-layouts.layout')

@section('title', 'User List')

@section('content')


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="model-title">Chat</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class=""> <!-- modal-body -->

                <div class=""> <!-- col-md-12 -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div id="chat-receiverEmail-name"></div>
                        </div>
                        <div class="card-body">

                            <div class="overflow-auto" style="height: 50vh; border: 1px solid #dee2e6; border-radius: 5px; padding: 10px; background-color: #f8f9fa;" id="chat-window">
                                <div class="row">

                                    <div class="col-md-6" id="receiver_message">
                                        <div class="alert alert-primary" role="alert">

                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-5" id="sender_message">
                                        <div class="alert alert-secondary" role="alert">

                                        </div>
                                    </div>

                                </div>

                            </div>

                            <form id="message-form">
                                <div class="mt-1">
                                    <div class="input-group">
                                        <input type="text" class="form-control message-input" id="" placeholder="Type a message...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" id="messageBtn">Send</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <table id="acceptFriendRequestsTable" class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Sender Email</th>
                <th scope="col">status</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>


@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {

        // ===============================================================
        //                        accept fried Request List 
        // ===============================================================
        $.ajax({
            url: "{{ route('chat_view') }}",
            method: "GET",
            success: function(response) {
                console.log(response.success);

                if (response.length === 0) {
                    var noDataMessage = '<tr><td colspan="3" class="text-center">No friend requests found</td></tr>';
                    $('#acceptFriendRequestsTable tbody').append(noDataMessage);
                } else {

                    var count = 1;
                    $.each(response, function(index, friendRequests) {

                        var row = '<tr>' +
                            '<td>' + count++ + '</td>' +
                            '<td>' + friendRequests.sender_email + '</td>' +
                            '<td>' +
                            // '<button class="btn btn-primary chat-user" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="' + friendRequests.id + '">Chat</button> &nbsp;' +
                            // '<button class="btn btn-primary chat-user" data-bs-toggle="modal" data-bs-target="#exampleModal" data-email="' + friendRequests.sender_email + '">Chat Email</button>' + '</td>' +
                            '<button class="btn btn-primary chat-user" data-bs-toggle="modal" data-bs-target="#exampleModal" data-email="' + friendRequests.sender_email + '" data-id="' + friendRequests.id + '">Send Message</button>' +
                            '</tr>';

                        $('#acceptFriendRequestsTable tbody').append(row);
                    });
                }

            },
            error: function(error) {
                console.log(error);
            }
        });

        // ===============================================================
        //                     Send a new chat message
        // ===============================================================
        $('#message-form').on('submit', function(e) {
            e.preventDefault();

            var message = $('.message-input').val();

            if (message.trim() === '') {
                alert('Message cannot be empty');
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('chat') }}",
                method: "POST",
                data: {
                    message: message,
                    receiver_user_email: requestId
                },
                success: function(response) {
                    $('.message-input').val('');

                    $('#sender_message').append('<div class="alert alert-secondary" role="alert">' + message + '</div>');

                    var chatWindow = $('#chat-window');
                    chatWindow.scrollTop(chatWindow[0].scrollHeight);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });


        // ===============================================================
        //                          Chat Get
        // ===============================================================
        $(document).on('click', '.chat-user', function() {
            requestId = $(this).data('id');
            var receiverEmail = $(this).data('email');

            $('#chat-receiverEmail-name').html('Chat with: <b class="text-dark">' + receiverEmail + '</b>');

            fetchMessages(receiverEmail);
        });

        function fetchMessages(receiverEmail) {
            $.ajax({
                url: "{{ route('get_message') }}",
                method: "GET",
                data: {
                    receiver_user_emails: receiverEmail
                },
                success: function(response) {
                    $('#receiver_message').empty();
                    $('#sender_message').empty();


                    // if (response && response.get_messages) {
                    //     response.get_messages.forEach(function(message) {
                    //         if (message.sender_email === '{{ auth()->user()->email }}') {
                    //             $('#sender_message').append('<div class="alert alert-secondary" role="alert">' + message.message + '</div>');
                    //         }
                    //     });
                    // }

                    // if (response && response.send_messages) {
                    //     response.send_messages.forEach(function(message) {
                    //         if (message.receiver_email === '{{ auth()->user()->email }}') {
                    //             $('#receiver_message').append('<div class="alert alert-danger" role="alert">' + message.message + '</div>');
                    //         }
                    //     });
                    // } else {
                    //     var noDataMessage = '<div class="alert alert-warning" role="alert">No chat messages found.</div>';
                    //     $('#receiver_message').append(noDataMessage);
                    // }

                    if (response && response.get_messages) {
                        response.get_messages.forEach(function(message) {
                            if (message.sender_email === '{{ auth()->user()->email }}') {
                                $('#sender_message').append('<div class="alert alert-secondary" role="alert">' + message.message + '</div>');
                            }
                        });
                    }

                    if (response && response.send_messages) {
                        response.send_messages.forEach(function(message) {
                            if (message.receiver_email === '{{ auth()->user()->email }}') {
                                $('#receiver_message').append('<div class="alert alert-danger" role="alert">' + message.message + '</div>');
                            }
                        });
                    } else {
                        var noDataMessage = '<div class="alert alert-warning" role="alert">No chat messages found.</div>';
                        $('#receiver_message').append(noDataMessage);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }


    });
</script>



<!-- // ===============================================================
//                     Send a new chat message
// ===============================================================
$(document).on('click', '.chat-user', function() {
    requestId = $(this).data('id');
});

$('#message-form').on('submit', function(e) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    e.preventDefault();

    var message = $('.message-input').val();

    if (message.trim() === '') {
        alert('Message cannot be empty');
        return;
    }

    $.ajax({
        url: "{{ route('chat') }}",
        method: "POST",
        data: {
            message: message,
            receiver_user_email: requestId
        },
        success: function(response) {
            console.log(response.success);

            $('.message-input').val('');

        },
        error: function(error) {
            console.log(error);
        }
    });
    email0
});

// ===============================================================
//                          Chat Get
// ===============================================================
var receiverEmail = '';
$(document).on('click', '.chat-user', function() {

    receiverEmail = $(this).data('email');

    // chat title
    $('#chat-receiverEmail-name').html('Chat with: <b class="text-dark">' + receiverEmail + '</b>');

    $.ajax({
        url: "{{ route('get_message') }}",
        method: "GET",
        data: {
            receiver_user_emails: receiverEmail
        },
        success: function(response) {
            $('#receiver_message').empty();
            $('#sender_message').empty();

            if (response && response.get_messages) {
                response.get_messages.forEach(function(message) {
                    if (message.sender_email === '{{ auth()->user()->email }}') {
                        $('#sender_message').append('<div class="alert alert-secondary" role="alert">' + message.message + '</div>');
                    }
                });
            }

            if (response && response.send_messages) {
                response.send_messages.forEach(function(message) {
                    if (message.receiver_email === '{{ auth()->user()->email }}') {
                        $('#receiver_message').append('<div class="alert alert-danger" role="alert">' + message.message + '</div>');
                    }
                });
            } else {
                var noDataMessage = '<div class="alert alert-warning" role="alert">No chat messages found.</div>';
                $('#receiver_message').append(noDataMessage);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}); -->