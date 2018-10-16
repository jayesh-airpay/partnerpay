<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserMaster;

/**
 * UserMasterSearch represents the model behind the search form about `app\models\UserMaster`.
 */
class UserMasterSearch extends UserMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['USER_ID', 'PARTNER_ID','MERCHANT_ID'], 'integer'],
            [['EMAIL', 'PASSWORD', 'USER_TYPE', 'FIRST_NAME', 'LAST_NAME', 'USER_STATUS', 'ACCESS_TOKEN', 'AUTH_KEY'], 'safe'],
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
        $query = UserMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
    	$dataProvider->setSort([
            'defaultOrder' => ['USER_ID'=>SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'USER_ID' => $this->USER_ID,
            'MERCHANT_ID' => $this->MERCHANT_ID,
            'PARTNER_ID' => $this->PARTNER_ID,
            'CREATED_ON' => $this->CREATED_ON,
            'UPDATED_ON' => $this->UPDATED_ON,
        ]);

        $query->andFilterWhere(['like', 'EMAIL', $this->EMAIL])
            ->andFilterWhere(['like', 'PASSWORD', $this->PASSWORD])
            ->andFilterWhere(['like', 'USER_TYPE', $this->USER_TYPE])
            //->andFilterWhere(['like', 'MERCHANT_ID', $this->MERCHANT_ID])
            ->andFilterWhere(['like', 'FIRST_NAME', $this->FIRST_NAME])
            ->andFilterWhere(['like', 'LAST_NAME', $this->LAST_NAME])
            ->andFilterWhere(['like', 'USER_STATUS', $this->USER_STATUS])
            ->andFilterWhere(['like', 'ACCESS_TOKEN', $this->ACCESS_TOKEN])
            ->andFilterWhere(['like', 'AUTH_KEY', $this->AUTH_KEY]);

        return $dataProvider;
    }
}
