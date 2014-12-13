<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товар';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_article',
            'name_article',
            [
                'attribute' => 'category_id',
                'format' => 'html',
                'value' => function ($model) {
                        if($model->category_id !== NULL)
                        {
                            return $model->category['name_category'];
                        }else{
                            return NULL;
                        }
                    },
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'category_id',
                        $categoryList,
                        [
                            'class' => 'form-control',
                            'prompt' => Yii::t('backend', 'Выберите категорию')
                        ]
                    )
            ],
            [
                'attribute' => 'number',
                'contentOptions' => [
                    'style' => 'width: 55px;',
                ]
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'contentOptions' => [
                    'style' => 'width: 55px;',
                ]
            ],
        ],
    ]); ?>

</div>
