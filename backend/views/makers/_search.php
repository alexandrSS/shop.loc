<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\MakersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="makers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_makers') ?>

    <?= $form->field($model, 'providers_id') ?>

    <?= $form->field($model, 'nomer_nakladnoi') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?php // echo $form->field($model, 'comment') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
