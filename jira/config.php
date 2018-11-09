<?php

// Лицензионный ключ полученный в сервисе APMinvest
$config['license_key'] = 'xxxx';

// Настройки для получение количества открытых задач в проектах (всего)
$config['total_tasks'] = [
    'settings' => [
        // Список проектов
        'projects' => ['PROJECT_NAME_1', 'PROJECT_NAME_2']
    ],

    // Настройки для отправки в APMinvest
    'apm' => [
        'license_key' => $config['license_key'],
        'metric_code' => 'jira-total',                  // Код метрики
        'metric_label' => 'Открытые задачи (всего)'     // Лейбл метрики
    ]
];

// Настройки для получение количества закрытых задач в проектах (за 2 дня)
$config['closed_tasks'] = [
    'settings' => [
        // Список проектов при получение количества открытых задач в проектах (всего)
        'projects' => ['PROJECT_NAME_1', 'PROJECT_NAME_2']
        // days_ago - Необязательный параметр. Количество дней назад. По умолчание: -2d (sting)
    ],

    // Настройки для отправки в APMinvest
    'apm' => [
        'license_key' => $config['license_key'],
        'metric_code' => 'jira-closed',                 // Код метрики
        'metric_label' => 'Закрытые задачи за вчера'    // Лейбл метрики
    ]
];

return $config;