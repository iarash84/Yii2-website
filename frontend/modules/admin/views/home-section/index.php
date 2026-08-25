<?php
use frontend\models\HomeSection;
use yii\grid\GridView;
use yii\helpers\Html;
$this->title=Yii::t('app','Homepage sections');
?>
<div class="page-header page-header-actions"><div><p class="text-overline"><?= Yii::t('app','Homepage builder') ?></p><h1><?= Html::encode($this->title) ?></h1><p><?= Yii::t('app','Change section order, visibility and content without editing code.') ?></p></div><?= Html::a(Yii::t('app','Add section'),['create'],['class'=>'btn']) ?></div>
<div class="card table-responsive"><?= GridView::widget(['dataProvider'=>$dataProvider,'columns'=>['title',['attribute'=>'type','value'=>static fn($m)=>HomeSection::typeOptions()[$m->type] ?? $m->type],'sort_order',['attribute'=>'status','format'=>'boolean'],['class'=>'yii\grid\ActionColumn','template'=>'{update} {delete}']]]) ?></div>
