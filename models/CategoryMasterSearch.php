<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MerchantMaster;

/**
 * MerchantMasterSearch represents the model behind the search form about `app\models\MerchantMaster`.
 */
class CategoryMasterSearch extends CategoryMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CAT_NAME', 'CAT_DESC', 'CAT_STATUS'], 'safe'],
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
        $query = CategoryMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
     	$dataProvider->setSort([
            'defaultOrder' => ['CAT_ID'=>SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        $query->andFilterWhere([
            'CREATED_ON' => $this->CREATED_ON,
            'UPDATED_ON' => $this->UPDATED_ON,
        ]);
		
        $query->andFilterWhere(['like', 'CAT_NAME', $this->CAT_NAME])
            //->andFilterWhere(['like', 'CAT_DESC', $this->CAT_DESC])
            ->andFilterWhere(['like', 'CAT_STATUS', $this->CAT_STATUS]);

        return $dataProvider;
    }
}
