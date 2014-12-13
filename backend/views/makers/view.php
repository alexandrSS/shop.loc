<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\Makers */

$this->title = 'Просмотр поставки';
$this->params['breadcrumbs'][] = ['label' => 'Поставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="makers-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id_makers], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id_makers], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_makers',
            'providers_id',
            'nomer_nakladnoi',
            'date',
            'status:boolean',
            'comment',
        ],
    ]) ?>

    <?php Pjax::begin(['enablePushState' => false]); ?>
        <?= $this->render('_deliveries', [
            'model' => $model,
            'articleList' => $articleList
        ]) ?>
    <?php Pjax::end(); ?>

</div>
