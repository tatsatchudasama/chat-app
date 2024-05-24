<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>

    <h2>User List</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr class="table-primary">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Address</th>
                <th scope="col">Date Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user_lists as $index => $user_list)
            <tr>
                <th scope="row">{{ $user_list->id }}</th>
                <td>{{ $user_list->name }}</td>
                <td>{{ $user_list->email }}</td>
                <td>{{ $user_list->phone }}</td>
                <td>{{ $user_list->address }}</td>
                <td>{{ $user_list->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>