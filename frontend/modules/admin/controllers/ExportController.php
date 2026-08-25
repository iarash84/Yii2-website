<?php

namespace frontend\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ExportController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::class,'actions' => ['download' => ['post']]]];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionDownload($type)
    {
        $map = ['contacts' => 'contact_submission','orders' => 'order_submission','opportunities' => 'opportunity_submission','pages' => 'page'];
        if (!isset($map[$type])) {
            throw new \yii\web\BadRequestHttpException();
        }
        $rows = Yii::$app->db->createCommand('SELECT * FROM ' . Yii::$app->db->quoteTableName($map[$type]))->queryAll();
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, "\xEF\xBB\xBF");
        if ($rows) {
            fputcsv($stream, array_keys($rows[0]));
            foreach ($rows as $row) {
                fputcsv($stream, $row);
            }
        } rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);
        return Yii::$app->response->sendContentAsFile($content, $type . '-' . date('Ymd-His') . '.csv', ['mimeType' => 'text/csv']);
    }
}
