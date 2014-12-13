<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_delivery') ?>

    <?= $form->field($model, 'makers_id') ?>

    <?= $form->field($model, 'article_id') ?>

    <?= $form->field($model, 'cena_postavki') ?>

    <?= $form->field($model, 'kolichestvo_tovara') ?>

    <?php // echo $form->field($model, 'srok_godnosti') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'Статус')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
