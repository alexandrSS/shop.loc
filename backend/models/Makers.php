<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "makers".
 *
 * @property integer $id_makers
 * @property integer $providers_id
 * @property string $nomer_nakladnoi
 * @property string $date
 * @property boolean $status
 * @property string $comment
 *
 * @property Delivery[] $deliveries
 * @property Providers $НазваниеПоставщика
 */
class Makers extends \yii\db\ActiveRecord
{
    /**
     * @var string Jui created date
     */
    private $_createdAtJui;

    /**
     * @return string Jui created date
     */
    public function getCreatedAtJui()
    {
        if (!$this->isNewRecord && $this->_createdAtJui === null) {
            $this->_createdAtJui = Yii::$app->formatter->asDate($this->date);
        }
        return $this->_createdAtJui;
    }

    /**
     * Set jui created date
     */
    public function setCreatedAtJui($value)
    {
        $this->_createdAtJui = $value;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'makers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['providers_id', 'nomer_nakladnoi'], 'required'],
            [['providers_id'], 'integer'],
            [['status'], 'boolean'],
            [['nomer_nakladnoi'], 'string', 'max' => 10],
            [['comment'], 'string', 'max' => 100],
            [['nomer_nakladnoi'], 'unique'],
            ['createdAtJui', 'date']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_makers' => 'Id  Makers',
            'providers_id' => 'Название Поставщика',
            'nomer_nakladnoi' => 'Номер накладной',
            'date' => 'Дата поставки',
            'status' => 'Статус накладной',
            'comment' => 'Примечание',
            'createdAtJui' => 'Дата поставки'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::className(), ['makers_id' => 'id_makers']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNazvaniePostavshika()
    {
        return $this->hasOne(Providers::className(), ['id_providers' => 'providers_id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->_createdAtJui) {
                $this->date = Yii::$app->formatter->asTimestamp($this->_createdAtJui);
            }
            return true;
        } else {
            return false;
        }
    }
}
