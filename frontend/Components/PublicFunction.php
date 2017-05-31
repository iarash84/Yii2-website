<?php
namespace app\components;


use Yii;
use yii\base\Component;


/**
 * Class PublicFunction
 * @package app\components
 */
class PublicFunction extends Component
{
    public function AddHttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }


}
