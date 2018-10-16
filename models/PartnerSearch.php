<?php

namespace app\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Partner;

/**
 * PartnerSearch represents the model behind the search form about `app\models\Partner`.
 */
class PartnerSearch extends Partner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PARTNER_ID', 'AIRPAY_MERCHANT_ID', 'MERCHANT_ID'], 'integer'],
            [['PARTNER_NAME', 'PARTNER_LOCATION', 'AIRPAY_USERNAME', 'AIRPAY_PASSWORD', 'AIRPAY_SECRET_KEY', 'EMAIL_FOOTER', 'PARTNER_STATUS'], 'safe'],
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

        $query = Partner::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
     	$dataProvider->setSort([
            'defaultOrder' => ['PARTNER_ID'=>SORT_DESC],
         ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'PARTNER_ID' => $this->PARTNER_ID,
            'MERCHANT_ID' => $this->MERCHANT_ID,
            'AIRPAY_MERCHANT_ID' => $this->AIRPAY_MERCHANT_ID,
//            'CREATED_ON' => $this->CREATED_ON,
//            'UPDATED_ON' => $this->UPDATED_ON,
        ]);

        $query->andFilterWhere(['like', 'PARTNER_NAME', $this->PARTNER_NAME])
            ->andFilterWhere(['like', 'PARTNER_LOCATION', $this->PARTNER_LOCATION])
            ->andFilterWhere(['like', 'AIRPAY_USERNAME', $this->AIRPAY_USERNAME])
            ->andFilterWhere(['like', 'AIRPAY_PASSWORD', $this->AIRPAY_PASSWORD])
            ->andFilterWhere(['like', 'AIRPAY_SECRET_KEY', $this->AIRPAY_SECRET_KEY])
            ->andFilterWhere(['like', 'EMAIL_FOOTER', $this->EMAIL_FOOTER])
            ->andFilterWhere(['like', 'PARTNER_STATUS', $this->PARTNER_STATUS]);

        return $dataProvider;
    }
}
