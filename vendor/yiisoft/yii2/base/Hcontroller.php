<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 11/6/15
 * Time: 12:15 PM
 * Description: extends all functionality of Controller class, only difference is, it uses own render function do deal with ajax page rendering.
 */

namespace yii\base;

use app\models\MerchantMaster;
use Yii;
use yii\web\Controller;

class Hcontroller extends Controller {
    /**
     * @param string $id the ID of this controller.
     * @param Module $module the module that this controller belongs to.
     * @param array $config name-value pairs that will be used to initialize the object properties.
     */
    public $merchant_id = null;
    public $vendor_logo = '';
	public $page_title = null;
	public $bank_logo = null;
    public $DB_name = 'db';
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function init()
    {
		
        parent::init();

        $server_name = Yii::$app->getRequest()->getHeaders()->get('host');

        $domain_arr = explode(".", $server_name);

        $sub_domain = array_shift($domain_arr);
        if($sub_domain == 'www')    {
            $sub_domain = '';
        }

        if(!empty($sub_domain)) {
            $merchant = MerchantMaster::find()->where(['DOMAIN_NAME' => $sub_domain])->one();
            if(!empty($merchant))   {
                $this->merchant_id = $merchant->MERCHANT_ID;
            	$this->page_title = $merchant->MERCHANT_NAME;
            	$this->bank_logo = $merchant->BANK_LOGO;
            }
            if(!Yii::$app->user->isGuest){
                $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID'=>Yii::$app->user->identity->MERCHANT_ID])->one();
            	//var_dump($merchant_details);
                if(!empty($merchant_details)) {
                    Yii::$app->name = $merchant_details->MERCHANT_NAME;
                	//Yii::$app->logo= $merchant_details->MERCHANT_LOGO;
                }

            }
        } 




        return true;
    }


    /**
     * If it's a normal web page request, renders a view and applies layout if available.
     * If it's an ajax request, return json encoded result with json header.
     *
     * @param string $view the view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return array|string the rendering result.
     */

}