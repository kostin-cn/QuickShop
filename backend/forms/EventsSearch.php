<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entities\Events;

class EventsSearch extends Events
{
    public function rules()
    {
        return [
            [['status', 'variants'], 'integer'],
            [['title_ru', 'date'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Events::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'variants' => $this->variants,
        ]);

        $query->andFilterWhere(['like', 'title_ru', $this->title_ru])
            ->andFilterWhere(['>=', 'date', $this->date ? strtotime($this->date . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'date', $this->date ? strtotime($this->date . ' 23:59:59') : null]);
        return $dataProvider;
    }
}
