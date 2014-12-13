<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Sales */

$this->title = 'Update Sales: ' . ' ' . $model->id_sale;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_sale, 'url' => ['view', 'id' => $model->id_sale]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
