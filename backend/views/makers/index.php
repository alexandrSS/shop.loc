<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MakersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поставки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="makers-index">

    <p>
        <?= Html::a('Добавить поставку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'providers_id',
                'format' => 'html',
                'value' => function ($model) {
                        if($model->nazvaniePostavshika !== NULL)
                        {
                            return Html::a($model->nazvaniePostavshika['name'], ['/providers/view', 'id' => $model->providers_id['id_providers']]);
                        }else{
                            return NULL;
                        }
                    },
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'providers_id',
                        $providersList,
                        [
                            'class' => 'form-control',
                            'prompt' => Yii::t('backend', 'Выберите поставщика')
                        ]
                    )
            ],
            'nomer_nakladnoi',
            [
                'attribute' => 'date',
                'format' => 'date',
            ],
            'status:boolean',
            // 'comment',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
