<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Providers */

$this->title = 'Обновление поставщика: ' . ' "' . $model->name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Поставщики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_providers]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="providers-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
