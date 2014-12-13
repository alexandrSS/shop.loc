<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "providers".
 *
 * @property integer $id_providers
 * @property string $name
 * @property string $description
 *
 * @property Makers[] $makers
 */
class Providers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'providers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_providers' => 'Id',
            'name' => 'Имя',
            'description' => 'Описание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMakers()
    {
        return $this->hasMany(Makers::className(), ['providers_id' => 'id_providers']);
    }

    /**
     * @return array
     */
    public static function getProvidersListArray()
    {
        $providers = self::find()->all();

        $list = array();

        foreach ($providers as $provider) {

            $list[$provider->id_providers] = $provider->name;
        }

        return $list;
    }
}
