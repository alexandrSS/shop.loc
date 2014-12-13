<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Makers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="makers-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'providers_id')->dropDownList($providersList, [
                'prompt' => Yii::t('backend', 'Выберите поставщика')
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'nomer_nakladnoi')->textInput(['maxlength' => 10]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'createdAtJui')->widget(
                DatePicker::className(),
                [
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                        'changeMonth' => true,
                        'changeYear' => true
                    ]
                ]
            ); ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'comment')->textInput(['maxlength' => 100]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Добавить товар', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
