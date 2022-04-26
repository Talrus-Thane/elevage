<?php

namespace AcyMailing\Controllers;

use AcyMailing\Classes\ColorClass;
use AcyMailing\Classes\MountClass;
use AcyMailing\Libraries\acymController;

class TinderController extends acymController
{
    private $mounts;
    private $colors;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumb[acym_translation('ACYM_TINDER')] = acym_completeLink('tinder');
    }

    public function listing()
    {
        acym_setVar('layout', 'listing');

        $data = [];

        $colorClass = new ColorClass();
        $this->colors = $colorClass->getAll();
        $mountClass = new MountClass();
        $this->mounts = $mountClass->getAll();

        $this->prepareNextDates($data);

        parent::display($data);
    }

    public function prepareNextDates(&$data)
    {
        $this->prepareAncestors();

        $relations = [];
        foreach ($this->mounts as $oneMount) {
            if (!empty($oneMount->mother_id) && !empty($oneMount->father_id)) {
                $motherColor = $this->mounts[$oneMount->mother_id]->color_id;
                $fatherColor = $this->mounts[$oneMount->father_id]->color_id;

                if ($motherColor === $fatherColor && $motherColor === $oneMount->color_id) {
                    $relations[$oneMount->mother_id][] = $oneMount->father_id;
                    $relations[$oneMount->father_id][] = $oneMount->mother_id;
                }
            }

            //if (!empty($oneMount->last_partner_id)) {
            //    $relations[$oneMount->id][] = $oneMount->last_partner_id;
            //}
        }

        $colorsNotMated = [];
        $data['possibleMates'] = [];
        $lastHour = time() - 3600;
        $lastDay = time() - 86400;
        foreach ($this->mounts as $oneMount) {
            if ($oneMount->color_id != 121) continue;
            if ($oneMount->purity_level != 4) continue;
            if (empty($oneMount->gender)) continue;
            if ($oneMount->creation_date > $lastDay) continue;
            if ($oneMount->last_mating > $lastHour) continue;

            foreach ($this->mounts as $oneSuitor) {
                if ($oneSuitor->creation_date > $lastDay) continue;
                if ($oneSuitor->last_mating > $lastHour) continue;
                if ($oneMount->gender === $oneSuitor->gender) continue;
                if ($oneMount->color_id !== $oneSuitor->color_id) continue;
                if ($oneMount->reproductions_counter === $oneMount->max_reproductions) continue;
                if ($oneSuitor->reproductions_counter === $oneSuitor->max_reproductions) continue;
                if ($oneMount->purity_level !== $oneSuitor->purity_level) continue;

                $commonAncestorsCount = count(array_intersect($oneMount->ancestors, $oneSuitor->ancestors));

                //if ($commonAncestorsCount > 2) continue;


                $hasMated = !empty($relations[$oneMount->id]) && in_array($oneSuitor->id, $relations[$oneMount->id]);
                $data['possibleMates'][] = [
                    'mount' => $oneMount,
                    'suitor' => $oneSuitor,
                    'commonAncestors' => $commonAncestorsCount,
                    'color' => $this->colors[$oneMount->color_id],
                    'hasMated' => $hasMated,
                ];

                if (!$hasMated) {
                    $colorsNotMated[] = $this->colors[$oneMount->color_id];
                }
            }
        }

        foreach ($data['possibleMates'] as $key => $onePossibleMate) {
            if (!in_array($onePossibleMate['color'], $colorsNotMated)) {
                //unset($data['possibleMates'][$key]);
            }
        }

        /*
         * -1 => move left
         * 1 => move right
         * 0 => don't move
         */
        uasort($data['possibleMates'], function ($a, $b) {
            if ($a['mount']->color_id < $b['mount']->color_id) return -1;
            if ($a['mount']->color_id > $b['mount']->color_id) return 1;

            if ($a['commonAncestors'] < $b['commonAncestors']) return -1;
            if ($a['commonAncestors'] > $b['commonAncestors']) return 1;

            if ($a['mount']->purity_level < $b['mount']->purity_level) return -1;
            if ($a['mount']->purity_level > $b['mount']->purity_level) return 1;

            if (!$a['hasMated'] && $b['hasMated']) return -1;
            if ($a['hasMated'] == $b['hasMated']) return 0;

            return 1;
        });
        //uasort($data['possibleMates'], function ($a, $b) {
        //    if ($a['commonAncestors'] < $b['commonAncestors']) return -1;
        //    if ($a['commonAncestors'] == $b['commonAncestors']) {
        //        if ($a['mount']->purity_level < $b['mount']->purity_level) return -1;
        //        if ($a['mount']->purity_level == $b['mount']->purity_level) {
        //            if ($a['mount']->color_id < $b['mount']->color_id) return -1;
        //            if ($a['mount']->color_id == $b['mount']->color_id) {
        //                if (!$a['hasMated'] && $b['hasMated']) return -1;
        //                if ($a['hasMated'] == $b['hasMated']) return 0;
        //            }
        //        }
        //    }
        //
        //    return 1;
        //});

        $data['purity_levels'] = [
            '1' => ['label' => acym_translation('ACYM_NONE'), 'color' => 'red'],
            '2' => ['label' => acym_translation('P1'), 'color' => 'darkorange'],
            '3' => ['label' => acym_translation('P2'), 'color' => 'cornflowerblue'],
            '4' => ['label' => acym_translation('ACYM_PURE'), 'color' => 'green'],
        ];
    }

    private function prepareAncestors()
    {
        foreach ($this->mounts as $oneMount) {
            $oneMount->ancestors = [$oneMount->id];
            $this->addAncestors($oneMount->ancestors, $oneMount);
            $oneMount->ancestors = array_unique($oneMount->ancestors);
        }
    }

    public function addAncestors(&$ancestors, $mount, $level = 0)
    {
        if (!empty($mount->mother_id)) {
            $ancestors[] = $mount->mother_id;
        }
        if (!empty($mount->father_id)) {
            $ancestors[] = $mount->father_id;
        }

        if ($level === 1) return;

        if (!empty($this->mounts[$mount->mother_id])) {
            $this->addAncestors($ancestors, $this->mounts[$mount->mother_id], $level + 1);
        }

        if (!empty($this->mounts[$mount->father_id])) {
            $this->addAncestors($ancestors, $this->mounts[$mount->father_id], $level + 1);
        }
    }

    public function mate()
    {
        $mountClass = new MountClass();
        $mountClass->mate([acym_getVar('int', 'father_id'), acym_getVar('int', 'mother_id')]);

        $this->listing();
    }
}
