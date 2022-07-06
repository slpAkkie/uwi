<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>

<body>
    <h1><?= $title ?></h1>
    <p><?= $description ?></p>

    <p>
        <b>Message:</b> <?= $e->getMessage() ?> <br>
        <b>In File:</b> <?= $e->getFile() ?> <br>
        <b>On Line:</b> <?= $e->getLine() ?> <br>
    </p>

    <h2>Trace</h2>

    <pre><?= $e->getTraceAsString() ?></pre>
</body>

</html>
