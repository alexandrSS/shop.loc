<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Delivery;

/**
 * DeliverySearch represents the model behind the search form about `backend\models\Delivery`.
 */
class DeliverySearch extends Delivery
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_delivery', 'makers_id', 'article_id', 'kolichestvo_tovara'], 'integer'],
            [['cena_postavki'], 'number'],
            [['srok_godnosti', 'remark'], 'safe'],
            [['status'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Delivery::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_delivery' => $this->id_delivery,
            'makers_id' => $this->makers_id,
            'article_id' => $this->article_id,
            'cena_postavki' => $this->cena_postavki,
            'kolichestvo_tovara' => $this->kolichestvo_tovara,
            'srok_godnosti' => $this->srok_godnosti,
            'status' => $this->Статус,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
