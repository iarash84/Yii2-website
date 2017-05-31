<?php
use yii\helpers\Html;

?>
<div class="site-sample">
    <?php
    $counter = 0;
    foreach ($dataProvider->models as $model) :
        ?>
        <?php if(($counter % 3) == 0) : ?>
        <div class="row" >
    <?php endif; ?>
        <div class="col s8 m4">
            <div class="card">

                <?php if(!Yii::$app->user->isGuest) : ?>
                    <div class="card-action" style="text-align: center">
                        <div class="btn-group btn-group-sm btn-group-justified " role="group" dir="ltr">

                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/sample/update', 'id' => $model->id], [
                                'title' => Yii::t('app', 'Edit'),
                                'class' => 'btn btn-default blue darken-2',
                            ]); ?>

                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/sample/delete', 'id' => $model->id], [
                                'title' => Yii::t('app', 'Delete item'),
                                'class' => 'btn btn-default confirm-delete red accent-2',
                                'data' => [
                                    'confirm' => Yii::t('app' ,'Are you sure you want to delete this item ?'),
                                    'method' => 'post',
                                ],
                            ]); ?>

                        </div>
                    </div>
                <?php endif; ?>


                <div class="card-image">
                    <?= Html::img($model->image) ?>
                </div>
                <div class="card-content">
                    <b ><?= $model->title ?></b>

                    <p style="text-align: justify;">
                        <?= $model->content ?>
                    </p>
                </div>
                <div class="card-action">
                    <a href="<?= $model->url_link ?>"><?= $model->url_display_name ?></a>
                </div>
            </div>
        </div>
        <?php
        $counter++;
        if($counter % 3 == 0) : ?>
            </div>
            <?php
        endif;
    endforeach;
    ?>
</div>