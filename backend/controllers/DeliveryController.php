<?php

namespace backend\controllers;

use Yii;
use backend\models\Delivery;
use backend\models\search\DeliverySearch;
use backend\models\Makers;
use backend\models\Article;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * DeliveryController implements the CRUD actions for Delivery model.
 */
class DeliveryController extends Controller
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
                'roles' => ['bcDeliveryIndex']
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => ['bcDeliveryCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => ['bcDeliveryUpdate']
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => ['bcDeliveryDelete']
            ],
            [
                'allow' => true,
                'actions' => ['delete-makers'],
                'roles' => ['bcDeliveryDeleteMakers']
            ],
//            [
//                'allow' => true,
//                'actions' => ['batch-delete'],
//                'roles' => ['bcDeliveryBatchDelete']
//            ],
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
                'batch-delete' => ['post', 'delete']
            ],
        ];

        return $behaviors;
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
    public function actionCreate($makerId)
    {
        $makers = $this->findMakers($makerId);
        $model = new Delivery();
        $model->addOne($makerId);
        $makers->link('deliveries', $model);
        $articleList = Article::getArticleListArray();
        return $this->renderAjax('/makers/_deliveries', [
            'model' => $makers,
            'articleList' => $articleList
        ]);
    }

//    /**
//     * @param $makerId
//     * @return string
//     */
//    public function actionUpdate($makerId)
//    {
//        $makers = $this->findMakers($makerId);
//        $this->batchUpdate($makers->deliveries);
//        return $this->renderAjax('/makers/_deliveries', ['model' => $makers]);
//    }

    public function actionUpdate($makerId)
    {
        $maker = $this->findMakers($makerId);
        $this->batchUpdate($maker->deliveries);

        $articleList = Article::getArticleListArray();
        return $this->renderAjax('/makers/_deliveries', [
            'model' => $maker,
            'articleList' => $articleList
        ]);
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
        $makers = $this->findMakers($model->makers_id);
        $model->delete();

        $articleList = Article::getArticleListArray();
        return $this->renderAjax('/makers/_deliveries', [
            'model' => $makers,
            'articleList' => $articleList
        ]);
    }

    /**
     * Deletes an existing Delivery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteMakers($id)
    {
        $model = $this->findModel($id);
        $makers = $this->findMakers($model->makers_id);
        $model->delete();

        return $this->render('/makers/view', ['id' => 43]);
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
