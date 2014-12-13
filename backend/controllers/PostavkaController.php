<?php

namespace backend\controllers;

use Yii;
use backend\models\Delivery;
use backend\models\search\DeliverySearch;
use backend\models\Makers;
use backend\models\Providers;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * Class PostavkaController
 * @package backend\controllers
 */
class PostavkaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['bcPostavkaIndex']
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => ['bcPostavkaCreate']
            ],
            [
                'allow' => true,
                'actions' => ['create-tovar'],
                'roles' => ['bcPostavkaCreateTovar']
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => ['bcPostavkaUpdate']
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => ['bcPostavkaDelete']
            ],
            [
                'allow' => false,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'post'],
                'delete' => ['get', 'post', 'delete'],
            ],
        ];

        return $behaviors;
    }


    public function actionCreate()
    {
        $model = new Makers();
        $providersList = Providers::getProvidersListArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create-tovar', 'makerId' => $model->id_makers]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'providersList' => $providersList
            ]);
        }
    }


    public function actionCreateTovar($makerId)
    {
        $makers = $this->findMakers($makerId);
        $model = new Delivery();
        $model->addOne($makerId);
        $makers->link('deliveries', $model);
        return $this->renderAjax('/makers/_deliveries', ['model' => $makers]);
    }

    /**
     * Lists all Delivery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Delivery model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Delivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatezz($makerId)
    {
        $makers = $this->findMakers($makerId);
        $model = new Delivery();
        $model->addOne($makerId);
        $makers->link('deliveries', $model);
        return $this->renderAjax('/makers/_deliveries', ['model' => $makers]);
    }

    /**
     * @param $makerId
     * @return string
     */
    public function actionUpdate($makerId)
    {
        $makers = $this->findMakers($makerId);
        $this->batchUpdate($makers->deliveries);
        return $this->renderAjax('/makers/_deliveries', ['model' => $makers]);
    }

    /**
     * @param $items
     */
    protected function batchUpdate($items)
    {
        if (Model::loadMultiple($items, Yii::$app->request->post()) &&
            Model::validateMultiple($items)) {
            foreach ($items as $key => $item) {
                $item->save();
            }
        }
    }

    /**
     * Deletes an existing Delivery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $makers = $this->findMakers($model->id_makers);
        $model->delete();

        return $this->renderAjax('/makers/_deliveries', ['model' => $makers]);

    }

    /**
     * Finds the Delivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Delivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Delivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMakers($id)
    {
        if (($model = Makers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
