<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Quotation;

/**
 * QuotationSearch represents the model behind the search form about `app\models\Quotation`.
 */
class QuotationSearch extends Quotation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'CAT_ID', 'PARENT_ID', 'VERSION_ID', 'ASSIGN_PARTNER', 'ASSIGN_DATE'], 'integer'],
            [['NAME', 'DESCRIPTION', 'STATUS'], 'safe'],
            [['DUE_DATE'], 'number'],
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
       //echo '<pre>';print_r($params['QuotationSearch']['CREATED']);exit;
        $query = Quotation::find();
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        $dataProvider->setSort([
            'defaultOrder' => ['ID'=>SORT_DESC],
         ]);

        $this->load($params);
        
        if(!empty($params['QuotationSearch']['CREATED'])){
          $this->CREATED = strtotime($params['QuotationSearch']['CREATED']);
        }
   
        if(!empty($params['QuotationSearch']['DUE_DATE'])){
          $this->DUE_DATE = strtotime($params['QuotationSearch']['DUE_DATE']);
        }
       // echo '<pre>';print_r($this);exit;
    
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            // echo '<pre>';print_r($this->errors);exit;
            return $dataProvider;
        }
        //echo '<pre>';print_r($this);exit;
        // $query->andFilterWhere([
        //     'ID' => $this->ID,
        //     'CAT_ID' => $this->CAT_ID,
        //     'PARENT_ID' => $this->PARENT_ID,
        //     'VERSION_ID' => $this->VERSION_ID,
        //     'DUE_DATE' => $this->DUE_DATE,
        //     'ASSIGN_PARTNER' => $this->ASSIGN_PARTNER,
        //     'ASSIGN_DATE' => $this->ASSIGN_DATE,
        //     'CREATED' => $this->CREATED,
        //     'MODIFIED' => $this->MODIFIED,
        // ]);
        //var_dump($this->CREATED);exit;
        $query->andFilterWhere(['like', 'NAME', $this->NAME])
            ->andFilterWhere(['like', 'CREATED', $this->CREATED])
            ->andFilterWhere(['like', 'DUE_DATE', $this->DUE_DATE])
            ->andFilterWhere(['like', 'STATUS', $this->STATUS]);

        return $dataProvider;
    }
}
