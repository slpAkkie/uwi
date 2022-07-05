<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Exception: <?= $e->getMessage() ?></h1>
    <p>
        <b>File: </b> <?= $e->getFile() ?> <br>
        <b>On line: </b> <?= $e->getLine() ?> <br>
        <b>Code: </b> <?= $e->getCode() ?> <br>
    </p>

    <h2>Trace</h2>
    <pre><?= $e->getTraceAsString() ?></pre>
</body>

</html>
