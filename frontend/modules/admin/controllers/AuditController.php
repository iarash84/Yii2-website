<?php

namespace frontend\modules\admin\controllers;

use frontend\models\AdminAudit;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class AuditController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => AdminAudit::find()->with('user')->orderBy(['created_at' => SORT_DESC])])]);
    }
}
