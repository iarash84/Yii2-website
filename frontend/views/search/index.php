<?php
use frontend\widgets\Icon;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
$this->title = Yii::t('app', 'Search'); $this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page">
    <div class="page-header"><p class="text-overline"><?= Yii::t('app', 'Site search') ?></p><h1><?= Html::encode($this->title) ?></h1></div>
    <?= Html::beginForm(['/search/index'], 'get', ['class' => 'card search-form']) ?>
    <label for="site-search-query"><?= Yii::t('app', 'Search phrase') ?></label>
    <div class="search-input-row"><?= Html::textInput('q', $query, ['id' => 'site-search-query', 'class' => 'form-control', 'minlength' => 2, 'maxlength' => 100, 'required' => true]) ?><?= Html::submitButton(Icon::show('posts') . Yii::t('app', 'Search'), ['class' => 'btn']) ?></div>
    <?= Html::endForm() ?>
    <?php if ($query !== ''): ?>
        <p class="search-count"><?= Yii::t('app', '{count} results found.', ['count' => $dataProvider->totalCount]) ?></p>
        <div class="search-results">
            <?php foreach ($dataProvider->models as $result): ?>
                <article class="card search-result"><p class="text-overline"><?= Yii::t('app', $result['type'] === 'page' ? 'Page' : 'Blog') ?></p><h2><?= Html::a(Html::encode($result['title']), $result['url']) ?></h2><div class="text-muted"><?= HtmlPurifier::process(mb_substr(strip_tags((string) $result['summary']), 0, 240)) ?></div></article>
            <?php endforeach; ?>
            <?php if (!$dataProvider->models): ?><div class="card empty-state"><h2><?= Yii::t('app', 'No results found') ?></h2><p><?= Yii::t('app', 'Try a different search phrase.') ?></p></div><?php endif; ?>
        </div>
        <?= yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
    <?php endif; ?>
</div>
