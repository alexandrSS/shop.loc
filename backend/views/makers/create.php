<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Makers */

$this->title = 'Создание поставки';
$this->params['breadcrumbs'][] = ['label' => 'Поставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="makers-create">

    <?= $this->render('_form', [
        'model' => $model,
        'providersList' => $providersList,
        'articleList' => $articleList
    ]) ?>

</div>
