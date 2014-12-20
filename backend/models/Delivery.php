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
    public function getArticle()
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


    public static function getDeliveryListArray($article_id)
    {
        if($deliverys = self::find()->where(['article_id'=>$article_id])->all()){

            $list = array();

            foreach ($deliverys as $delivery) {

                $list[] = [
                        'id' => $delivery->id_delivery,
                        'name' => $delivery->srok_godnosti
                    ];
            }

            return $list;
        }
        return null;
    }


    public static function getDeliveryKolichestvo($srok_godnosti, $article_id)
    {
        if($srok = self::find()->where(['id_delivery'=>$srok_godnosti])->one()){

            $deliverys = self::find()->where(['srok_godnosti'=>$srok->srok_godnosti,'article_id'=>$article_id])->all();

            $list = array();
            $count = 0;

            foreach ($deliverys as $delivery) {

                $count += $delivery->kolichestvo_tovara;

            }

            for($a = 1; $a <= $count; $a++){
                $list[] = [
                        'id' => $a,
                        'name' => $a
                    ];
            }

            return $list;
        }
        return null;
    }

/*    public static function getDeliveryKolichestvo($srok_godnosti, $article_id)
    {
        if($srok = self::find()->where(['id_delivery'=>$srok_godnosti])->one()){

            $deliverys = self::find()->where(['srok_godnosti'=>$srok->srok_godnosti,'article_id'=>$article_id])->all();


            $list = array();
            $list['out']=array();
            $i = 0;
            foreach ($deliverys as $delivery) {

                array_push($list['out'],[
                        'id' => $delivery->id_delivery,
                        'name' => $delivery->kolichestvo_tovara]
                );

                if($i == 0){
                    $list['selected'] = $delivery->id_delivery;
                    $i++;
                }
            }
            return $list;
        }
        return null;
    }*/
}
