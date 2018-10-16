<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PoMaster;

/**
 * PoMasterSearch represents the model behind the search form about `app\models\PoMaster`.
 */
class PoMasterSearch extends PoMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PO_ID', 'MERCHANT_ID','PARTNER_ID'], 'integer'],
            [['SAP_REFERENCE', 'PO_NUMBER', 'PDF_ATTACHMENT', 'IS_PAID','STATUS'], 'safe'],
            [['DATE_OF_CREATION', 'AMOUNT', 'CREATED_ON', 'UPDATED_ON'], 'number'],
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
        $query = PoMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
         $dataProvider->setSort([
            'defaultOrder' => ['PO_ID'=>SORT_DESC],
         ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'PARTNER_ID' => $this->PARTNER_ID,
            'DATE_OF_CREATION' => $this->DATE_OF_CREATION,
            'AMOUNT' => $this->AMOUNT,
            'CREATED_ON' => $this->CREATED_ON,
            'UPDATED_ON' => $this->UPDATED_ON,
        ]);

        $query->andFilterWhere(['like', 'SAP_REFERENCE', $this->SAP_REFERENCE])
            ->andFilterWhere(['like', 'PO_NUMBER', $this->PO_NUMBER])
            ->andFilterWhere(['like', 'PDF_ATTACHMENT', $this->PDF_ATTACHMENT])
            ->andFilterWhere(['like', 'STATUS', $this->STATUS])
            ->andFilterWhere(['like', 'IS_PAID', $this->IS_PAID]);

        return $dataProvider;
    }
}
