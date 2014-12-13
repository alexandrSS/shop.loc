<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Delivery */

/*$this->title = 'Update Delivery: ' . ' ' . $model->id_delivery;
$this->params['breadcrumbs'][] = ['label' => 'Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_delivery, 'url' => ['view', 'id' => $model->id_delivery]];
$this->params['breadcrumbs'][] = 'Update';*/
?>
<div class="delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
