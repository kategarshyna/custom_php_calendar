<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="icon" href="favicon.png">

    <title>Custom PHP Calendar</title>
</head>
<body>
<?php include_once "calendar.php";?>
<div class="container">
    <h1>Custom PHP Calendar</h1>
    <form method="post" action="">
        <div class="form-group col-md-6">
            <label for="date">Enter date (dd-mm-yyyy) / (dd.mm.yyyy) / (dd/mm/yyyy):</label>
            <input type="text" id="date" class="form-control <?= !empty($error) ? 'is-invalid' : '' ?> <?= !empty($weekDayResult) ? 'is-valid' : '' ?>" name="date" value="<?= $_POST['date'] ?>" placeholder="01-01-1990" required="required"/>
            <div class="invalid-feedback"><?= $error ?></div>
            <div class="valid-feedback"><?= $weekDayResult ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
