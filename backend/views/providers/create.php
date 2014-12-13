<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Providers */

$this->title = 'Добавление поставщика';
$this->params['breadcrumbs'][] = ['label' => 'Поставщики', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="providers-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
