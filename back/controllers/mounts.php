<?php

namespace AcyMailing\Controllers;

use AcyMailing\Classes\ColorClass;
use AcyMailing\Classes\MountClass;
use AcyMailing\Classes\SpeciesClass;
use AcyMailing\Helpers\PaginationHelper;
use AcyMailing\Helpers\ToolbarHelper;
use AcyMailing\Libraries\acymController;

class MountsController extends acymController
{
    public function __construct()
    {
        parent::__construct();
        $this->breadcrumb[acym_translation('ACYM_MOUNTS')] = acym_completeLink('mounts');
    }

    public function listing()
    {
        acym_setVar('layout', 'mounting');

        $data = [];
        $data['ordering'] = $this->getVarFiltersListing('string', 'mounts_ordering', 'id');
        $data['orderingSortOrder'] = $this->getVarFiltersListing('string', 'mounts_ordering_sort_order', 'desc');
        $data['pagination'] = new PaginationHelper();

        $this->prepareListingFilters($data);
        $this->prepareMountsListing($data);
        $this->prepareToolbar($data);

        parent::display($data);
    }

    protected function prepareListingFilters(&$data)
    {
        $data['status'] = $this->getVarFiltersListing('string', 'mounts_status', 'muldo');
        $data['search'] = $this->getVarFiltersListing('string', 'mounts_search', '');
        $data['color'] = $this->getVarFiltersListing('int', 'mounts_color', 0);
        $data['gender'] = $this->getVarFiltersListing('string', 'mounts_gender', '');
        $data['purity'] = $this->getVarFiltersListing('int', 'mounts_purity', 0);

        $colorClass = new ColorClass();
        $colors = $colorClass->getAll('id');
        uasort(
            $colors,
            function ($a, $b) {
                return strtolower($a->name) > strtolower($b->name) ? 1 : -1;
            }
        );

        $species = [
            'dragodinde' => 1,
            'muldo' => 2,
            'volkorne' => 3,
        ];

        $data['colors'] = [0 => acym_translation('ACYM_SELECT_A_COLOR')];
        foreach ($colors as $oneColor) {
            if (!empty($data['status'])) {
                if ($oneColor->species_id != $species[$data['status']]) {
                    continue;
                }
            }
            $data['colors'][$oneColor->id] = $oneColor->name;
        }

        $data['status_toolbar'] = [];
        if (!empty($data['color'])) {
            $data['status_toolbar']['mounts_color'] = $data['color'];
        }
        if (!empty($data['gender'])) {
            $data['status_toolbar']['mounts_gender'] = $data['gender'];
        }
        if (!empty($data['purity'])) {
            $data['status_toolbar']['mounts_purity'] = $data['purity'];
        }
    }

    protected function prepareToolbar(&$data)
    {
        $toolbarHelper = new ToolbarHelper();
        $toolbarHelper->addSearchBar($data['search'], 'mounts_search', 'ACYM_SEARCH');
        $toolbarHelper->addOptionSelect(
            acym_translation('ACYM_COLOR'),
            acym_select(
                $data['colors'],
                'mounts_color',
                $data['color'],
                ['class' => 'acym__select']
            )
        );
        $toolbarHelper->addOptionSelect(
            acym_translation('ACYM_GENDER'),
            acym_select(
                [
                    '' => acym_translation('ACYM_SELECT_A_GENDER'),
                    'male' => acym_translation('ACYM_MALE'),
                    'female' => acym_translation('ACYM_FEMALE'),
                ],
                'mounts_gender',
                $data['gender'],
                ['class' => 'acym__select']
            )
        );
        $toolbarHelper->addOptionSelect(
            acym_translation('ACYM_PURITY_LEVEL'),
            acym_select(
                [
                    '' => acym_translation('ACYM_SELECT_PURITY'),
                    '1' => acym_translation('ACYM_NONE'),
                    '2' => acym_translation('P1'),
                    '3' => acym_translation('P2'),
                    '4' => acym_translation('ACYM_PURE'),
                ],
                'mounts_purity',
                $data['purity'],
                ['class' => 'acym__select']
            )
        );
        $toolbarHelper->addButton(acym_translation('ACYM_CREATE'), ['data-task' => 'settings'], 'playmount_add', true);

        $data['toolbar'] = $toolbarHelper;
    }

