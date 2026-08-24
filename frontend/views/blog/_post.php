<?php
use yii\helpers\Html;

?>
<div class="blog-post">
    <article>
<!--        <h5> --><?php //= Html::a(Html::encode($model->title) ,['blog/'.$model->id]) ?><!--</h5>-->
        <?php $localizedTitle = $model->getLocalized('title'); ?>
        <h5> <?= Html::a(Html::encode($localizedTitle) ,['blog/view','id' => $model->id, 'subject' => str_replace(' ','_',trim($localizedTitle))]) ?></h5>

        <div class="row">
            <div class="group1 col-sm-6 col-md-6">
                <span class="glyphicon glyphicon-folder-open" style="margin-left: 5px"></span><?= $model->user->username ?></a>

            </div>
            <div class="group2 col-sm-6 col-md-6">
                <span class="glyphicon glyphicon-time"></span> <?= $model->createDatetime ?>
            </div>
        </div>
        <hr>
        <?= \yii\helpers\HtmlPurifier::process($model->getLocalized('description')) ?>
        <p class="text-right">
            <?= Html::a(Yii::t('app','continue reading...'),['blog/view','id' => $model->id, 'subject' => str_replace(' ','_',trim($model->title))],['class'=>'text-right']); ?>
        </p>

        <hr>
    </article>

</div>
