<?php
use yii\helpers\Html;

?>
<div class="blog-post">
    <article>
<!--        <h5> --><?php //= Html::a(Html::encode($model->title) ,['blog/'.$model->id]) ?><!--</h5>-->
        <h5> <?= Html::a(Html::encode($model->title) ,['blog/view','id' => $model->id, 'subject' => str_replace(' ','_',trim($model->title))]) ?></h5>

        <div class="row">
            <div class="group1 col-sm-6 col-md-6">
                <span class="glyphicon glyphicon-folder-open" style="margin-left: 5px"></span><?= $model->user->username ?></a>

            </div>
            <div class="group2 col-sm-6 col-md-6">
                <span class="glyphicon glyphicon-time"></span> <?= $model->createDatetime ?>
            </div>
        </div>
        <hr>
        <?= $model->description ?>
        <p class="text-right">
            <?= Html::a(Yii::t('app','continue reading...'),['blog/view','id' => $model->id, 'subject' => str_replace(' ','_',trim($model->title))],['class'=>'text-right']); ?>
        </p>

        <hr>
    </article>

</div>
