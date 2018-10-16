<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GroupInvoice;

/**
 * GroupInvoiceSearch represents the model behind the search form about `app\models\GroupInvoice`.
 */
class GroupInvoiceSearch extends GroupInvoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['GROUP_INVOICE_ID', 'GROUP_ID', 'PARTNER_ID'], 'integer'],
            [['PARTNER_NAME', 'PAN_NO', 'INVOICE_STATUS'], 'safe'],
            [['AMOUNT', 'CREATED_ON', 'UPDATED_ON'], 'number'],
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
        $query = GroupInvoice::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    	$dataProvider->setSort([
            'defaultOrder' => ['GROUP_INVOICE_ID'=>SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'GROUP_INVOICE_ID' => $this->GROUP_INVOICE_ID,
            'GROUP_ID' => $this->GROUP_ID,
            'PARTNER_ID' => $this->PARTNER_ID,
            'AMOUNT' => $this->AMOUNT,
            'INVOICE_STATUS' => $this->INVOICE_STATUS,
//            'CREATED_ON' => $this->CREATED_ON,
//            'UPDATED_ON' => $this->UPDATED_ON,
        ]);

        $query->andFilterWhere(['like', 'PARTNER_NAME', $this->PARTNER_NAME])
            ->andFilterWhere(['like', 'PAN_NO', $this->PAN_NO]);

        return $dataProvider;
    }
}
