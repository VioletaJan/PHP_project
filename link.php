<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>link</title>
</head>
<body>
    <h1>PUCKO</h1>
    <h2>Fullständig information</h2>

    <table border="1">
        <tr>
            <th>Name</th>
            <th>Nr</th>
            <th>Specialite</th>
            <th>Incident Name</th>
            <th>Incident Nummer</th>
        </tr>
        <tr>
            <td><?php echo $_GET["namn"]; ?></td>
            <td><?php echo $_GET["nr"]; ?></td>
            <td><?php echo $_GET["specialite"]; ?></td>
            <td><?php echo $_GET["namnInc"]; ?></td>
            <td><?php echo $_GET["nrInc"]; ?></td>
        </tr>
    </table>
</body>
</html>
