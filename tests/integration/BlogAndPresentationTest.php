<?php

namespace tests\integration;

use frontend\components\LocalizedFormatter;
use frontend\models\Blog;
use frontend\models\BlogSearch;
use frontend\models\Category;
use frontend\models\SystemSetting;
use tests\Support\DatabaseTestCase;

class BlogAndPresentationTest extends DatabaseTestCase
{
    public function testPostsAreSortedNewestFirstAndCanBeFilteredByHashtag(): void
    {
        $user = $this->createUser();
        $category = new Category(['user_id' => $user->id, 'title' => 'Test']);
        self::assertTrue($category->save());
        $old = new Blog(['user_id' => $user->id, 'category_id' => $category->id, 'title' => 'Old', 'created_at' => '2025-01-01 10:00:00']);
        $new = new Blog(['user_id' => $user->id, 'category_id' => $category->id, 'title' => 'New', 'created_at' => '2026-01-01 10:00:00']);
        self::assertTrue($old->save());
        self::assertTrue($new->save());
        self::assertTrue($new->syncTags('#yii #php'));

        $all = (new BlogSearch())->search([])->getModels();
        self::assertSame($new->id, $all[0]->id);
        $tagged = (new BlogSearch())->search(['BlogSearch' => ['tag' => 'yii']])->getModels();
        self::assertCount(1, $tagged);
        self::assertSame($new->id, $tagged[0]->id);
    }

    public function testJalaliCalendarSettingIsAppliedByGlobalFormatter(): void
    {
        self::assertTrue(SystemSetting::put('date_calendar', 'jalali'));
        $formatter = new LocalizedFormatter();
        self::assertSame('1403/01/01', $formatter->asDate('2024-03-20'));
        self::assertStringStartsWith('1403/01/01 ', $formatter->asDatetime('2024-03-20 12:30:00'));
    }
}
