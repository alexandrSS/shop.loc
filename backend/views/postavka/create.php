<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Makers */

$this->title = 'Поставка';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<div class="makers-create">

    <?= $this->render('_form', [
        'model' => $model,
        'providersList' => $providersList
    ]) ?>

</div>
