<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>
<body>
    <table class="table mt-5">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>department</th>
            <th>hiredate</th>
            <th>city</th>
            <th>gender</th>
            <th>salary</th>
        </tr>
        <tr>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{$employee->name }}</td>
                    <td>{{$employee->email }}</td>
                    <td>{{$employee->mobile_number }}</td>
                    <td>{{$employee->department_name }}</td>
                    <td>{{$employee->hiredate }}</td>
                    <td>{{$employee->city }}</td>
                    <td>{{$employee->gender }}</td>
                    <td>{{$employee->salary }}</td>
                </tr>
            @endforeach
        </tr>
    </table>
    <div class="d-flex">
        {!! $employees->links() !!}
    </div>
</body>
</html>