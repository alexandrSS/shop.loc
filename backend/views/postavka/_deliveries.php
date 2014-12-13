<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Makers */

$this->title = 'Update Makers: ' . ' ' . $model->id_makers;
$this->params['breadcrumbs'][] = ['label' => 'Makers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_makers, 'url' => ['view', 'id' => $model->id_makers]];
$this->params['breadcrumbs'][] = 'Update';
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
        'id' => 'adressesUpdateForm'
    ]); ?>

    <?php foreach ($model->deliveries as $key => $delivery): ?>

        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($delivery, "[$key]article_id") ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($delivery, "[$key]cena_postavki") ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($delivery, "[$key]kolichestvo_tovara") ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($delivery, "[$key]srok_godnosti") ?>

                <?= Html::a('Удалить', Url::toRoute(['/delivery/delete', 'id' => $delivery->id_delivery]), [
                    'class' => 'btn btn-danger',
                ]) ?>
            </div>
        </div>
    <?php endforeach ?>

    <?= Html::a('Добавить адрес', Url::toRoute(['/delivery/create', 'makerId' => $model->id_makers]), [
        'class' => 'btn btn-success',
    ]) ?>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

</div>
