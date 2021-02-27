<?php

require "vendor/autoload.php";

use App\Battle;

$battle = new Battle();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/style.css">
    <title>Bataille</title>
</head>

<body>


    <header>
        <h1>Bataille</h1>
        <div class="center">
            <input type="button" id="fight" value="Fight" onclick="<?php $battle->nextTurn(); ?>"></input>
        </div>

    </header>

    <div class="container">

        <div class="summary child">
            <p class="p">
                <?= $battle->getSummary(); ?>
            </p>
        </div>
        <div class="table child">
            <?= $battle->tablePlayers(); ?>
        </div>
    </div>

</body>

</html>