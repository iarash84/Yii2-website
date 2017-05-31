<?php

namespace frontend\controllers;

use common\models\Log;
use frontend\models\AuthAssignment;
use frontend\models\ChangePasswordForm;
use frontend\models\SignupForm;
use Yii;
use common\models\User;
use frontend\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'delete' ,'change'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
        if (Yii::$app->user->can('usersManagement')) {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $model = new SignupForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signup()) {
                    return $this->redirect(['/users']);
                }
            }

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        } else {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app','Forbidden Http Exception'));
        }
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('update.user')) {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $userModel = User::find()->where(['id' => $model->id])->one();
                $userModel->username = $model->username;
                $userModel->email = $model->email;
                if(!empty($model->password)){
                    $userModel->password_hash = Yii::$app->security->generatePasswordHash($model->password);
                }

                $userModel->save();
                AuthAssignment::deleteAll(['user_id' => $model->id]);
                if($model->isSuperAdmin) {
                    $authAssignment = new AuthAssignment();
                    $authAssignment->user_id = $model->id;
                    $authAssignment->item_name = "superAdmin";
                    $authAssignment->save();
                }


                return $this->redirect(['user/index']);
            } else {
                $temp = AuthAssignment::find()->where(['item_name'=> 'superAdmin','user_id'=>$model->id])->count();
                if($temp > 0)
                    $model->isSuperAdmin = true;
                else
                    $model->isSuperAdmin = false;

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else{
            throw new \yii\web\ForbiddenHttpException(Yii::t('app','Forbidden Http Exception'));
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
            $user->password_hash = Yii::$app->security->generatePasswordHash($model->newPassword);
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
            'query' => Log::find()->orderBy(['createDateTime' => SORT_DESC]),
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
        if (Yii::$app->user->can('delete.user')) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app','Forbidden Http Exception'));
        }
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
}
