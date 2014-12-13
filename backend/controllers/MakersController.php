<?php

namespace backend\controllers;

use Yii;
use backend\models\Delivery;
use backend\models\Makers;
use backend\models\search\MakersSearch;
use backend\models\Providers;
use backend\models\Article;
use backend\components\Controller;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MakersController implements the CRUD actions for Makers model.
 */
class MakersController extends Controller
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
                'roles' => ['bcMakersIndex']
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => ['bcMakersView']
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => ['bcMakersCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => ['bcMakersUpdate']
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => ['bcMakersDelete']
            ],
            [
                'allow' => true,
                'actions' => ['test'],
                'roles' => ['bcMakersTest']
            ],
//            [
//                'allow' => true,
//                'actions' => ['batch-delete'],
//                'roles' => ['bcMakersBatchDelete']
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
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all Makers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MakersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $providersList = Providers::getProvidersListArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'providersList' => $providersList
        ]);
    }

    /**
     * Displays a single Makers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $providersList = Providers::getProvidersListArray();
        $articleList = Article::getArticleListArray();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providersList' => $providersList,
            'articleList' => $articleList
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionTest()
    {
        $model = $this->findModel(25);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_makers]);
        } else {
            return $this->render('test', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Makers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Makers();
        $providersList = Providers::getProvidersListArray();
        $articleList = Article::getArticleListArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_makers]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'providersList' => $providersList,
                'articleList' => $articleList
            ]);
        }
    }

    /**
     * Updates an existing Makers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $providersList = Providers::getProvidersListArray();
        $articleList = Article::getArticleListArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_makers]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'providersList' => $providersList,
                'articleList' => $articleList
            ]);
        }
    }

    /**
     * Deletes an existing Makers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Makers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Makers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Makers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
