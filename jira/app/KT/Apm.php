<?php

namespace KT;

class Apm
{
    /**
     * @param $metric
     * @param array $settings
     */
    public function send($metric, $settings = [])
    {

        if(getenv('APM_JIRA_DEBUG')) {
            $this->_log('debug - ' . $metric);
            echo $metric . PHP_EOL;
            return;
        }

        if(!isset($settings['metric_code'])) {
            throw new \InvalidArgumentException('Не указан тип стата');
        }

        if(!isset($settings['license_key'])) {
            throw new \InvalidArgumentException('Не указан лицензионный ключ с сервиса APMinvest');
        }

        $url = 'https://service.apminvest.com/metrics/stat/' . $settings['metric_code'];

        $params = [];
        $params['metrics'] = $metric;
        $params['key'] = $settings['license_key'];

        if(isset($settings['date'])) {
            $params['date'] = $settings['date'];
        }

        if(isset($settings['metric_label'])) {
            $params['label'] = $settings['metric_label'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        if($info['http_code'] != 200 || !$output) {
            $res = json_decode($output, true);
            if(!empty($res['message'])) {
                throw new \RuntimeException($res['message']);
            }
            throw new \RuntimeException('К сожалению, не удалось отправить метрики в APMinvest. Проверьте, пожалуйста, параметры или обратитесь в тех. поддержку.');
        }
    }

    /**
     * @param $msg
     */
    public function _log($msg)
    {
        if(!getenv('APM_JIRA_LOG')) {
            return;
        }

        $logFilePath = $logFilePath = APM_JIRA_BASE_PATH . '/log.log';
        $date = date('Y-m-d H:i:s');
        $msg = $date . ' - ' . $msg . PHP_EOL;
        if(!file_put_contents($logFilePath, $msg, FILE_APPEND)) {
            echo $date . ' - Error file log';
        }
    }
}