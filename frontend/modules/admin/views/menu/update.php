<?php
use yii\helpers\Html;
$this->title = Yii::t('app', 'Update menu item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-update"><h1><?= Html::encode($this->title) ?></h1><?= $this->render('_form', compact('model', 'parents')) ?></div>
