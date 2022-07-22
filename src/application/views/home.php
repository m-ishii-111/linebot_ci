<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .container {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>HomePage</h1>
        <p>This page is home page.</p>
        <?php if (empty($users)): ?>
            <?php foreach($users as $user): ?>
                <p><?= $user['name']?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>