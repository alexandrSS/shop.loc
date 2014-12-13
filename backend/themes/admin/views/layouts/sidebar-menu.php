<?php

echo \common\components\widgets\menu\MenuWidget::widget([
    'options' => ['class' => 'sidebar-menu'],
    'labelTemplate' => '<a href="#">{icon}<span>{label}</span>{right-icon}{badge}</a>',
    'linkTemplate' => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
    'activateParents' => true,
    'items' => [
        [
            'label' => Yii::t('themes', 'Пользователи'),
            'icon' => '<i class="fa fa-users"></i>',
            'url' => ['/user/index']
        ],
        [
            'label' => Yii::t('themes', 'Товар'),
            'icon' => '<i class="fa fa-shopping-cart"></i>',
            'options' => ['class' => 'treeview'],
            'items' => [
                [
                    'label' => Yii::t('themes', 'Категории товара'),
                    'url' => ['/category/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                ],
                [
                    'label' => Yii::t('themes', 'Товар'),
                    'url' => ['/article/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                ],
            ]
        ],
        [
            'label' => Yii::t('themes', 'Поставки'),
            'icon' => '<i class="fa fa-truck"></i>',
            'options' => ['class' => 'treeview'],
            'items' => [
                [
                    'label' => Yii::t('themes', 'Поставщики'),
                    'url' => ['/providers/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                ],
                [
                    'label' => Yii::t('themes', 'Поставки'),
                    'url' => ['/makers/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                ],
                [
                    'label' => Yii::t('themes', 'Поставленный товар'),
                    'url' => ['/delivery/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                ],
            ]
        ],
        [
            'label' => Yii::t('themes', 'Продажи'),
            'icon' => '<i class="fa fa-truck"></i>',
            'options' => ['class' => 'treeview'],
            'items' => [
                [
                    'label' => Yii::t('themes', 'Проданные товары'),
                    'url' => ['/sales/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                ],
                [
                    'label' => Yii::t('themes', 'Продажа'),
                    'url' => ['/prodaja/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                ],
            ]
        ],
        [
            'label' => Yii::t('themes', 'Система'),
            'icon' => '<i class="fa fa-cogs"></i>',
            'options' => ['class' => 'treeview'],
            'items' => [
                [
                    'label' => Yii::t('themes', 'Журнал'),
                    'url' => ['/log/index'],
                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                    'badge' => \backend\models\SystemLog::find()->count(),
                    'badgeBgClass' => 'bg-red',
                ],
            ]
        ],
    ]
]);