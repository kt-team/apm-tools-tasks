<?php

namespace KT;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;

class Issues
{

    /**
     * @param array $settings
     * @return int
     * @throws JiraException
     * @throws \JsonMapper_Exception
     */
    public function getTotalOpen(array $settings)
    {
        if(!isset($settings['projects'])) {
            throw new \InvalidArgumentException('Отсутствует параметр - projects');
        }

        $projects = implode(',', $settings['projects']);

        $jql = 'project in (' . $projects . ') AND resolution is EMPTY';

        $issueService = new IssueService();
        $response = $issueService->search($jql);

        return $response->total;
    }

    /**
     * @param array $settings
     * @return int
     * @throws JiraException
     * @throws \JsonMapper_Exception
     */
    public function tasksChangedStatusInSeveralDays(array $settings)
    {
        if(!isset($settings['projects'])) {
            throw new \InvalidArgumentException('Отсутствует параметр - projects');
        }

        if(!isset($settings['days_ago'])) {
            $settings['days_ago'] = '-2d';
        }

        $projects = implode(',', $settings['projects']);

        $jql = 'project in (' . $projects . ') and resolution is not EMPTY and resolved > ' . $settings['days_ago'] . ' and resolved < startOfDay()';

        $issueService = new IssueService();
        $response = $issueService->search($jql);

        return $response->total;
    }
}