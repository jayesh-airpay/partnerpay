<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserMerchant;

/**
 * UserMerchantSearch represents the model behind the search form about `app\models\UserMerchant`.
 */
class UserMerchantSearch extends UserMerchant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['USER_ID', 'PARTNER_ID'], 'integer'],
            [['EMAIL', 'PASSWORD', 'USER_TYPE', 'FIRST_NAME', 'LAST_NAME', 'USER_STATUS'], 'safe'],
            [['CREATED_ON', 'UPDATED_ON'], 'number'],
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
        $query = UserMerchant::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'USER_ID' => $this->USER_ID,
            'PARTNER_ID' => $this->PARTNER_ID,
            'CREATED_ON' => $this->CREATED_ON,
            'UPDATED_ON' => $this->UPDATED_ON,
        ]);

        $query->andFilterWhere(['like', 'EMAIL', $this->EMAIL])
            ->andFilterWhere(['like', 'PASSWORD', $this->PASSWORD])
            ->andFilterWhere(['like', 'USER_TYPE', $this->USER_TYPE])
            ->andFilterWhere(['like', 'FIRST_NAME', $this->FIRST_NAME])
            ->andFilterWhere(['like', 'LAST_NAME', $this->LAST_NAME])
            ->andFilterWhere(['like', 'USER_STATUS', $this->USER_STATUS]);

        return $dataProvider;
    }
}
