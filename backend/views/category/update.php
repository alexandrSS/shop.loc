<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$this->title = 'Редактирование категории товара: ' . ' "' . $model->name_category . '"';
$this->params['breadcrumbs'][] = ['label' => 'Категории товара', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_category, 'url' => ['view', 'id' => $model->id_category]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
