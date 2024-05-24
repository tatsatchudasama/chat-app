@extends('dashboard-layouts.layout')

@section('title', 'friend request list')

@section('content')

<div class="container">
    <table id="friendRequestsTable" class="table table-striped">
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
        //                        Friend Request List 
        // ===============================================================
        $.ajax({
            url: "{{ route('friend_request_list') }}",
            method: "GET",
            success: function(response) {

                if (response.length === 0) {
                    var noDataMessage = '<tr><td colspan="3" class="text-center">No friend requests found</td></tr>';
                    $('#friendRequestsTable tbody').append(noDataMessage);
                } else {

                    var count = 1;
                    $.each(response, function(index, friendRequests) {

                        var statusBTN = '';

                        if (friendRequests.status === 'accept') {
                            statusBTN = '<button class="btn btn-primary un_accept-request" data-id="' + friendRequests.id + '">Un Accept</button> &nbsp;'
                        } else {
                            statusBTN = '<button class="btn btn-success accept-request" data-id="' + friendRequests.id + '">Accept</button> &nbsp;'
                        }

                        var row = '<tr>' +
                            '<td>' + count++ + '</td>' +
                            '<td>' + friendRequests.sender_email + '</td>' +
                            '<td>' + statusBTN +
                            '<button class="btn btn-danger reject-request" data-id="' + friendRequests.id + '">Reject</button>' +
                            '</td>' +
                            '</tr>';

                        $('#friendRequestsTable tbody').append(row);
                    });
                }

            },
            error: function(error) {
                console.log(error);
            }
        });

        // ===============================================================
        //                        Accept Request 
        // ===============================================================
        $(document).on('click', '.accept-request', function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var requestId = $(this).data('id');
            var $button = $(this);

            $.ajax({
                url: "{{ route('accept_request') }}",
                method: "POST",
                data: {
                    id: requestId
                },
                success: function(response) {
                    swal("Success", response.success, "success");
                    $button.replaceWith('<button class="btn btn-primary">Un Accept</button>');
                    $button.closest('tr').find('.reject-request').prop('disabled', true);
                    
                    setTimeout(function(){
                        location.reload();
                    }, 1000); 
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        // ===============================================================
        //                       UN Accept Request 
        // ===============================================================
        $(document).on('click', '.un_accept-request', function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var requestId = $(this).data('id');
            var $button = $(this);

            $.ajax({
                url: "{{ route('un_accept_request') }}",
                method: "POST",
                data: {
                    id: requestId
                },
                success: function(response) {

                    swal("Success", response.success, "success");
                    $button.replaceWith('<button class="btn btn-success accept-request" data-id="' + requestId + '">Accept</button>');
                    $button.closest('tr').find('.reject-request').prop('disabled', true);

                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        // ===============================================================
        //                        reject Request 
        // ===============================================================
        $(document).on('click', '.reject-request', function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var $button = $(this);
            var requestId = $button.data('id');

            swal({
                    title: "ARE YOU SURE YOU WANT TO REJECT REQUEST .!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willLogout) => {
                    if (willLogout) {

                        $.ajax({
                            url: "{{ route('reject_request') }}",
                            method: "POST",
                            data: {
                                id: requestId
                            },
                            success: function(response) {
                                console.log(response);
                                $button.closest('.friend-request-item').remove();

                                setTimeout(function() {
                                    location.reload();
                                }, 1000);

                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });

                    } else {
                        swal("Rejection Cancelled", "You have chosen not to reject the request.", "info");
                    }
                });





        });



    });
</script>