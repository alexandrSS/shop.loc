<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Delivery;
use Yii;
use backend\models\Saleses;
use backend\models\Category;
use backend\components\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class ProdajaController extends Controller
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
                'roles' => ['bcProdajaIndex']
            ],
            [
                'allow' => true,
                'actions' => ['article'],
                'roles' => ['bcProdajaArticle']
            ],
            [
                'allow' => true,
                'actions' => ['srok-godnosti'],
                'roles' => ['bcProdajaSrokGodnosti']
            ],
            [
                'allow' => true,
                'actions' => ['kolichestvo'],
                'roles' => ['bcProdajaKolichestvo']
            ],
            [
                'allow' => false,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get', 'post'],
                'article' => ['post'],
                'srok-godnosti' => ['post'],
                'kolichestvo' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all Sales models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Saleses();
        $categoryList = Category::getCategoryListArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('index', [
                'model' => $model,
                'categoryList' => $categoryList
            ]);
        }
    }


    public function actionArticle() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents !== null) {
                $category_id = $parents[0];
                $out = Article::getArticleCategoryListArray($category_id);
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSrokGodnosti() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $category_id = empty($ids[0]) ? null : $ids[0];
            $article_id = empty($ids[1]) ? null : $ids[1];
            if (($article_id !== null) and ($article_id !=='Loading ...')) {
                $data = Delivery::getDeliveryListArray($article_id);
                if($data == NULL){
                    echo Json::encode(['output'=>'', 'selected'=>'']);
                }
                /**
                 * the getProdList function will query the database based on the
                 * cat_id and sub_cat_id and return an array like below:
                 *  [
                 *      'out'=>[
                 *          ['id'=>'<prod-id-1>', 'name'=>'<prod-name1>'],
                 *          ['id'=>'<prod_id_2>', 'name'=>'<prod-name2>']
                 *       ],
                 *       'selected'=>'<prod-id-1>'
                 *  ]
                 */

                echo Json::encode(['output'=>$data]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }



    public function actionKolichestvo() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $article_id = empty($ids[0]) ? null : $ids[0];
            $srok_godnosti = empty($ids[1]) ? null : $ids[1];
            if (($srok_godnosti !== null) and ($srok_godnosti !=='Loading ...') ) {
                $data = Delivery::getDeliveryKolichestvo($srok_godnosti, $article_id);
                if($data == NULL){
                    echo Json::encode(['output'=>'', 'selected'=>'']);
                }
                /**
                 * the getProdList function will query the database based on the
                 * cat_id and sub_cat_id and return an array like below:
                 *  [
                 *      'out'=>[
                 *          ['id'=>'<prod-id-1>', 'name'=>'<prod-name1>'],
                 *          ['id'=>'<prod_id_2>', 'name'=>'<prod-name2>']
                 *       ],
                 *       'selected'=>'<prod-id-1>'
                 *  ]
                 */

                echo Json::encode(['output'=>$data]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
}
