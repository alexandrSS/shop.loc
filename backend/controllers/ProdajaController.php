<?php

namespace backend\controllers;

use Yii;
use backend\models\Sales;
use backend\components\Controller;
use yii\filters\VerbFilter;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class ProdajaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sales models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Sales();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreate()
    {
        return $this->render('create');
    }

}
