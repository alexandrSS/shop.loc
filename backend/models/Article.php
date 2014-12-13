<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id_article
 * @property string $name_article
 * @property integer $category_id
 * @property integer $number
 *
 * @property Delivery[] $deliveries
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_article'], 'required'],
            [['category_id', 'number'], 'integer'],
            [['name_article'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_article' => 'ID',
            'name_article' => 'Название товара',
            'category_id' => 'Категория',
            'number' => 'Количество',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::className(), ['aArticle_id' => 'id_article']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id_category' => 'category_id']);
    }

    /**
     * @return array
     */
    public static function getArticleListArray()
    {
        $articles = self::find()->all();

        $list = array();

        foreach ($articles as $article) {

            $list[$article->id_article] = $article->name_article;
        }

        return $list;
    }
}
