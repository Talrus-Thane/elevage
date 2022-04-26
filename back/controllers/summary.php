<?php

namespace AcyMailing\Controllers;

use AcyMailing\Classes\ColorClass;
use AcyMailing\Classes\MountClass;
use AcyMailing\Libraries\acymController;

class SummaryController extends acymController
{
    private $mounts;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumb[acym_translation('ACYM_SUMMARY')] = acym_completeLink('summary');
    }

    public function listing()
    {
        acym_setVar('layout', 'listing');

        $data = [];
        $data['mountsLink'] = acym_completeLink('mounts');
        $data['purity_levels'] = [
            1 => acym_translation('ACYM_NONE'),
            2 => acym_translation('P1'),
            3 => acym_translation('P2'),
            4 => acym_translation('ACYM_PURE'),
        ];

        $colorClass = new ColorClass();
        $data['colors'] = $colorClass->getAll();
        $mountClass = new MountClass();
        $this->mounts = $mountClass->getAll();

        $this->prepareMounts($data);

        parent::display($data);
    }

    public function prepareMounts(&$data)
    {
        $data['mounts'] = [];
        /*
         * -1 => move left
         * 1 => move right
         * 0 => don't move
         */
        uasort($this->mounts, function ($a, $b) use ($data) {
            $aColorName = $data['colors'][$a->color_id]->name;
            $bColorName = $data['colors'][$b->color_id]->name;
            if (strpos($aColorName, ' ') === false && strpos($bColorName, ' ') !== false) return -1;
            if (strpos($aColorName, ' ') !== false && strpos($bColorName, ' ') === false) return 1;

            return $aColorName < $bColorName ? -1 : 1;
        });
        foreach ($this->mounts as $oneMount) {
            if ($oneMount->max_reproductions === $oneMount->reproductions_counter) continue;
            if (empty($data['mounts'][$oneMount->color_id])) {
                $data['mounts'][$oneMount->color_id] = [
                    0 => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                        4 => 0,
                    ],
                    1 => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                        4 => 0,
                    ],
                ];
            }

            $data['mounts'][$oneMount->color_id][$oneMount->gender][$oneMount->purity_level]++;
        }
    }
}
