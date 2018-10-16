<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MerchantMaster;

/**
 * MerchantMasterSearch represents the model behind the search form about `app\models\MerchantMaster`.
 */
class MerchantMasterSearch extends MerchantMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MERCHANT_ID'], 'integer'],
            [['MERCHANT_NAME', 'DOMAIN_NAME', 'DB_NAME', 'AIRPAY_MERCHANT_KEY', 'AIRPAY_MERCHANT_USERNAME', 'AIRPAY_MERCHANT_PASSWORD', 'AIRPAY_MERCHANT_SECRETE_KEY'], 'safe'],
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
        $query = MerchantMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
     	$dataProvider->setSort([
            'defaultOrder' => ['MERCHANT_ID'=>SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            //'MERCHANT_ID' => $this->MERCHANT_ID,
            'CREATED_ON' => $this->CREATED_ON,
            'UPDATED_ON' => $this->UPDATED_ON,
        ]);
        $query->andFilterWhere(['like', 'MERCHANT_NAME', $this->MERCHANT_NAME])
            ->andFilterWhere(['like', 'DOMAIN_NAME', $this->DOMAIN_NAME])
            ->andFilterWhere(['like', 'MERCHANT_ADDRESS', $this->MERCHANT_ADDRESS])
            //->andFilterWhere(['like', 'DB_NAME', $this->DB_NAME])
            //->andFilterWhere(['like', 'AIRPAY_MERCHANT_KEY', $this->AIRPAY_MERCHANT_KEY])
            ->andFilterWhere(['like', 'AIRPAY_MERCHANT_USERNAME', $this->AIRPAY_MERCHANT_USERNAME])
            //->andFilterWhere(['like', 'AIRPAY_MERCHANT_PASSWORD', $this->AIRPAY_MERCHANT_PASSWORD])
            ->andFilterWhere(['like', 'AIRPAY_MERCHANT_SECRETE_KEY', $this->AIRPAY_MERCHANT_SECRETE_KEY]);

        return $dataProvider;
    }
}
