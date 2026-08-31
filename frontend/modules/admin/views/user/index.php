<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\widgets\AdminActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php  echo $this->render('signup', ['model' => $model]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'striped responsive-table' ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'username'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'email'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDatetime($data->created_at);
                },
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'created_at',
            ],
            [
                'label' => Yii::t('app', 'Role'),
                'format' => 'raw',
                'value' => static function ($data) {
                    $labels = ['superAdmin' => Yii::t('app', 'Super Admin'), 'admin' => Yii::t('app', 'Admin'), 'editor' => Yii::t('app', 'Editor')];
                    $roles = array_keys(Yii::$app->authManager->getRolesByUser($data->id));
                    return implode(' ', array_map(static fn ($role) => Html::tag('span', Html::encode($labels[$role] ?? $role), ['class' => 'status-pill']), $roles));
                },
            ],
            [
                'class' => AdminActionColumn::class,
                'template' => '{update} {delete}'
            ]
        ],
    ]); ?>
    <br /><br />
</div>
