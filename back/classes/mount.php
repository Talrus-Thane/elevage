<?php

namespace AcyMailing\Classes;

use AcyMailing\Helpers\PaginationHelper;
use AcyMailing\Libraries\acymClass;

class MountClass extends acymClass
{
    var $table = 'mount';
    var $pkey = 'id';

    public function getMatchingElements($settings = [])
    {
        $columns = 'mount.*, color.species_id';

        $query = 'SELECT '.$columns.' FROM #__acym_mount AS mount 
        JOIN #__acym_color AS color ON color.id = mount.color_id ';
        $queryCount = 'SELECT COUNT(mount.id) FROM #__acym_mount AS mount 
        JOIN #__acym_color AS color ON color.id = mount.color_id ';
        $queryStatus = 'SELECT COUNT(mount.id) AS number, color.species_id FROM #__acym_mount AS mount 
        JOIN #__acym_color AS color ON color.id = mount.color_id ';
        $filters = [];
        $filters[] = 'mount.max_reproductions != mount.reproductions_counter';

        if (!empty($settings['search'])) {
            $filters[] = 'mount.name LIKE '.acym_escapeDB('%'.$settings['search'].'%');
        }

        if (!empty($settings['color'])) {
            $filters[] = 'mount.color_id = '.intval($settings['color']);
        }

        if (!empty($settings['gender'])) {
            $filters[] = 'mount.gender = '.($settings['gender'] === 'male' ? 0 : 1);
        }

        if (!empty($settings['purity'])) {
            $filters[] = 'mount.purity_level = '.intval($settings['purity']);
        }

        if (!empty($filters)) {
            $queryStatus .= ' WHERE ('.implode(') AND (', $filters).')';
        }

        if (!empty($settings['status'])) {
            $allowedStatus = [
                'dragodinde' => 'color.species_id = 1',
                'muldo' => 'color.species_id = 2',
                'volkorne' => 'color.species_id = 3',
            ];
            if (empty($allowedStatus[$settings['status']])) {
                die('Injection denied');
            }
            $filters[] = $allowedStatus[$settings['status']];
        }

        if (!empty($settings['where'])) {
            $filters[] = $settings['where'];
        }

        if (!empty($filters)) {
            $query .= ' WHERE ('.implode(') AND (', $filters).')';
            $queryCount .= ' WHERE ('.implode(') AND (', $filters).')';
        }

        if (!empty($settings['ordering']) && !empty($settings['ordering_sort_order'])) {
            if (strpos($settings['ordering'], ',') !== false) {
                $orderings = explode(',', $settings['ordering']);
                $query .= ' ORDER BY ';

                foreach ($orderings as $oneOrdering) {
                    $query .= 'mount.'.acym_secureDBColumn($oneOrdering).' '.acym_secureDBColumn(strtoupper($settings['ordering_sort_order'])).', ';
                }
                $query = rtrim($query, ', ');
            } else {
                $query .= ' ORDER BY mount.'.acym_secureDBColumn($settings['ordering']).' '.acym_secureDBColumn(strtoupper($settings['ordering_sort_order']));
            }
        }

        if (empty($settings['offset']) || $settings['offset'] < 0) {
            $settings['offset'] = 0;
        }

        if (empty($settings['elementsPerPage']) || $settings['elementsPerPage'] < 1) {
            $pagination = new PaginationHelper();
            $settings['elementsPerPage'] = $pagination->getListLimit();
        }

        $results['elements'] = acym_loadObjectList($query, '', $settings['offset'], $settings['elementsPerPage']);

        $results['total'] = acym_loadResult($queryCount);

        $mountsPerStatus = acym_loadObjectList($queryStatus.' GROUP BY species_id', 'species_id');
        for ($i = 1 ; $i < 4 ; $i++) {
            $mountsPerStatus[$i] = empty($mountsPerStatus[$i]) ? 0 : $mountsPerStatus[$i]->number;
        }

        $results['status'] = [
            'all' => array_sum($mountsPerStatus),
            'dragodinde' => $mountsPerStatus[1],
            'muldo' => $mountsPerStatus[2],
            'volkorne' => $mountsPerStatus[3],
        ];

        return $results;
    }

    public function mate($elements)
    {
        // We need exactly two mounts
        if (empty($elements) || count($elements) !== 2) {
            return false;
        }

        $firstParent = $this->getOneById($elements[0]);
        $secondParent = $this->getOneById($elements[1]);

        if (empty($firstParent) || empty($secondParent)) {
            return false;
        }

        // Of opposite sex
        if ($firstParent->gender === $secondParent->gender) {
            return false;
        }

        // That aren't sterile yet
        if ($firstParent->max_reproductions === $firstParent->reproductions_counter) {
            return false;
        }
        if ($secondParent->max_reproductions === $secondParent->reproductions_counter) {
            return false;
        }

        $colorClass = new ColorClass();
        $firstColor = $colorClass->getOneById($firstParent->color_id);
        $secondColor = $colorClass->getOneById($secondParent->color_id);

        // Of the same species
        if ($firstColor->species_id !== $secondColor->species_id) {
            return false;
        }

        $firstParent->reproductions_counter++;
        $secondParent->reproductions_counter++;

        $firstParent->last_partner_id = $secondParent->id;
        $secondParent->last_partner_id = $firstParent->id;

        $time = time();
        $firstParent->last_mating = $time;
        $secondParent->last_mating = $time;

        $this->save($firstParent);
        $this->save($secondParent);

        return true;
    }

    public function getNextName()
    {
        return acym_loadResult('SELECT MAX(id) FROM #__acym_mount') + 1;
    }

    public function save($element)
    {
        if (empty($element->id) && empty($element->creation_date)) {
            $element->creation_date = time();
        }

        return parent::save($element);
    }
}
