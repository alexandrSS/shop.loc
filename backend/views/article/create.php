<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Article */

$this->title = 'Добавление товара';
$this->params['breadcrumbs'][] = ['label' => 'Товар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <?= $this->render('_form', [
        'model' => $model,
        'categoryList' => $categoryList
    ]) ?>

</div>
