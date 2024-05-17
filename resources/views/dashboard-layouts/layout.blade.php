<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="{{ route('dashboard_index') }}">Chat App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">

                    <form id="logoutForm">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <button type="submit" id="logoutBtn"><i class="fas fa-sign-out-alt"></i>Logout</button>
                    </form>

                </li>
            </ul>
        </div>
    </nav>

    <hr>

    <div class="container-fluid mt-5">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#"><i class="fas fa-home"></i>&nbsp;&nbsp;Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_list') }}"><i class="fas fa-users"></i>&nbsp;&nbsp;Friends List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-user-friends"></i>&nbsp;&nbsp;Friend Request</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-comment-dots"></i>&nbsp;&nbsp;Chat</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

                @yield('content')

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#logoutForm').submit(function(e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // swal("Logging Out", "Please wait...", "info");

                // handel on ok or cancel button
                swal({
                    title: "Are you sure you want to logout?",
                    text: "You will be logged out of your account.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willLogout) => {
                    if (willLogout) {
                        
                        $.ajax({
                            url: "{{ route('dashboard_logout') }}",
                            type: 'POST',
                            success: function(response) {
                                // window.location.href = response.redirect;
                                setTimeout(function() {
                                    swal.close();
                                    window.location.href = response.redirect;
                                }, 1000);
                            },
                            error: function(xhr) {
                                swal("Error", xhr.responseJSON.message, "error");
                            }
                        });

                    } else {
                        swal("Logout Cancelled", "You are still logged in.", "info");
                    }
                });
                    
            });
        });
    </script>

</body>

</html>