<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `app\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['INVOICE_ID', 'PARTNER_ID', 'CREATED_BY', 'ASSIGN_TO', 'INVOICE_STATUS'], 'integer'],
            [['REF_ID', 'COMPANY_NAME', 'CLIENT_EMAIL', 'MAIL_SENT', 'ATTACHMENT','CREATED_ON'], 'safe'],
            [['CLIENT_MOBILE', 'AMOUNT', 'PAID', 'BALANCE', 'ISSUE_DATE', 'DUE_DATE'], 'number'],
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
        $query = Invoice::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
         $dataProvider->setSort([
            'defaultOrder' => ['INVOICE_ID'=>SORT_DESC],
         ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $start_date='';
        $end_date='';
        if(!empty($this->CREATED_ON)) {
            $tmp = explode('to', $this->CREATED_ON);
            $start_date = isset($tmp[0]) ? trim($tmp[0]) . ' 00:00:00' : null;
            $end_date = isset($tmp[1]) ? trim($tmp[1]) . ' 23:59:59' : null;
            $start_date = strtotime($start_date);
            $end_date = strtotime($end_date);
        }



        $query->andFilterWhere([
            'INVOICE_ID' => $this->INVOICE_ID,
            'PARTNER_ID' => $this->PARTNER_ID,
            'CREATED_BY' => $this->CREATED_BY,
            'ASSIGN_TO' => $this->ASSIGN_TO,
            //'MERCHANT_ID' => $this->MERCHANT_ID,
            'CLIENT_MOBILE' => $this->CLIENT_MOBILE,
            'AMOUNT' => $this->AMOUNT,
            'PAID' => $this->PAID,
            'BALANCE' => $this->BALANCE,
            'INVOICE_STATUS' => $this->INVOICE_STATUS,
            'IS_APPROVE' => $this->IS_APPROVE,
             'ISSUE_DATE' => $this->ISSUE_DATE,
            'DUE_DATE' => $this->DUE_DATE,
//            'EXPIRY_DATE' => $this->EXPIRY_DATE,
          //'CREATED_ON' => $this->CREATED_ON,
//            'UPDATED_ON' => $this->UPDATED_ON,
        ]);

        $query->andFilterWhere(['like', 'REF_ID', $this->REF_ID])
            ->andFilterWhere(['like', 'COMPANY_NAME', $this->COMPANY_NAME])
            ->andFilterWhere(['like', 'CLIENT_EMAIL', $this->CLIENT_EMAIL])
            ->andFilterWhere(['like', 'MAIL_SENT', $this->MAIL_SENT])
            ->andFilterWhere(['like', 'ATTACHMENT', $this->ATTACHMENT])
            ->andFilterWhere(['between', 'CREATED_ON', $start_date, $end_date])
            ->andFilterWhere(['like', 'ASSIGN_TO', $this->ASSIGN_TO]);


        return $dataProvider;
    }
}
