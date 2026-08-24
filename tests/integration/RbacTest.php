<?php

namespace tests\integration;

use tests\Support\DatabaseTestCase;
use Yii;

class RbacTest extends DatabaseTestCase
{
    public function testRoleHierarchyAndPermissions(): void
    {
        $editor = $this->createUser('editor');
        $admin = $this->createUser('admin');
        $superAdmin = $this->createUser('superAdmin');
        $auth = Yii::$app->authManager;

        self::assertTrue($auth->checkAccess($editor->id, 'accessAdmin'));
        self::assertTrue($auth->checkAccess($editor->id, 'manageContent'));
        self::assertFalse($auth->checkAccess($editor->id, 'manageSettings'));
        self::assertFalse($auth->checkAccess($editor->id, 'manageUsers'));

        self::assertTrue($auth->checkAccess($admin->id, 'manageContent'));
        self::assertTrue($auth->checkAccess($admin->id, 'manageSettings'));
        self::assertTrue($auth->checkAccess($admin->id, 'viewSubmissions'));
        self::assertFalse($auth->checkAccess($admin->id, 'manageUsers'));

        self::assertTrue($auth->checkAccess($superAdmin->id, 'manageUsers'));
        self::assertTrue($auth->checkAccess($superAdmin->id, 'manageSettings'));
    }
}
