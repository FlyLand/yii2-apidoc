<?php

use landrain\src\assets\JidAsset;
use \yii\helpers\Html;

$this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">
    <?php JidAsset::register($this); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="container-fluid">
    <?=$content ?>
</div>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage(); ?>