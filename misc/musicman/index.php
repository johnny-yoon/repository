<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous"><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
    <?php
        $con = mysqli_connect("localhost","root","secret","musicman");
        $result = mysqli_query($con,"SELECT * FROM musicians");
        // while ($row = mysqli_fetch_assoc($result)) {
        //     echo $row["musician_id"]." ".$row["name"]." ".$row['birthday']."<br />";
        // }
    ?>
    <!-- <table with="800" border="1">
        <tr>
            <th>Musician ID</th>
            <th>Musician Name</th>
            <th>Musician Birthday</th>
            <th>Musician Nationality</th>
        </tr> -->
    <?php
        echo '<table border="1">';
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>'.$row["musician_id"].'</td>';
            echo '<td>'.$row["fname"].'</td>';
            echo '<td>'.$row["birthday"].'</td>';
            echo '<td>'.$row["nationality"].'</td>';
            echo '<td><a type="button" class="btn btn-danger" href="del.php?id='.$row["musician_id"].'">Delete</a></td>';
            echo '</tr>';
        }  
        echo '</table>';
    ?>
    <a type="button" class="btn btn-primary" href="add.html">Add Musician</a>
    </div>
</body>
</html>