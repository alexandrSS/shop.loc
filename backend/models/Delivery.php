<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property integer $id_delivery
 * @property integer $makers_id
 * @property integer $article_id
 * @property double $cena_postavki
 * @property integer $kolichestvo_tovara
 * @property string $srok_godnosti
 * @property string $remark
 * @property boolean $status
 *
 * @property Article $idArticle
 * @property Makers $idMakers
 * @property Sales[] $sales
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['makers_id', 'article_id', 'kolichestvo_tovara'], 'required'],
            [['makers_id', 'article_id', 'kolichestvo_tovara'], 'integer'],
            [['cena_postavki'], 'number'],
            [['srok_godnosti'], 'safe'],
            [['remark'], 'string'],
            [['status'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_delivery' => 'Id  Delivery',
            'makers_id' => 'Id  Makers',
            'article_id' => 'Товар',
            'cena_postavki' => 'Цена поставки',
            'kolichestvo_tovara' => 'Количество товара',
            'srok_godnosti' => 'Срок годности',
            'remark' => 'Remark',
            'status' => 'Статус',
        ];
    }

    public function addOne($makerId)
    {
        $this->makers_id = $makerId;
        $this->article_id = 2;
        $this->cena_postavki = 10;
        $this->kolichestvo_tovara = 1;
        $this->srok_godnosti = 1;
        $this->remark = 'test';
        $this->status = 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdArticle()
    {
        return $this->hasOne(Article::className(), ['id_article' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMakers()
    {
        return $this->hasOne(Makers::className(), ['id_makers' => 'makers_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sales::className(), ['delivery_id' => 'id_delivery']);
    }
}
