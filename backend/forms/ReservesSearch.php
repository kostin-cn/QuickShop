<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entities\Reserves;

/**
 * ReservesSearch represents the model behind the search form of `common\entities\Reserves`.
 */
class ReservesSearch extends Reserves
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'restaurant_id', 'qty', 'status', 'dispatch'], 'integer'],
            [['name', 'email', 'phone', 'date', 'notes', 'language'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function search($params, $rest_id)
    {
        $query = Reserves::find()->where(['restaurant_id' => $rest_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'qty' => $this->qty,
            'status' => $this->status,
            'dispatch' => $this->dispatch,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['>=', 'date', $this->date ? strtotime($this->date . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'date', $this->date ? strtotime($this->date . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