    public function settings()
    {
        acym_setVar('layout', 'settings');

        $data = [];
        $data['svg'] = acym_loaderLogo(false);

        $mountId = acym_getVar('int', 'id', 0);

        if (!$this->prepareMountSettings($data, $mountId)) {
            return;
        }

        parent::display($data);
    }

    protected function prepareMountsListing(&$data)
    {
        $mountsPerPage = $data['pagination']->getListLimit();
        $page = $this->getVarFiltersListing('int', 'mounts_pagination_page', 1);

        $matchingMounts = $this->getMatchingElementsFromData(
            [
                'search' => $data['search'],
                'ordering' => $data['ordering'],
                'ordering_sort_order' => $data['orderingSortOrder'],
                'elementsPerPage' => $mountsPerPage,
                'offset' => ($page - 1) * $mountsPerPage,
                'status' => $data['status'],
                'color' => $data['color'],
                'gender' => $data['gender'],
                'purity' => $data['purity'],
            ],
            $data['status'],
            $page
        );
        $data['pagination']->setStatus($matchingMounts['total'], $page, $mountsPerPage);

        $data['mounts'] = $matchingMounts['elements'];
        $data['mountNumberPerStatus'] = $matchingMounts['status'];

        $data['purity_levels'] = [
            '1' => ['label' => acym_translation('ACYM_NONE'), 'color' => 'red'],
            '2' => ['label' => acym_translation('P1'), 'color' => 'darkorange'],
            '3' => ['label' => acym_translation('P2'), 'color' => 'cornflowerblue'],
            '4' => ['label' => acym_translation('ACYM_PURE'), 'color' => 'green'],
        ];
    }

    private function prepareMountSettings(&$data, $mountId)
    {
        $data['purity_levels'] = [
            '1' => acym_translation('ACYM_NONE'),
            '2' => acym_translation('P1'),
            '3' => acym_translation('P2'),
            '4' => acym_translation('ACYM_PURE'),
        ];

        $speciesClass = new SpeciesClass();
        $data['species'] = $speciesClass->getAll();

        $colorClass = new ColorClass();
        $colors = $colorClass->getAll();
        $data['colors'] = [];
        foreach ($colors as $oneColor) {
            $data['colors'][$oneColor->species_id][] = $oneColor;
        }

        $mounts = $this->currentClass->getAll();
        $data['mounts'] = [
            1 => [(object)['id' => 0, 'name' => acym_translation('ACYM_NONE')]],
            2 => [(object)['id' => 0, 'name' => acym_translation('ACYM_NONE')]],
            3 => [(object)['id' => 0, 'name' => acym_translation('ACYM_NONE')]],
        ];
        foreach ($mounts as $oneMount) {
            $data['mounts'][$colors[$oneMount->color_id]->species_id][] = $oneMount;
        }

        if (empty($mountId)) {
            $mountInformation = new \stdClass();
            $mountInformation->id = '';
            $mountInformation->name = $this->currentClass->getNextName();
            $mountInformation->species_id = 2;
            $mountInformation->color_id = '';
            $mountInformation->mother_id = '';
            $mountInformation->father_id = '';
            $mountInformation->max_reproductions = 4;
            $mountInformation->reproductions_counter = 0;
            $mountInformation->last_partner_id = 0;
            $mountInformation->gender = 0;
            $mountInformation->last_mating = 0;
            $mountInformation->purity_level = 1;
            $mountInformation->reproductive = 0;

            $data['mother_id'] = acym_getVar('int', 'mother_id', 0);
            if (!empty($data['mother_id'])) {
                $mountInformation->mother_id = $data['mother_id'];
                $mountClass = new MountClass();
                $mother = $mountClass->getOneById($data['mother_id']);
                if (!empty($mother->last_partner_id)) {
                    $mountInformation->father_id = $mother->last_partner_id;
                    $mountInformation->max_reproductions = $mother->max_reproductions;
                    $mountInformation->color_id = $mother->color_id;
                    $formData = (object)acym_getVar('array', 'mount', []);
                    if (!empty($formData->max_reproductions)) {
                        $mountInformation->max_reproductions = $formData->max_reproductions;
                        $mountInformation->gender = $formData->gender;
                    }

                    $father = $mountClass->getOneById($mother->last_partner_id);
                    if (!empty($father)) {
                        if ($father->color_id === $mother->color_id) {
                            $parentsPurity = min($mother->purity_level, $father->purity_level);
                            $mountInformation->purity_level = min($parentsPurity + 1, 4);
                        } else {
                            $mountInformation->purity_level = 0;
                        }
                    }
                }
            } else {
                $previous = acym_getVar('array', 'mount', []);
                if (!empty($previous['color_id'])) {
                    $mountInformation->species_id = acym_getVar('int', 'species', 2);
                    $mountInformation->color_id = $previous['color_id'];
                }
            }

            $this->breadcrumb[acym_translation('ACYM_NEW_MOUNT')] = acym_completeLink('mounts&task=settings');
        } else {
            $mountInformation = $this->currentClass->getOneById($mountId);
            if (is_null($mountInformation)) {
                acym_enqueueMessage(acym_translation('ACYM_LIST_DOESNT_EXIST'), 'error');
                $this->listing();

                return false;
            }
            $color = $colorClass->getOneById($mountInformation->color_id);
            $mountInformation->species_id = $color->species_id;

            $this->breadcrumb[acym_escape($mountInformation->name)] = acym_completeLink('mounts&task=settings&id='.$mountId);
        }

        $data['mountInformation'] = $mountInformation;

        return true;
    }

