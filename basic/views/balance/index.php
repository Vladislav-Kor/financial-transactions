<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Загрузка файла';

?>

<h1>Загрузка файла</h1>

<?= Html::beginForm(['/graf'], 'post', ['enctype' => 'multipart/form-data']) ?>
    <?= Html::fileInput('file') ?>
    <?= Html::submitButton('Загрузить и построить график') ?>
<?= Html::endForm() ?>
