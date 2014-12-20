<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\DeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поставленный товар';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_delivery',
            //'makers_id',
            [
                'attribute' => 'article_id',
                'format' => 'html',
                'value' => function ($model) {
                        if($model->article_id !== NULL)
                        {
                            return $model->article['name_article'];
                        }else{
                            return NULL;
                        }
                    },
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'article_id',
                        $articleList,
                        [
                            'class' => 'form-control',
                            'prompt' => Yii::t('backend', 'Выберите товар')
                        ]
                    )
            ],
            'cena_postavki',
            'kolichestvo_tovara',
            'srok_godnosti',
            'remark:ntext',
            'status:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