    public function apply()
    {
        $this->save('same');
    }

    public function saveAndNew()
    {
        $this->save('new');
    }

    public function save($redirection = 'listing')
    {
        acym_checkToken();

        $formData = (object)acym_getVar('array', 'mount', []);

        $mountId = acym_getVar('int', 'id', 0);
        if (!empty($mountId)) {
            $formData->id = $mountId;
        }

        $allowedFields = acym_getColumns('mount');
        $mountInformation = new \stdClass();
        foreach ($formData as $name => $data) {
            if (!in_array($name, $allowedFields)) {
                continue;
            }
            $mountInformation->{$name} = $data;
        }

        $mountId = $this->currentClass->save($mountInformation);

        if (!empty($mountId)) {
            acym_setVar('id', $mountId);
            acym_enqueueMessage(acym_translationSprintf('ACYM_MOUNT_IS_SAVED', $mountInformation->name), 'success');
        } else {
            acym_enqueueMessage(acym_translation('ACYM_ERROR_SAVING'), 'error');
            if (!empty($this->currentClass->errors)) {
                acym_enqueueMessage($this->currentClass->errors, 'error');
            }
        }

        if ($redirection === 'same') {
            $this->settings();
        } elseif ($redirection === 'new') {
            acym_setVar('id', 0);
            $this->settings();
        } else {
            $this->listing();
        }
    }

    public function mate()
    {
        acym_checkToken();
        $ids = acym_getVar('array', 'elements_checked', []);
        if (!$this->currentClass->mate($ids)) {
            acym_enqueueMessage(acym_translation('ACYM_SELECT_TWO_COMPATIBLE'), 'error');
        }

        $this->listing();
    }

    public function retire()
    {
        acym_checkToken();
        $ids = acym_getVar('array', 'elements_checked', []);

        foreach ($ids as $id) {
            $mount = $this->currentClass->getOneById($id);
            $mount->reproductions_counter = $mount->max_reproductions;
            $this->currentClass->save($mount);
        }

        $this->listing();
    }
}
