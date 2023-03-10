<!doctype html>
<html lang="en">

<head>
    <title>Employee list</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <table id="example" class="display table table-striped table-bordered table-responsive " style="width:100%">
            <thead>
                <tr>
                    <th class="bg-primary-subtle" colspan="8">Employee Data</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>hire date</th>
                    <th>city</th>
                    <th>gender</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->mobile_number }}</td>
                        <td>{{ $employee->department_name }}</td>
                        <td>{{ $employee->hiredate }}</td>
                        <td>{{ $employee->city }}</td>
                        <td>{{ $employee->gender }}</td>
                        <td>{{ $employee->salary }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div class="d-flex">
         {!! $employees->links() !!}
     </div> --}}
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>

</html>
