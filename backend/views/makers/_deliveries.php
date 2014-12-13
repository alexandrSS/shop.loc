<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Makers */

$this->registerJs('$(function(){
    $(document).on("click", "[data-toggle=reroute]", function(e) {
        e.preventDefault();
        var $this = $(this);
        var data = $this.data();
        var action = data.action;
        var $form = $this.closest("form");
        if ($form && action) {
            $form.attr("action", action).submit();
        } else {
            alert("Ошибка! Пожалуйста, сообщите администрации.");
        }
    });
})');
?>
<div class="makers-update">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['/delivery/update', 'makerId' => $model->id_makers]),
        'options' => [
            'data-pjax' => '1'
        ],
        'id' => 'deliveryUpdateForm'
    ]); ?>

    <?php foreach ($model->deliveries as $key => $delivery): ?>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($delivery, 'article_id')->dropDownList($articleList, [
                    'prompt' => Yii::t('backend', 'Выберите товар')
                ]) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($delivery, "[$key]cena_postavki") ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($delivery, "[$key]kolichestvo_tovara") ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($delivery, "[$key]srok_godnosti") ?>
            </div>
            <div class="col-sm-2">
                <br>
                <?= Html::a('Удалить', Url::toRoute(['/delivery/delete', 'id' => $delivery->id_delivery]), [
                    'class' => 'btn btn-danger',
                ]) ?>
            </div>
        </div>
        <hr>
    <?php endforeach ?>

    <?= Html::a('Добавить товар', Url::toRoute(['/delivery/create', 'makerId' => $model->id_makers]), [
        'class' => 'btn btn-success',
    ]) ?>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

</div>
