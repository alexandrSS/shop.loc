<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */

$this->title = 'Редактирование товара: ' . ' "' . $model->name_article . '"';
$this->params['breadcrumbs'][] = ['label' => 'Товар', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_article, 'url' => ['view', 'id' => $model->id_article]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="article-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categoryList' => $categoryList
    ]) ?>

</div>
