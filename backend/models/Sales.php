<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sales".
 *
 * @property integer $id_sale
 * @property string $date
 * @property integer $delivery_id
 * @property integer $kolichestvo
 * @property double $price
 * @property string $remark
 */
class Sales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'delivery_id'], 'required'],
            [['date'], 'safe'],
            [['delivery_id', 'kolichestvo'], 'integer'],
            [['price'], 'number'],
            [['remark'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_sale' => 'Id  Sale',
            'date' => 'Date',
            'delivery_id' => 'Id  Delivery',
            'kolichestvo' => 'Количество',
            'price' => 'Цена',
            'remark' => 'Remark',
        ];
    }
}
