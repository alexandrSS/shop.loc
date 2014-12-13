<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use backend\assets\DependentDropdown;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

DependentDropdown::register($this);
$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="sales-index">

    <div class="sales-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-4"><!-- HTML Markup (Parent) -->
                <select id="cat-id">
                    <option id="">Select ...</option>
                    <!-- other options -->
                </select>
            </div>
            <div class="col-sm-4">
                <!-- HTML Markup (Child # 1) -->
                <select id="subcat-id">
                    <option id="">Select ...</option>
                    <!-- other options -->
                </select>
            </div>
            <div class="col-sm-4">
                <!-- HTML Markup (Child # 2) -->
                <select id="prod-id">
                    <option id="">Select ...</option>
                    <!-- other options -->
                </select>
            </div>
        </div>

        <?= $form->field($model, 'date')->textInput() ?>

        <?= $form->field($model, 'kolichestvo')->textInput() ?>

        <?= $form->field($model, 'price')->textInput() ?>

        <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<script>
    // Child # 1
    $("#subcat-id").depdrop({
        url: '/server/getSubcat',
        depends: ['cat-id']
    });

    // Child # 2
    $("#prod-id").depdrop({
        url: '/server/getProd',
        depends: ['cat-id', 'subcat-id']
    });
</script>