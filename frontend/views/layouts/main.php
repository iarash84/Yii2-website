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

<html lang="fa-IR" dir="rtl">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body dir="rtl">
<?php $this->beginBody() ?>

<?php
if(Yii::$app->user->isGuest) { ?>
    <!--Logout Mode-->
    <nav class="teal" role="navigation">
        <div class="nav-wrapper">
            <a href="<?= Yii::$app->homeUrl ?>" class="brand-logo site-brand"><?= Html::encode($footerSetting->companyName) ?></a>
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
        <li><?= Html::a(Yii::t('app','Blog'),['/admin/blog/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Category'),['/admin/category/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','New Post'),['/admin/blog/create']) ?></li>
    </ul>


    <ul id="userDropdown" class="dropdown-content">
        <?php if(Yii::$app->user->can('manageUsers')){ ?>
            <li><?= Html::a(Yii::t('app','User Management'),['/admin/user/index']) ?></li>
        <?php } ?>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Change Password'),['/changepass']) ?></li>
        <li class="divider"></li>
        <?php if(Yii::$app->user->can('manageUsers')){ ?>
            <li><?= Html::a(Yii::t('app','Log'),['/admin/user/log']) ?></li>
            <li class="divider"></li>
        <?php } ?>
        <li><?= Html::a(Yii::t('app', 'Logout'), ['/logout'], ['data-method' => 'post']) ?></li>
    </ul>

    <ul id="settingDropdown" class="dropdown-content">
        <li><?= Html::a(Yii::t('app','Setting'),['/admin/setting/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Home Update'),['/admin/setting/home']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Carousel'),['/admin/carousel/index']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','Social Network'),['/admin/setting/social']) ?></li>
        <li class="divider"></li>
        <li><?= Html::a(Yii::t('app','System'),['/admin/setting/system']) ?></li>
    </ul>


    <nav class="teal navbar-fixed-top">
        <div class="nav-wrapper">
            <a href="<?= Yii::$app->homeUrl ?>" class="brand-logo site-brand"><?= Html::encode($footerSetting->companyName) ?></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">

                <li><?= Html::a(Yii::t('app','User Area'). '(' . Yii::$app->user->identity->username . ')','#!',['class'=>'dropdown-button' , 'data-activates'=>'userDropdown']) ?></li>
                <li><?= Html::a(Yii::t('app','Blog'),'#!',['class'=>'dropdown-button' , 'data-activates'=>'blogDropdown']) ?></li>
                <li><?= Html::a(Yii::t('app','Setting'),'#!',['class'=>'dropdown-button' , 'data-activates'=>'settingDropdown']) ?></li>
                <li><?= Html::a(Yii::t('app','Job opportunity'),['/admin/opportunity/index']) ?></li>
                <li><?= Html::a(Yii::t('app','Order app'),['/admin/order/index']) ?></li>
                <li><?= Html::a(Yii::t('app','Sample Project'),['/site/sample']) ?></li>
                <li><?= Html::a(Yii::t('app','Contact'),['/admin/contact/index']) ?></li>
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
                    <li><?= Html::a(Yii::t('app','Blog'),['/blog/index'],['class'=>"grey-text text-lighten-3"]) ?></li>
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
<button id="scroll-to-top" type="button" aria-label="Scroll to top">&uarr;</button>
<?php
$this->registerJs(<<<'JS'
const scrollButton = document.getElementById('scroll-to-top');
const toggleScrollButton = () => {
    scrollButton.classList.toggle('is-visible', window.scrollY > 300);
};
window.addEventListener('scroll', toggleScrollButton, {passive: true});
scrollButton.addEventListener('click', () => window.scrollTo({top: 0, behavior: 'smooth'}));
toggleScrollButton();
JS
);
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
