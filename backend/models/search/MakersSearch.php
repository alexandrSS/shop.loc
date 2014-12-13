<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Makers;

/**
 * MakersSearch represents the model behind the search form about `backend\models\Makers`.
 */
class MakersSearch extends Makers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_makers', 'providers_id'], 'integer'],
            [['nomer_nakladnoi', 'date', 'comment'], 'safe'],
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
        $query = Makers::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_makers' => $this->id_makers,
            'providers_id' => $this->providers_id,
            'date' => $this->date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nomer_nakladnoi', $this->nomer_nakladnoi])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
