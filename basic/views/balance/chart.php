<?php

use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var array $balanceData
 */

$this->title = 'График изменения баланса';
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js');

// Подготовка данных для графика
$labels = array_keys($balanceData);
$data = array_values($balanceData);

$this->registerJs("
    var ctx = document.getElementById('balanceChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: " . json_encode($labels) . ",
            datasets: [{
                label: 'Баланс',
                data: " . json_encode($data) . ",
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
");
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= Html::beginForm(['/graf'], 'post', ['enctype' => 'multipart/form-data']) ?>
    <?= Html::fileInput('file') ?>
    <?= Html::submitButton('Загрузить и построить график') ?>
<?= Html::endForm() ?>
<canvas id="balanceChart" width="800" height="400"></canvas>





