<?php

namespace frontend\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class RichTextEditor extends InputWidget
{
    public $rows = 10;

    public function run()
    {
        $options = array_merge($this->options, [
            'data-rich-editor' => true,
            'rows' => $this->rows,
            'class' => trim(($this->options['class'] ?? 'form-control') . ' rich-text-source'),
        ]);
        return $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $options)
            : Html::textarea($this->name, $this->value, $options);
    }
}
