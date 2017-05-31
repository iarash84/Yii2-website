<?php

use kartik\icons\Icon;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Carousel');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carousel-index">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Icon::show('plusgames', [], Icon::WHHG).Html::encode($this->title) ?></h3>

        </div><!-- /.box-header -->
        <div class="box-body">

            <p>
                <?= Html::a(Yii::t('app', 'Create carousel'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php if($dataProvider->count > 0) : ?>
                <table class="table table-hover">
                    <thead>
                    <tr >
                        <th style="text-align: center" ><?= Yii::t('app', 'Image') ?></th>
                        <th width="160"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($dataProvider->models as $item) : ?>
                        <tr data-id="<?= $item->primaryKey ?>">

                            <td><?= Html::img($item->image,['style'=>"width: 550px;"]) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">

                                    <?= Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', ['/carousel/up', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Move up'),
                                        'class' => 'btn btn-default move-up',
                                    ]); ?>

                                    <?= Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', ['/carousel/down', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Move down'),
                                        'class' => 'btn btn-default move-down',
                                    ]); ?>

                                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/carousel/update', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Edit'),
                                        'class' => 'btn btn-default',
                                    ]); ?>

                                    <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/carousel/delete', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Delete item'),
                                        'class' => 'btn btn-default confirm-delete',
                                        'data' => [
                                            'confirm' => Yii::t('app' ,'Are you sure you want to delete this item ?'),
                                            'method' => 'post',
                                        ],
                                    ]); ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?= yii\widgets\LinkPager::widget([
                    'pagination' => $dataProvider->pagination
                ]) ?>
            <?php else : ?>
                <p><?= Yii::t('app', 'No records found') ?></p>
            <?php endif; ?>

        </div>
    </div>
</div>
