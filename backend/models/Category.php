<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id_category
 * @property string $name_category
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_category'], 'required'],
            [['name_category'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_category' => 'ID',
            'name_category' => 'Название категории',
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getCategoryListArray()
    {
        $categories = self::find()->all();

        $list = array();

        foreach ($categories as $category) {
            $list[$category->id_category] = $category->name_category;
        }

        return $list;
    }
}
