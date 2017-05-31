<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class HtmlForm extends Model
{
    public $pageName;
    public $pageContent;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pageName', 'pageContent'], 'string'],
            [['pageName', 'pageContent'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pageName' => Yii::t('app', 'Page Name'),
            'pageContent' => Yii::t('app', 'Page Content'),
        ];
    }
}

