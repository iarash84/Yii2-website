<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app','Sample Project');;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sample">
    <div class="page-header page-header-actions">
        <div><p class="text-overline"><?= Yii::t('app', 'Selected work') ?></p><h1><?= Html::encode($this->title) ?></h1></div>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('manageContent')): ?>
            <?= Html::a(\frontend\widgets\Icon::show('plus') . Yii::t('app', 'Create Sample'), ['/admin/sample/create'], ['class' => 'btn']) ?>
        <?php endif; ?>
    </div>

    <?= $this->render('//sample/view', [
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
