<?php

/* @var $this yii\web\View */
use frontend\models\Carousel as CarouselModel;
use frontend\models\Setting;
use yii\bootstrap\Carousel;
use yii\helpers\Html;

$this->title = Yii::t('app' ,'My Company');


$carousels = CarouselModel::find()->orderBy('order_num')->all();
$items = [];
foreach($carousels as $item){
    $items[] = [
        'content' => Html::a(Html::img($item->image,['style'=>"height:480px;width: 100%;"] ), $item->link),
        'caption' => '<h3>' . $item->title . '</h3>'.'<p>'.$item->text.'</p>',
        'options' => [ ],
    ];
}

?>

<?= Carousel::widget ( [
    'items' => $items,
    'options' => [
        'style' => 'width:100%;height: 480px', // set the width of the container if you like
        'dir' => 'ltr'
    ]
] );
?>


<?php $home = Setting::findOne(['type' => 'Home']); ?>
<?= $home ? \yii\helpers\HtmlPurifier::process($home->getLocalizedContent()) : $this->render('_home') ?>
