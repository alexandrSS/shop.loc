<?php

use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Продажа';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sales-index">

    <div class="sales-form">
        <?php $form = ActiveForm::begin() ?>
        <div class="row">
            <div class="col-sm-3">
                <?=
                $form->field($model, 'category_id')->dropDownList($categoryList, [
                    'id' => 'category_id',
                    'prompt' => 'Выберите категорию...'
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?=
                $form->field($model, 'article_id')->widget(DepDrop::classname(), [
                    'options' => ['id' => 'article_id'],
                    'pluginOptions' => [
                        'depends' => ['category_id'],
                        'placeholder' => 'Выберите...',
                        'url' => Url::to(['/prodaja/article'])
                    ]
                ]) ?>
            </div>
            <div class="col-sm-2">
                <?=
                $form->field($model, 'srok_godnosti')->widget(DepDrop::classname(), [
                    'options' => ['id' => 'srok_godnosti'],
                    'pluginOptions' => [
                        'depends' => ['category_id', 'article_id'],
                        'placeholder' => 'Выберите...',
                        'url' => Url::to(['/prodaja/srok-godnosti'])
                    ]
                ]) ?>
            </div>
            <div class="col-sm-2">
                <?=
                $form->field($model, 'kolichestvo')->widget(DepDrop::classname(), [
                    'pluginOptions' => [
                        'depends' => ['article_id', 'srok_godnosti'],
                        'placeholder' => 'Выберите...',
                        'url' => Url::to(['/prodaja/kolichestvo'])
                    ]
                ]) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'prace')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <?=
                $form->field($model, 'date')->widget(
                    DatePicker::className(),
                    [
                        'options' => [
                            'class' => 'form-control'
                        ],
                        'clientOptions' => [
                            'dateFormat' => 'yyyy-MM-dd',
                        ]
                    ]
                ); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>

</div>