<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app','Sample Project');;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sample">
    <h3><?= Html::encode($this->title) ?></h3>
    <p>
        <?php
        if(!Yii::$app->user->isGuest)
            echo Html::a(Yii::t('app', 'Create Sample'), ['sample/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('//sample/view', [
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
