<?php

namespace frontend\components;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\db\IntegrityException;
use yii\web\Request;

class VisitorAnalytics implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->on(Application::EVENT_AFTER_REQUEST, function () use ($app) {
            $this->record($app);
        });
    }

    private function record($app)
    {
        $request = $app->request;
        if (!$request instanceof Request || !$request->getIsGet() || $request->getIsAjax()) {
            return;
        }
        $route = (string) ($app->controller ? $app->controller->route : '');
        $status = (int) $app->response->statusCode;
        $userAgent = (string) $request->userAgent;
        if ($route === '' || strpos($route, 'admin/') === 0 || $status >= 400 || $this->isBot($userAgent)) {
            return;
        }

        try {
            $date = gmdate('Y-m-d');
            $path = '/' . ltrim((string) parse_url($request->url, PHP_URL_PATH), '/');
            $path = mb_substr($path === '/' ? '/' : rtrim($path, '/'), 0, 500);
            $country = $this->countryCode($request);
            $secret = (string) ($request->cookieValidationKey ?: $app->id);
            $visitorHash = hash_hmac('sha256', $date . '|' . $request->userIP . '|' . $userAgent, $secret);

            $transaction = $app->db->beginTransaction();
            try {
                $this->increment('{{%visitor_daily}}', ['visit_date' => $date], $this->isUnique($date, $visitorHash, 'site', '*'));
                $this->increment('{{%visitor_country_daily}}', ['visit_date' => $date, 'country_code' => $country], $this->isUnique($date, $visitorHash, 'country', $country));
                $this->increment('{{%visitor_page_daily}}', ['visit_date' => $date, 'path' => $path], $this->isUnique($date, $visitorHash, 'page', $path));
                $transaction->commit();
                if (random_int(1, 100) === 1) {
                    $app->db->createCommand()->delete('{{%visitor_unique}}', [
                        '<', 'visit_date', gmdate('Y-m-d', strtotime('-90 days')),
                    ])->execute();
                }
            } catch (\Throwable $exception) {
                $transaction->rollBack();
                throw $exception;
            }
        } catch (\Throwable $exception) {
            Yii::warning('Visitor statistics could not be recorded: ' . $exception->getMessage(), __METHOD__);
        }
    }

    private function isUnique($date, $hash, $type, $value)
    {
        try {
            Yii::$app->db->createCommand()->insert('{{%visitor_unique}}', [
                'visit_date' => $date,
                'visitor_hash' => $hash,
                'dimension_type' => $type,
                'dimension_value' => $value,
            ])->execute();
            return true;
        } catch (IntegrityException $exception) {
            return false;
        }
    }

    private function increment($table, array $condition, $unique)
    {
        $values = ['page_views' => 1, 'visitors' => $unique ? 1 : 0];
        $updated = Yii::$app->db->createCommand()->update($table, [
            'page_views' => new \yii\db\Expression('[[page_views]] + 1'),
            'visitors' => new \yii\db\Expression('[[visitors]] + ' . ($unique ? '1' : '0')),
        ], $condition)->execute();
        if (!$updated) {
            try {
                Yii::$app->db->createCommand()->insert($table, array_merge($condition, $values))->execute();
            } catch (IntegrityException $exception) {
                Yii::$app->db->createCommand()->update($table, [
                    'page_views' => new \yii\db\Expression('[[page_views]] + 1'),
                    'visitors' => new \yii\db\Expression('[[visitors]] + ' . ($unique ? '1' : '0')),
                ], $condition)->execute();
            }
        }
    }

    private function countryCode(Request $request)
    {
        foreach (['CF-IPCountry', 'X-Country-Code'] as $header) {
            $code = strtoupper(trim((string) $request->headers->get($header)));
            if (preg_match('/^[A-Z]{2}$/', $code)) {
                return $code;
            }
        }
        return 'ZZ';
    }

    private function isBot($userAgent)
    {
        return $userAgent === '' || preg_match('/bot|crawl|spider|slurp|preview|monitor/i', $userAgent) === 1;
    }
}
