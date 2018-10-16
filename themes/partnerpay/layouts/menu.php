<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 21/3/16
 * Time: 3:37 PM
 */

?>

<?php
$b_logo = '';
if(!empty (Yii::$app->controller->bank_logo)) {
    $b_logo = '<span class = "brandimg bank-brand">'.\yii\bootstrap\Html::img(\yii\helpers\Url::to('/uploads/bank_logo/'.Yii::$app->controller->bank_logo)).'</span>';
}?>

<?php 
if(strpos($_SERVER['REQUEST_URI'], 'bbps') !== false){
	$logo_url = '/bbps/images/bbps-logo.png';
}else{
	$logo_url = $theme->baseUrl . '/images/partnerpay-logo.png';
}
?>

<div class="header navbar-fixed-top">
    <?php
    \yii\bootstrap\NavBar::begin([
        //'brandLabel' => \yii\bootstrap\Html::img($theme->baseUrl . '/images/partnerpay-logo.png', ['alt' => Yii::$app->name]),
        'brandLabel' => '<span class ="brandimg">'.\yii\bootstrap\Html::img($logo_url, ['alt' => Yii::$app->name]).'</span>'.$b_logo,
        'brandUrl' => \yii\helpers\Url::to(['/site/dashboard']),
        'screenReaderToggleText' => 'Menu',
        'containerOptions' => [
            'class' => 'container tophead',
        ],
        'options' => [
            'class' => 'navbar navbar-default',
        ],

    ]);

    //if (!Yii::$app->getUser()->getIsGuest() && Yii::$app->user->identity->USER_TYPE != 'partner') {
    echo \yii\bootstrap\Nav::widget([
        'options' => ['class' => 'nav navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->isGuest ? '' :
                ['label' => 'Home', 'url' => ['site/dashboard'],'visible' => Yii::$app->user->identity->USER_TYPE != 'guestuser'],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'Merchant',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE == 'admin'),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'List Merchant', 'url' => ['/merchant/index'], 'template' => '<a href="{url}" {label}</a>',],
                        ['label' => 'Create Merchant',
                            'url' => ['/merchant/create'], 'template' => '<a href="{url}"{label}</a>',
                            'visible' => (Yii::$app->user->identity->USER_TYPE != 'partner' && Yii::$app->user->identity->USER_TYPE != 'merchant' && Yii::$app->user->identity->USER_TYPE != 'guestuser'),
                        ],
                    ],
                ],
            Yii::$app->user->isGuest ? '' :
                ['label' => '<span>Partners <span class="subtext">(Vendors)</span></span>',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE != 'partner' && Yii::$app->user->identity->USER_TYPE != 'approver' && Yii::$app->user->identity->USER_TYPE != 'payment' && Yii::$app->user->identity->USER_TYPE != 'guestuser'),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'List Partners', 'url' => ['/partner'], 'template' => '<a href="{url}" {label}</a>',
                        ],
                        ['label' => 'Create Partner', 'url' => ['/partner/create'], 'template' => '<a href="{url}"{label}</a>', 'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE != 'partner' && Yii::$app->user->identity->USER_TYPE != 'approver' && Yii::$app->user->identity->USER_TYPE != 'payment')],
                        ['label' => 'Import Partner', 'url' => ['/partner/import'], 'template' => '<a href="{url}"{label}</a>', 'visible' => (Yii::$app->user->identity->USER_TYPE == 'admin' || Yii::$app->user->identity->USER_TYPE == 'merchant'),],
                        ['label' => 'List of QR','url' => ['/quotation/listofquotationsrequest'],'template' => '<a href="{url}"{label}</a>', 'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE != 'merchant' && Yii::$app->user->identity->USER_TYPE != 'approver' && Yii::$app->user->identity->USER_TYPE != 'payment')]
                    ],
                ],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'Invoices',
                    'url' => '#',
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'visible' => Yii::$app->user->identity->USER_TYPE != 'guestuser',
                    'items' => [
                        ['label' => 'List Invoices', 'url' => ['/invoice'], 'template' => '<a href="{url}" {label}</a>',],
                        ['label' => 'Create Invoice', 'url' => ['/invoice/create'], 'template' => '<a href="{url}"{label}</a>', 'visible' => (Yii::$app->user->identity->USER_TYPE != 'approver' && Yii::$app->user->identity->USER_TYPE != 'payment' && Yii::$app->user->identity->USER_TYPE != 'guestuser'),],
                    ],
                ],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'PO',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE != 'approver' && Yii::$app->user->identity->USER_TYPE != 'payment' && Yii::$app->user->identity->USER_TYPE != 'guestuser'),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'List PO', 'url' => ['/po'], 'template' => '<a href="{url}" {label}</a>',],
                        ['label' => 'Create PO', 'url' => ['/po/create'], 'template' => '<a href="{url}"{label}</a>', 'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE != 'partner'),],
                        ['label' => 'Import Bulk PO', 'url' => ['/po/import'], 'template' => '<a href="{url}"{label}</a>', 'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE != 'partner'),],
                    ],
                ],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'Group',
                    'url' => '#',
                    'options' => ['class' => 'dropdown'],
                    'visible' => Yii::$app->user->identity->USER_TYPE == 'merchant',
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'List Group', 'url' => ['/group/index'], 'template' => '<a href="{url}" {label}</a>',],
                        ['label' => 'Create Group', 'url' => ['/group/create'], 'template' => '<a href="{url}"{label}</a>'],
                        ['label' => 'Update UTR', 'url' => ['/group/utr-update'], 'template' => '<a href="{url}"{label}</a>'],
                    ],
                ],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'Users',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE != 'approver' && Yii::$app->user->identity->USER_TYPE != 'payment' && Yii::$app->user->identity->USER_TYPE != 'muser' && Yii::$app->user->identity->USER_TYPE != 'guestuser'),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'List Users', 'url' => ['/user/index'], 'template' => '<a href="{url}" {label}</a>',],
                        ['label' => 'Create User', 'url' => ['/user/create'], 'template' => '<a href="{url}"{label}</a>',],
                    ],
                ],
             Yii::$app->user->isGuest ? '' :
                ['label' => 'QR',
                    'url' => '#',
                    'visible' =>  Yii::$app->user->identity->USER_TYPE == 'partner',
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                
                
                'items'=>[
                    ['label' => 'Requested', 'url' => ['/quotation/listofquotationsrequest'],'visible' => Yii::$app->user->identity->USER_TYPE == 'partner'],
                   ['label' => 'Assigned', 'url' => ['/quotation/listofquotationsassigned'],'visible' => Yii::$app->user->identity->USER_TYPE == 'partner']   
                ]],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'Master',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE == 'admin'),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'Category', 'url' => ['/category/index'], 'template' => '<a href="{url}" {label}</a>',],
                    ],
                ],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'Billing',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE == 'admin' || Yii::$app->user->identity->USER_TYPE == 'merchant'),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'QR', 'url' => ['/quotation/index'], 'template' => '<a href="{url}" {label}</a>',],
                    ],
                ],
            Yii::$app->user->isGuest ? '' :
                ['label' => 'KYC Docs',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE == 'guestuser'),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'Uploads', 'url' => ['guest-user-doc/index'], 'template' => '<a href="{url}" {label}</a>',],
                      
                  //   ['label' => 'Uploaded Doc List', 'url' => ['guest-user-doc/uploadeddocs'], 'template' => '<a href="{url}" {label}</a>',],
                        

                    ],
                ],
        
               Yii::$app->user->isGuest ? '' :
                ['label' => 'Quotations',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->USER_TYPE == 'guestuser' ),
                    'options' => ['class' => 'dropdown'],
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                       ['label' => 'List of QR','url' => ['/quotation/listofquotationsrequest'],'template' => '<a href="{url}"{label}</a>', 'visible' => Yii::$app->user->identity->USER_TYPE == 'guestuser'],
                     ['label' => 'Assigned', 'url' => ['/quotation/listofquotationsassigned'],'visible' => Yii::$app->user->identity->USER_TYPE == 'guestuser']
                    ],
                ],
        	
        	(Yii::$app->user->identity->PARTNER_ID != 23) && (Yii::$app->user->identity->MERCHANT_ID != 2) ? '':
                [
                    'label' => 'BBPS',
                        'url' => '#',
                        //'visible' => (Yii::$app->user->identity->USER_TYPE == 'guestuser' ),
                        'options' => ['class' => 'dropdown'],
                        'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                        'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => 'Dashboard','url' => ['/bbps/default'],'template' => '<a href="{url}"{label}</a>'],
                            ['label' => 'FAQs', 'url' => ['/bbps/default/faq'],]
                    ],
                        'url' => ['/bbps/default'],
                ],
        
            Yii::$app->user->isGuest ?
                ['label' => 'Login',
                    'url' => ['/site/login']] :
                [
                    'label' => '<i class="fa fa-user" data-toggle="tooltip" title="' . Yii::$app->user->identity->EMAIL . '"></i>',
                    'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span>' . Yii::$app->user->identity->EMAIL . '</span><i class="fa fa-user"></i></a>',
                    'url' => '#',
                    'options' => ['class' => 'userbox dropdown'],
                    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                    'items' => [
                        ['label' => 'Profile', 'url' => ['/merchant/view/', 'id' => Yii::$app->user->identity->MERCHANT_ID], 'template' => '<a href="{url}"{label}</a>', 'visible' => Yii::$app->user->identity->USER_TYPE == 'merchant',],
                        ['label' => 'Profile', 'url' => ['/partner/view/', 'id' => Yii::$app->user->identity->PARTNER_ID], 'template' => '<a href="{url}"{label}</a>', 'visible' =>  Yii::$app->user->identity->USER_TYPE == 'partner',],
                        [   'label' => 'Logout',
                            'url' => ['/site/logout'],
                            'template' => '<a href="{url}" {label}</a>',
                            'linkOptions' => ['data-method' => 'post'],
                        ],

                    ],
                ],
        ],
        'encodeLabels' => false,
        'dropDownCaret' => null
    ]);

    /* } else {
         echo \yii\bootstrap\Nav::widget([
             'options' => ['class' => 'nav navbar-nav navbar-right'],
             'items' => [
                 Yii::$app->user->isGuest ? '' :
                     ['label' => 'Home', 'url' => ['site/dashboard']],
                 Yii::$app->user->isGuest ? '' :
                     ['label' => 'Partners',
                         'url' => '#',
                         'options' => ['class' => 'dropdown'],
                         'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                         'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                         'items' => [
                             ['label' => 'List Partners', 'url' => ['/partner'], 'template' => '<a href="{url}" {label}</a>',],
                         ],
                     ],
                 Yii::$app->user->isGuest ? '' :
                     ['label' => 'Invoices',
                         'url' => '#',
                         'options' => ['class' => 'dropdown'],
                         'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                         'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                         'items' => [
                             ['label' => 'List Invoices', 'url' => ['/invoice'], 'template' => '<a href="{url}" {label}</a>',],
                             ['label' => 'Create Invoice', 'url' => ['/invoice/create'], 'template' => '<a href="{url}"{label}</a>',],
                         ],
                     ],
                 Yii::$app->user->isGuest ? '' :
                     ['label' => 'Users',
                         'url' => '#',
                         'options' => ['class' => 'dropdown'],
                         'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                         'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                         'items' => [
                             ['label' => 'List Users', 'url' => ['/user/index'], 'template' => '<a href="{url}" {label}</a>',],
                             ['label' => 'Create User', 'url' => ['/user/create'], 'template' => '<a href="{url}"{label}</a>',],
                         ],
                     ],
                 Yii::$app->user->isGuest ?
                     ['label' => 'Login',
                         'url' => ['/site/login']] :
                     [
                         'label' => '<span>' . Yii::$app->user->identity->EMAIL . '</span><i class="fa fa-user"></i>',
                         'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span>' . Yii::$app->user->identity->EMAIL . '</span><i class="fa fa-user"></i></a>',
                         'url' => '#',
                         'options' => ['class' => 'userbox dropdown'],
                         'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                         'items' => [
                             ['label' => 'Logout',
                                 'url' => ['/site/logout'],
                                 'template' => '<a href="{url}" {label}</a>',
                                 'linkOptions' => ['data-method' => 'post'],
                             ],
                         ],
                     ],
             ],
             'encodeLabels' => false,
             'dropDownCaret' => null

         ]);
     }*/
    \yii\bootstrap\NavBar::end();
    ?>

</div>