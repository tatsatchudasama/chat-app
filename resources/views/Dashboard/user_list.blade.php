@extends('dashboard-layouts.layout')

@section('title', 'User List')

@section('content')

<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm-7">
                <h2>Friends List</h2>
            </div>

            <div class="col-sm-4">

                <form id="userSearch" autocomplete="off">
                    <div class="input-group">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="search" id="search" name="search" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary" id="SearchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm">

                <a href="{{ route('registration_view') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>

                <a href="{{ route('user_list') }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-refresh" aria-hidden="true"></i>
                </a>

                <a href="{{ route('user_list_pdf') }}" id="download-pdf" class="btn btn-danger btn-sm">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                </a>

            </div>
        </div>
    </div>
</div>


<div id="user-list-container">
    @foreach($user_lists as $user_list)
    <div class="container">
        <div class="col-md-12 mt-3 user-list">
            <div class="card b-1 hover-shadow mb-20 border border-warning">
                <div class="media card-body">
                    <div class="media-left pr-12">
                        <img class="avatar avatar-xl no-radius" src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="..." width="60" height="60">
                    </div>
                    <div class="media-body">
                        <div class="mb-2">
                            <span class="fs-20 pr-16 ml-3">{{ $user_list->name }}</span>
                        </div>
                        <small class="fs-16 fw-300 ls-1 ml-3">{{ $user_list->email }}</small>
                    </div>
                    <div class="media-right text-right d-none d-md-block">
                        <p class="fs-14 text-fade mb-12"><i class="fa fa-map-marker pr-1"></i>{{ $user_list->address }}</p>
                        <span class="text-fade"><i class="fa fa-phone pr-1"></i>{{ $user_list->phone }}</span>
                    </div>
                </div>
                <footer class="card-footer flexbox align-items-center">
                    <div>
                        <strong>Applied on :</strong>
                        <span>{{ $user_list->created_at->format('Y M,d H:i:s') }}</span>
                    </div>
                    <div class="card-hover-show">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-userid="{{ $user_list->id }}">Edit</button>
                        <button type="button" class="btn @if($user_list->status == 'active') btn-success @else btn-danger @endif">{{ $user_list->status }}</button>
                        <button type="button" class="btn btn-warning">{{ $user_list->role }}</button>

                        @if($friendRequests->where('receiver_email', $user_list->email)->where('sender_email', Auth::user()->email)->first())
                            <button type="button" class="btn btn-secondary un_send_request" data-userid="{{ $user_list->id }}" data-userEmail="{{ $user_list->email }}" id="Un_sendRequestBTN">Sending Request...</button>
                        @else
                            <button type="button" class="btn btn-danger send_request" data-userid="{{ $user_list->id }}" id="sendRequestBTN">Send Request</button>
                        @endif

                        <!-- <button type="button" class="btn btn-danger send_request" data-userid="{{ $user_list->id }}" id="sendRequestBTN">Send Request</button> -->
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <br>
    @endforeach
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="model-title"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="editUser">
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <input type="hidden" name="id" id="userId" value="">

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control border" name="name" id="name" placeholder="Enter name">
                        <div id="nameError" class="text-danger error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email address:</label>
                        <input type="email" class="form-control border" name="email" id="email" placeholder="Enter email">
                        <div id="emailError" class="text-danger error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number:</label>
                        <input type="text" class="form-control border" name="phone" id="phone" placeholder="Enter mobile number" maxlength="10">
                        <div id="phoneError" class="text-danger error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea class="form-control border" cols="3" rows="3" name="address" id="address" placeholder="Enter address"></textarea>
                        <div id="addressError" class="text-danger error-message"></div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editUserBtn"></button>
            </div>

        </div>
    </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    
    $(document).ready(function() {

        // ===============================================================
        //                           Data Search
        // ===============================================================
        $('#userSearch').submit(function(event) {
            event.preventDefault();
            submitSearch();
        });

        $('#search').on('keyup', function() {
            submitSearch();
        });

        function submitSearch() {
            var text = $('#search').val();
            $.ajax({
                type: "GET",
                url: "{{ route('user_search') }}",
                data: {
                    text: text
                },
                success: function(data) {
                    // console.log(data);

                    $('#user-list-container').empty();

                    if (data.length === 0) {

                        var noDataHtml = `
                            <div class="card b-1 hover-shadow mb-20 border border-danger">
                                <div class="media card-body">
                                    <div class="media-left pr-12">
                                        <div class="text-danger">No data available ...!</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#user-list-container').append(noDataHtml);

                    } else {

                        data.forEach(function(user) {

                            var cardHtml = `
                                <div class="card b-1 hover-shadow mb-20 border border-warning">
                                    <div class="media card-body">
                                        <div class="media-left pr-12">
                                            <img class="avatar avatar-xl no-radius" src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="..." width="60" height="60">
                                        </div>
                                        <div class="media-body">
                                            <div class="mb-2">
                                                <span class="fs-20 pr-16 ml-3">${user.name}</span>
                                            </div>
                                            <small class="fs-16 fw-300 ls-1 ml-3">${user.email}</small>
                                        </div>
                                        <div class="media-right text-right d-none d-md-block">
                                            <p class="fs-14 text-fade mb-12"><i class="fa fa-map-marker pr-1"></i>${user.address}</p>
                                            <span class="text-fade"><i class="fa fa-money pr-1"></i>${user.phone}</span>
                                        </div>
                                    </div>
                                    <footer class="card-footer flexbox align-items-center">
                                        <div>
                                            <strong>Applied on:</strong>
                                            <span>${user.created_at}</span>
                                        </div>
                                        <div class="card-hover-show">
                                            <button type="button" class="btn btn-primary">Edit</button>
                                            <button type="button" class="btn ${user.status == 'active' ? 'btn-success' : 'btn-danger'}">${user.status}</button>
                                            <button type="button" class="btn btn-warning">${user.role}</button>
                                            <button type="button" class="btn btn-danger">Send Request</button>
                                        </div>
                                    </footer>
                                </div>
                            `;
                            $('#user-list-container').append(cardHtml);

                        });

                    }
                }
            });
        }

        // ===============================================================
        //                           User Edit
        // ===============================================================
        $('#exampleModal').on('show.bs.modal', function(event) {
            var editButton = $(event.relatedTarget);
            var userId = editButton.data('userid');
            $('#userId').val(userId);


            $('#model-title').html('Edit User');
            $('#editUserBtn').html('Save Change');

            // call this function userId vise data show
            getUserData(userId);
        });

        // create this function on data show
        function getUserData(userId) {
            $.ajax({
                url: '{{ route("edit_user", "") }}/' + userId,
                method: "GET",
                success: function(response) {
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#phone').val(response.phone);
                    $('#address').val(response.address);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // ===============================================================
        //                           User Update
        // ===============================================================
        $('#editUserBtn').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#editUser').serialize();

            var userId = $('#userId').val();

            $('.error-message').html('');

            $.ajax({
                url: '{{ route("update_user", "") }}/' + userId,
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response.success);

                    swal("success", response.success, "success");
                    $('#editUserBtn').prop("disabled", false);

                    $('#exampleModal').modal('hide');

                    setTimeout(function() {
                        location.reload();
                    }, 1000);

                },
                error: function(xhr, status, error) {

                    $('#saveBTN').attr('disabled', false);

                    $('#nameError').html(xhr.responseJSON.errors.name);
                    $('#emailError').html(xhr.responseJSON.errors.email);
                    $('#phoneError').html(xhr.responseJSON.errors.phone);
                    $('#addressError').html(xhr.responseJSON.errors.address);

                }
            });
        });

        // ===============================================================
        //                           fried request
        // ===============================================================
        $('.send_request').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('userid');
            var $button = $(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('fried_request') }}",
                method: "POST",
                data: {
                    receiver_user_email: userId
                },
                success: function(response) {

                    swal("success", response.success, "success");

                    $button.removeClass('btn-danger send_request')
                           .addClass('btn-secondary un_send_request')
                           .text('Sending Request...')
                           .prop('disabled', false)
                           .data('useremail', userId);
                        
                           setTimeout(function(){
                                location.reload();
                            }, 1000); 

                },
                error: function(error) {
                    console.log(error);
                    $button.text('Send Request').prop('disabled', false);

                }
            });
        });

        // ===============================================================
        //                       Un fried request send
        // ===============================================================
        $('.un_send_request').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('userid');
            var userEmail = $(this).data('useremail');
            var $button = $(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('un_friend_request') }}",
                method: "POST",
                data: {
                    receiver_user_id: userId,
                    receiver_user_email: userEmail
                },
                success: function(response) {
                    swal("success", response.success, "success");

                    $button.removeClass('btn-secondary un_send_request')
                           .addClass('btn-danger send_request')
                           .text('Send Request')
                           .prop('disabled', false)
                           .data('userid', userId);

                           setTimeout(function(){
                                location.reload();
                            }, 1000); 

                },
                error: function(error) {
                    console.log(error);
                    $button.text('Un Send Request').prop('disabled', false); // Re-enable the button
                }
            });
        });


    });
</script>