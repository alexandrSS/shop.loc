<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "saleses".
 *
 * @property integer $id_saleses
 * @property integer $date
 * @property integer $category_id
 * @property integer $article_id
 * @property integer $srok_godnosti
 * @property integer $kolichestvo
 * @property integer $prace
 * @property string $remark
 *
 * @property Article $article
 * @property Category $category
 */
class Saleses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'saleses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'category_id', 'article_id', 'srok_godnosti', 'kolichestvo', 'prace'], 'required'],
            [['category_id', 'article_id', 'prace'], 'integer'],
            [['remark'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_saleses' => 'Id Saleses',
            'date' => 'Дата продажи',
            'category_id' => 'Категория товара',
            'article_id' => 'Товар',
            'srok_godnosti' => 'Срок годности',
            'kolichestvo' => 'Количество',
            'prace' => 'Цена',
            'remark' => 'Примечание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id_article' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id_category' => 'category_id']);
    }
}
