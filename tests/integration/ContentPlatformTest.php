<?php

namespace tests\integration;

use frontend\models\Page;
use tests\Support\DatabaseTestCase;
use Yii;
use yii\web\Request;

class ContentPlatformTest extends DatabaseTestCase
{
    public function testEditorHasPageAndMediaPermissions(): void
    {
        $editor = $this->createUser('editor');
        self::assertTrue(Yii::$app->authManager->checkAccess($editor->id, 'managePages'));
        self::assertTrue(Yii::$app->authManager->checkAccess($editor->id, 'manageMedia'));
    }

    public function testDraftScheduledAndPublishedScopes(): void
    {
        $draft = $this->createPage('draft-page', Page::STATUS_DRAFT);
        $future = $this->createPage('future-page', Page::STATUS_SCHEDULED, time() + 3600);
        $scheduled = $this->createPage('scheduled-page', Page::STATUS_SCHEDULED, time() - 60);
        $published = $this->createPage('published-page', Page::STATUS_PUBLISHED);

        $ids = Page::published()->select('id')->column();
        self::assertNotContains($draft->id, $ids);
        self::assertNotContains($future->id, $ids);
        self::assertContains($scheduled->id, $ids);
        self::assertContains($published->id, $ids);
    }

    public function testPageTranslationSeoAndPublicRendering(): void
    {
        $page = $this->createPage('services', Page::STATUS_PUBLISHED);
        $page->seo_title = 'SEO title';
        $page->seo_description = 'SEO description';
        self::assertTrue($page->save());
        self::assertTrue($page->saveTranslations(['en' => ['title' => 'Services', 'slug' => 'services-en']]));

        Yii::$app->languageManager->activate('en');
        Yii::$app->response->clear();
        $output = Yii::$app->runAction('page/view', ['slug' => 'services-en']);
        self::assertStringContainsString('Services', $output);
        self::assertStringContainsString('SEO description', $output);
    }

    public function testSearchExcludesDraftPages(): void
    {
        $this->createPage('hidden-result', Page::STATUS_DRAFT, null, 'UniqueNeedle');
        $this->createPage('visible-result', Page::STATUS_PUBLISHED, null, 'UniqueNeedle');
        Yii::$app->response->clear();
        $output = Yii::$app->runAction('search/index', ['q' => 'UniqueNeedle']);
        self::assertStringContainsString('visible-result', $output);
        self::assertStringNotContainsString('hidden-result', $output);
    }

    public function testReservedAndDuplicateTranslatedSlugsAreRejected(): void
    {
        $reserved = new Page(['title' => 'Reserved', 'slug' => 'contact', 'status' => Page::STATUS_DRAFT, 'robots' => 'index,follow']);
        self::assertFalse($reserved->validate());

        $first = $this->createPage('first-page', Page::STATUS_DRAFT);
        $second = $this->createPage('second-page', Page::STATUS_DRAFT);
        self::assertTrue($first->saveTranslations(['en' => ['slug' => 'shared-slug']]));
        self::assertFalse($second->saveTranslations(['en' => ['slug' => 'shared-slug']]));
    }

    public function testFixedRoutesTakePriorityOverDynamicPageSlug(): void
    {
        $request = new Request(['scriptUrl' => '/index.php']);
        $request->setUrl('/fa/contact');
        [$route] = Yii::$app->urlManager->parseRequest($request);
        self::assertSame('site/contact', $route);

        $request = new Request(['scriptUrl' => '/index.php']);
        $request->setUrl('/fa/custom-page');
        [$route, $params] = Yii::$app->urlManager->parseRequest($request);
        self::assertSame('page/view', $route);
        self::assertSame('custom-page', $params['slug']);
    }

    private function createPage(string $slug, string $status, ?int $publishAt = null, ?string $title = null): Page
    {
        $page = new Page([
            'title' => $title ?: $slug,
            'slug' => $slug,
            'content' => '<p>Content</p>',
            'status' => $status,
            'publish_at' => $publishAt,
            'robots' => 'index,follow',
        ]);
        self::assertTrue($page->save(), json_encode($page->errors));
        return $page;
    }
}
