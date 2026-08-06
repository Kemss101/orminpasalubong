<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div style= "border: 3px solid black">
        <h2>College and Courses</h2>
         <form action="add" method="POST">
            @csrf
            <input name = "course" type="text" placeholder="course">
            <input name ="collegeName" type="text" placeholder="collegeName">
            <button>ADD</button>
         </form>
         <br>
         <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                   
                    <th>Course</th>
                    <th>College Name</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($colleges as $college)
                <tr>
                    
                    <td>{{ $college->course }}</td>
                    <td>{{ $college->collegeName }}</td>
                </tr>
                @endforeach
            </tbody>
         </table>
    </div>
</body>
</html>
