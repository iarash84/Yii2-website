<?php
use yii\grid\GridView; use yii\helpers\Html;
$this->title=Yii::t('app','Admin activity'); $this->params['breadcrumbs'][]=$this->title;
?><div class="audit-index"><div class="page-header"><p class="text-overline"><?= Yii::t('app','Security and accountability') ?></p><h1><?= Html::encode($this->title) ?></h1></div><?= GridView::widget(['dataProvider'=>$dataProvider,'columns'=>[['attribute'=>'user_id','value'=>static fn($m)=>$m->user?$m->user->username:Yii::t('app','Deleted user')],'route','method','ip',['attribute'=>'created_at','format'=>'datetime']]]) ?></div>
