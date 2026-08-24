<?php

use common\models\User;
use yii\db\Migration;

class m260823_000001_create_security_rbac extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Older releases stored superAdmin as a permission. Converting its type
        // preserves existing assignments while making the RBAC hierarchy valid.
        $this->update('{{%auth_item}}', ['type' => 1], ['name' => 'superAdmin']);
        $auth->invalidateCache();

        $permissions = [
            'accessAdmin' => 'ورود به پنل مدیریت',
            'manageContent' => 'مدیریت محتوای سایت',
            'manageSettings' => 'مدیریت تنظیمات سایت',
            'viewSubmissions' => 'مشاهده و مدیریت درخواست‌های کاربران',
            'manageUsers' => 'مدیریت کاربران و نقش‌ها',
        ];
        foreach ($permissions as $name => $description) {
            if ($auth->getPermission($name) === null) {
                $permission = $auth->createPermission($name);
                $permission->description = $description;
                $auth->add($permission);
            }
        }

        foreach (['editor', 'admin', 'superAdmin'] as $name) {
            if ($auth->getRole($name) === null) {
                $auth->add($auth->createRole($name));
            }
        }

        $this->addChild($auth, 'editor', 'accessAdmin');
        $this->addChild($auth, 'editor', 'manageContent');
        $this->addChild($auth, 'admin', 'editor');
        $this->addChild($auth, 'admin', 'manageSettings');
        $this->addChild($auth, 'admin', 'viewSubmissions');
        $this->addChild($auth, 'superAdmin', 'admin');
        $this->addChild($auth, 'superAdmin', 'manageUsers');

        if (empty($auth->getUserIdsByRole('superAdmin'))) {
            $firstUser = User::find()->where(['status' => User::STATUS_ACTIVE])->orderBy(['id' => SORT_ASC])->one();
            if ($firstUser !== null) {
                $auth->assign($auth->getRole('superAdmin'), $firstUser->id);
            }
        }
    }

    public function safeDown()
    {
        echo "m260823_000001_create_security_rbac cannot be safely reverted.\n";
        return false;
    }

    private function addChild($auth, $parentName, $childName)
    {
        $parent = $auth->getRole($parentName);
        $child = $auth->getRole($childName) ?: $auth->getPermission($childName);
        if (!$auth->hasChild($parent, $child)) {
            $auth->addChild($parent, $child);
        }
    }
}
