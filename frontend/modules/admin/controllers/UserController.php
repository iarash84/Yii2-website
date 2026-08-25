<?php

namespace frontend\modules\admin\controllers;

use common\models\Log;
use frontend\models\ChangePasswordForm;
use frontend\models\SignupForm;
use Yii;
use common\models\User;
use frontend\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $model = new SignupForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signup()) {
                    return $this->redirect(['/admin/users']);
                }
            }

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $this->guardLastSuperAdmin($model->id, $model->role);

                $userModel = User::find()->where(['id' => $model->id])->one();
                $userModel->username = $model->username;
                $userModel->email = $model->email;
                if(!empty($model->password)){
                    $userModel->setPassword($model->password);
                    $userModel->generateAuthKey();
                }

                if ($userModel->save()) {
                    $auth = Yii::$app->authManager;
                    $auth->revokeAll($model->id);
                    $auth->assign($auth->getRole($model->role), $model->id);
                }


                return $this->redirect(['user/index']);
            } else {
                $roles = Yii::$app->authManager->getRolesByUser($model->id);
                $model->role = empty($roles) ? 'editor' : array_keys($roles)[0];

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionChange()
    {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::find()->where(['id' => Yii::$app->user->identity->getId()])->one();
            $user->setPassword($model->newPassword);
            $user->generateAuthKey();
            $user->save();
//            Yii::$app->session->setFlash('success', Yii::t('app','Your password changes successfully'));
            return $this->redirect(['/changepass']);
        } else {
            return $this->render('changePassword', [
                'model' => $model,
            ]);
        }
    }

    public function actionLog(){
        $dataProvider = new ActiveDataProvider([
            'query' => Log::find()->orderBy(['created_at' => SORT_DESC]),
        ]);

        return $this->render('log', [
            'dataProvider' => $dataProvider
        ]);
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
            $model = $this->findModel($id);
            $this->guardLastSuperAdmin($model->id, null);
            Yii::$app->authManager->revokeAll($model->id);
            $model->delete();

            return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function guardLastSuperAdmin($userId, $newRole)
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($userId);
        if (isset($roles['superAdmin']) && $newRole !== 'superAdmin'
            && count($auth->getUserIdsByRole('superAdmin')) <= 1) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('app', 'The last super administrator cannot be removed or demoted.')
            );
        }
    }
}
