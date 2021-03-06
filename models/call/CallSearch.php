<?php
namespace pistol88\client\models\call;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pistol88\client\models\Call;

class CallSearch extends Call
{
    public function rules()
    {
        return [
            [['id', 'staffer_id', 'client_id', 'category_id'], 'integer'],
            [['status', 'type', 'comment', 'date', 'matter', 'result'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Call::scenarios();
    }

    public function search($params)
    {
        $query = Call::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => new \yii\data\Sort([
                'attributes' => [
                    'status',
                    'id',
                ],
            ])
        ]);
        
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'result' => $this->result,
            'staffer_id' => $this->staffer_id,
            'client_id' => $this->client_id,
            'status' => $this->status,
            'matter' => $this->matter,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
