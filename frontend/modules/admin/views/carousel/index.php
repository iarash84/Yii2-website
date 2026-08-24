<?php

use yii\helpers\Html;
use frontend\widgets\Icon;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Carousel');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carousel-index">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>

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

                                    <?= Html::a(Icon::show('arrow-up'), ['/admin/carousel/up', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Move up'),
                                        'class' => 'icon-button move-up',
                                        'aria-label' => Yii::t('app', 'Move up'),
                                        'data-method' => 'post',
                                    ]); ?>

                                    <?= Html::a(Icon::show('arrow-down'), ['/admin/carousel/down', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Move down'),
                                        'class' => 'icon-button move-down',
                                        'aria-label' => Yii::t('app', 'Move down'),
                                        'data-method' => 'post',
                                    ]); ?>

                                    <?= Html::a(Icon::show('edit'), ['/admin/carousel/update', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Edit'),
                                        'class' => 'icon-button',
                                        'aria-label' => Yii::t('app', 'Edit'),
                                    ]); ?>

                                    <?= Html::a(Icon::show('delete'), ['/admin/carousel/delete', 'id' => $item->primaryKey], [
                                        'title' => Yii::t('app', 'Delete item'),
                                        'class' => 'icon-button icon-button-danger confirm-delete',
                                        'aria-label' => Yii::t('app', 'Delete item'),
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
