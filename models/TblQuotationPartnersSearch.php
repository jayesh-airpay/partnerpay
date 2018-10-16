<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Quotation;
use app\models\TblQuotationPartners;

/**
 * QuotationSearch represents the model behind the search form about `app\models\Quotation`.
 */
class TblQuotationPartnersSearch extends TblQuotationPartners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['QUOTATION_ID', 'PARTNER_ID', 'CREATED'], 'required'],
            [['QUOTATION_ID', 'PARTNER_ID'], 'integer'],
            [['AMOUNT', 'CREATED'], 'number'],
            [['PARTNER_UPLOADED_DOC'], 'string', 'max' => 255]
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

        //$query = Quotation::find();
        $query = TblQuotationPartners::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        $dataProvider->setSort([
            'defaultOrder' => ['ID'=>SORT_DESC],
         ]);

        $this->load($params);
      
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

       // $query->andFilterWhere(['like', 'AMOUNT', '']);
         $query->andFilterWhere(['like', 'QUOTATION_ID', $this->QUOTATION_ID]);
       
            // ->andFilterWhere(['like', 'DESCRIPTION', $this->DESCRIPTION])
            // ->andFilterWhere(['like', 'STATUS', $this->STATUS]);

        //echo '<pre>';print_r($dataProvider);exit;
       
        return $dataProvider;
    }
}