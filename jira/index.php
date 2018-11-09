<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

// APM_JIRA_DEBUG - При дебаге не отправляет значения, а выводит их

// Загружаем .env
$dotEnv = new Dotenv\Dotenv(__DIR__);
$dotEnv->load();

const APM_JIRA_BASE_PATH = __DIR__;


/** @var \KT\Apm $apmClass */
$apmClass = new \KT\Apm();

/** @var \KT\Issues $issuesClass */
$issuesClass = new \KT\Issues();

try {

    $apmClass->_log('Старт Jira таски');

    // Открытые задачи в проекте
    $total = $issuesClass->getTotalOpen($config['total_tasks']['settings']);
    $apmClass->_log('1. Получили список открытых задач');
    $apmClass->send($total, $config['total_tasks']['apm']);
    $apmClass->_log('1. Отправили');


    // Закрытые задачи за вчерашний день
    $closed = $issuesClass->tasksChangedStatusInSeveralDays($config['closed_tasks']['settings']);
    $apmClass->_log('2. Получили список закрытых задач за вчера');
    $apmClass->send($closed, $config['closed_tasks']['apm']);
    $apmClass->_log('2. Отправили');


    echo 'Done' . PHP_EOL;

} catch (\Exception $e) {
    $apmClass->_log('Error: ' . $e->getMessage());
    echo $e->getMessage();
}