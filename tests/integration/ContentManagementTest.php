<?php

namespace tests\integration;

use frontend\models\Blog;
use frontend\models\Category;
use tests\Support\DatabaseTestCase;

class ContentManagementTest extends DatabaseTestCase
{
    public function testContentCanBeCreatedAndUpdated(): void
    {
        $user = $this->createUser('editor');
        $category = new Category(['user_id' => $user->id, 'title' => 'دسته آزمایشی']);
        self::assertTrue($category->save(), json_encode($category->errors));

        $post = new Blog([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'عنوان اولیه',
            'description' => '<p>خلاصه</p>',
            'content' => '<p>محتوای اولیه</p>',
        ]);
        self::assertTrue($post->save(), json_encode($post->errors));

        $post->title = 'عنوان ویرایش‌شده';
        $post->content = '<p>محتوای ویرایش‌شده</p>';
        self::assertTrue($post->save());
        $reloaded = Blog::findOne($post->id);
        self::assertSame('عنوان ویرایش‌شده', $reloaded->title);
        self::assertStringContainsString('ویرایش‌شده', $reloaded->content);
    }
}
