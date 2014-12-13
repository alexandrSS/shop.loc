<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\Makers */

$this->title = 'Update Makers: ' . ' ' . $model->id_makers;
$this->params['breadcrumbs'][] = ['label' => 'Поставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_makers, 'url' => ['view', 'id' => $model->id_makers]];
$this->params['breadcrumbs'][] = 'Update';


?>
<div class="makers-update">

    <?= $this->render('_form', [
        'model' => $model,
        'providersList' => $providersList
    ]) ?>

</div>
