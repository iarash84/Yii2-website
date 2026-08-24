<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Setting */

$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = Yii::t('app', 'About');
?>
<div class="setting-update">

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">


            <?= Tabs::widget([
                'navType' => 'nav-tabs nav-justified',
                'items' => [
                    [
                        'label' => Yii::t('app' ,'View'),
                        'content' =>  '<br />'.$model->content,
                        'active' => true
                    ],
                    [
                        'label' => Yii::t('app' ,'Update'),
                        'content' =>  '<br />'.$this->render('_form', ['model' => $model]),
                    ],
                ],
            ]);
            ?>


        </div>
    </div>
</div>
