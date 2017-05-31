<?php

/* @var $this \yii\web\View */
/* @var $content string */

use kartik\icons\Icon;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
$cookies = Yii::$app->request->cookies;;

Icon::map($this, Icon::WHHG);
$footerSetting = new \frontend\models\Setting();
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
if(Yii::$app->user->isGuest) { ?>
    <!--Logout Mode-->
    <nav class="teal" role="navigation">
        <div class="nav-wrapper">
            <a href="<?= Yii::$app->homeUrl ?>" class="brand-logo" style="margin-left: 15px;"><?= $footerSetting->companyName ?></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">

                <li><?= Html::a(Yii::t('app','Login'),['/site/login']) ?></li>
                <li><?= Html::a(Yii::t('app','Blog'),['/blog/index']) ?></li>
                <li><?= Html::a(Yii::t('app','Job opportunity'),['/site/opportunity']) ?></li>
                <li><?= Html::a(Yii::t('app','Order app'),['/site/order']) ?></li>
                <li><?= Html::a(Yii::t('app','Sample Project'),['/site/sample']) ?></li>
                <li><?= Html::a(Yii::t('app','Contact'),['/site/contact']) ?></li>
                <li><?= Html::a(Yii::t('app','About'),['/site/about']) ?></li>
                <li><?= Html::a(Yii::t('app','Home'),['/site/index']) ?></li>

            </ul>
        </div>
    </nav>

<?php }else { ?>

    <!-- Login Mode-->
    <ul id="blogDropdown" class="dropdown-content">
        <li><?= Html::a(Yii::t('app','Blog'),['/blog/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Category'),['/category/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','New Post'),['/blog/create']) ?></li>
    </ul>


    <ul id="userDropdown" class="dropdown-content">
        <?php if(Yii::$app->user->can('usersManagement')){ ?>
            <li><?= Html::a(Yii::t('app','User Management'),['/user/index']) ?></li>
        <?php } ?>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Change Password'),['/changepass']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Log'),['/user/log']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app', 'Logout') ,['/logout']) ?></li>
    </ul>

    <ul id="settingDropdown" class="dropdown-content">
        <li><?= Html::a(Yii::t('app','Setting'),['/setting/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Home Update'),['/setting/home']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Carousel'),['/carousel/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Social Network'),['/setting/social']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','System'),['/setting/system']) ?></li>
    </ul>


    <nav class="teal navbar-fixed-top" dir="ltr">
        <div class="nav-wrapper">
            <a href="<?= Yii::$app->homeUrl ?>" class="brand-logo" style="margin-left: 15px;"><?= $footerSetting->companyName ?></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">

                <li><?= Html::a(Yii::t('app','User Area'). '(' . Yii::$app->user->identity->username . ')','#!',['class'=>'dropdown-button' , 'data-activates'=>'userDropdown']) ?></li>
                <li><?= Html::a(Yii::t('app','Blog'),'#!',['class'=>'dropdown-button' , 'data-activates'=>'blogDropdown']) ?></li>
                <li><?= Html::a(Yii::t('app','Setting'),'#!',['class'=>'dropdown-button' , 'data-activates'=>'settingDropdown']) ?></li>
                <li><?= Html::a(Yii::t('app','Job opportunity'),['/opportunity/index']) ?></li>
                <li><?= Html::a(Yii::t('app','Order app'),['/order/index']) ?></li>
                <li><?= Html::a(Yii::t('app','Sample Project'),['/site/sample']) ?></li>
                <li><?= Html::a(Yii::t('app','Contact'),['/contact/index']) ?></li>
                <li><?= Html::a(Yii::t('app','About'),['/site/about']) ?></li>
                <li><?= Html::a(Yii::t('app','Home'),['/site/index']) ?></li>

            </ul>
        </div>
    </nav><br /><br /><br />

<?php    }
if(isset($this->params['breadcrumbs'])){ ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
<?php }else{ echo $content; }?>

<footer class="page-footer teal">
    <div class="container">
        <div class="row">

            <div class="col l4 offset-12 s12">
                <h5 class="white-text"><?= Yii::t('app','Useful links')?></h5>
                <ul>
                    <li><?= Html::a(Yii::t('app','About'),['/site/about'],['class'=>"grey-text text-lighten-3"]) ?></li>
                    <li><?= Html::a(Yii::t('app','Contact'),['/site/contact'],['class'=>"grey-text text-lighten-3"]) ?></li>
                    <li><?= Html::a(Yii::t('app','Sample Project'),['/site/sample'],['class'=>"grey-text text-lighten-3"]) ?></li>
                    <li><?= Html::a(Yii::t('app','Blog'),['/site/blog'],['class'=>"grey-text text-lighten-3"]) ?></li>
                    <li><?= Html::a(Yii::t('app','FAQS'),['/site/faqs'],['class'=>"grey-text text-lighten-3"]) ?></li>
                    <li><?= Html::a(Yii::t('app','Order app'),['/site/order'],['class'=>"grey-text text-lighten-3"]) ?></li>
                    <li><?= Html::a(Yii::t('app','Job opportunity'),['/site/opportunity'],['class'=>"grey-text text-lighten-3"]) ?></li>
                </ul>
            </div>

            <div class="col l6 s12">
                <h5 class="white-text"><?= Yii::t('app' , 'Address')?></h5>
                <address class="margin-bottom-40 grey-text text-lighten-3">
                    <?= Icon::show('map-marker', [], Icon::WHHG) ?>
                    <?= $footerSetting->address ?>
                    <br>
                    <?= Icon::show('envelope', [], Icon::WHHG) ?>
                    <?= Yii::t('app','Postal Code').' : '.$footerSetting->postalCode ?>
                    <br>
                    <?= Icon::show('phoneold', [], Icon::WHHG) ?>
                    <?= Yii::t('app','PhoneNumber').' : '.$footerSetting->phoneNumber ?>
                    <br>
                    <?= Icon::show('phonebook', [], Icon::WHHG) ?>
                    <?= Yii::t('app','FaxNumber').' : '.$footerSetting->faxNumber ?>
                    <br>
                    <?= Icon::show('at', [], Icon::WHHG) ?>
                    <?= Yii::t('app','Email').' : '.$footerSetting->email ?>
                    <br>
                    <?= Icon::show('time', [], Icon::WHHG) ?>
                    <?= Yii::t('app','Working Hours').' : '.$footerSetting->workingHours ?>
                </address>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            <div class="row">
                <div class="col s5">

                    <ul class="social-footer list-unstyled list-inline pull-right">
                        <?= $footerSetting->facebookLink ?>
                        <?= $footerSetting->twitterLink ?>
                        <?= $footerSetting->linkedinLink ?>
                        <?= $footerSetting->googlePlusLink ?>
                        <?= $footerSetting->aparatLink ?>
                        <?= $footerSetting->telegramLink ?>
                        <?= $footerSetting->instagramLink ?>
                        <?= $footerSetting->youtubeLink ?>
                    </ul>
                </div>

                <div class="col s7">
                    <p style="padding-right:15px;" rel="home" class="grey-text text-lighten-3">
                    <p>&copy; 2017 KyiiPortal.com<p>
                    </p>
                </div>

            </div>
        </div>
    </div>

</footer>
<?php echo uran1980\yii\widgets\scrollToTop\ScrollToTop::widget(); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
