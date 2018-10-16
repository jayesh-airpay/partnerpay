<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 21/3/16
 * Time: 3:37 PM
 */
?>


<div class="header navbar-fixed-top">
    <?php
    \yii\bootstrap\NavBar::begin([
        'brandLabel' => \yii\bootstrap\Html::img($theme->baseUrl . '/images/partnerpay-logo.png', ['alt' => Yii::$app->name]),
        'brandUrl' => \yii\helpers\Url::to(['/site/dashboard']),
        'screenReaderToggleText' => 'Menu',
        'containerOptions' => [
            'class' => 'container tophead',
        ],
        'options' => [
            'class' => 'navbar navbar-default',
        ],

    ]);

    if (!Yii::$app->getUser()->getIsGuest() && Yii::$app->user->identity->USER_TYPE != 'partner') {
        echo \yii\bootstrap\Nav::widget([
            'options' => ['class' => 'nav navbar-nav navbar-right'],
            'items' => [
                Yii::$app->user->isGuest ? '' :
                    ['label' => 'Home', 'url' => ['site/dashboard']],
                Yii::$app->user->isGuest ? '' :
                    ['label' => 'Merchant',
                        'url' => '#',
                        'options' => ['class' => 'dropdown'],
                        'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                        'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => 'List Merchant', 'url' => ['/merchant/index'], 'template' => '<a href="{url}" {label}</a>',],
                            Yii::$app->user->identity->USER_TYPE == 'merchant' ? '' : ['label' => 'Create Merchant', 'url' => ['/merchant/create'], 'template' => '<a href="{url}"{label}</a>',],
                        ],
                    ],
                Yii::$app->user->isGuest ? '' :
                    ['label' => 'Partners',
                        'url' => '#',
                        'options' => ['class' => 'dropdown'],
                        'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',

                        'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => 'List Partners', 'url' => ['/partner'], 'template' => '<a href="{url}" {label}</a>',],
                            ['label' => 'Create Partner', 'url' => ['/partner/create'], 'template' => '<a href="{url}"{label}</a>', 'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE != 'partner')],
                        Yii::$app->user->identity->USER_TYPE == 'merchant' ? '' : ['label' => 'Import Partner', 'url' => ['/partner/import'], 'template' => '<a href="{url}"{label}</a>',],
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

    } else {
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
    }
    \yii\bootstrap\NavBar::end();
    ?>

</div>