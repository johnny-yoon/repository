<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
        $con = mysqli_connect("localhost","root","secret","musicman");
        $sql = "DELETE FROM musicians WHERE musician_id=".$_GET['id'];
        $result = mysqli_query($con, $sql);
        echo $result;
    ?>
</body>
</html>
