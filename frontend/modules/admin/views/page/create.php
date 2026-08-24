<?php
use yii\helpers\Html;
$this->title = Yii::t('app', 'Create page'); $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dynamic pages'), 'url' => ['index']]; $this->params['breadcrumbs'][] = $this->title;
?><div class="page-create"><h1><?= Html::encode($this->title) ?></h1><?= $this->render('_form', compact('model', 'media')) ?></div>
