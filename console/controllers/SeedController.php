<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class SeedController extends Controller
{
    public function actionIndex($demo = true)
    {
        $password = (string) getenv('ADMIN_PASSWORD');
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{12,72}$/', $password)) {
            $this->stderr("ADMIN_PASSWORD must satisfy the project password policy.\n");
            return ExitCode::CONFIG;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = $this->seedAdmin($password);
            $this->seedSettings($user->id);
            if (filter_var($demo, FILTER_VALIDATE_BOOLEAN)) {
                $this->seedDemoContent($user->id);
            }
            $transaction->commit();
            $this->stdout("Seed data installed successfully.\n");
            return ExitCode::OK;
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            $this->stderr($exception->getMessage() . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    private function seedAdmin($password)
    {
        $username = getenv('ADMIN_USERNAME') ?: 'admin';
        $email = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
        $user = User::findOne(['username' => $username]) ?: new User();
        $user->username = $username;
        $user->email = $email;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        if (!$user->save()) {
            throw new \RuntimeException(implode(' ', $user->getFirstErrors()));
        }
        $role = Yii::$app->authManager->getRole('superAdmin');
        if ($role === null) {
            throw new \RuntimeException('RBAC migration has not been applied.');
        }
        Yii::$app->authManager->revokeAll($user->id);
        Yii::$app->authManager->assign($role, $user->id);
        return $user;
    }

    private function seedSettings($userId)
    {
        $settings = [
            'CompanyName' => 'وب‌سایت من',
            'Address' => 'نشانی شرکت',
            'Email' => getenv('ADMIN_EMAIL') ?: 'admin@example.com',
            'PhoneNumber' => '',
            'FaxNumber' => '',
            'PostalCode' => '',
            'WorkingHours' => 'شنبه تا چهارشنبه، ۸ تا ۱۷',
            'About' => '<p>متن معرفی مجموعه را از پنل مدیریت ویرایش کنید.</p>',
            'Opportunity' => '<p>برای همکاری با ما فرم زیر را تکمیل کنید.</p>',
        ];
        foreach ($settings as $type => $content) {
            $exists = Yii::$app->db->createCommand(
                'SELECT id FROM {{%tbl_setting}} WHERE type=:type LIMIT 1',
                [':type' => $type]
            )->queryScalar();
            $command = Yii::$app->db->createCommand();
            if ($exists) {
                $command->update('tbl_setting', ['user_id' => $userId, 'content' => $content], ['id' => $exists])->execute();
            } else {
                $command->insert('tbl_setting', ['user_id' => $userId, 'type' => $type, 'content' => $content])->execute();
            }
        }
    }

    private function seedDemoContent($userId)
    {
        $categoryId = Yii::$app->db->createCommand(
            'SELECT id FROM {{%tbl_blog_category}} WHERE title=:title LIMIT 1',
            [':title' => 'اخبار']
        )->queryScalar();
        if (!$categoryId) {
            Yii::$app->db->createCommand()->insert('tbl_blog_category', [
                'user_id' => $userId,
                'title' => 'اخبار',
            ])->execute();
            $categoryId = Yii::$app->db->getLastInsertID();
        }
        if (
            $categoryId && !Yii::$app->db->createCommand(
                'SELECT 1 FROM {{%tbl_blog_post}} WHERE title=:title LIMIT 1',
                [':title' => 'شروع به کار وب‌سایت']
            )->queryScalar()
        ) {
            Yii::$app->db->createCommand()->insert('tbl_blog_post', [
                'user_id' => $userId,
                'category_id' => $categoryId,
                'title' => 'شروع به کار وب‌سایت',
                'description' => '<p>این نوشته نمونه است و می‌توانید آن را حذف یا ویرایش کنید.</p>',
                'content' => '<p>نصب وب‌سایت با موفقیت انجام شد.</p>',
                'keyWord' => 'نمونه,نصب',
            ])->execute();
        }
    }
}
