<?php

namespace App\Service;

use Doctrine\Common\Collections\Criteria;

/**
 * Class CriteriaHelper
 * @package App\Service
 */
class CriteriaHelper
{
    /**
     * Converts a query string into a Criteria object
     * @param $query
     * @return Criteria
     */
    public function createCriteria($query): Criteria
    {
        $criteria = Criteria::create();
        if (array_key_exists('order', $query)) {
            $arr_condition = explode(',', $query['order']);
            $criteria->orderBy([
                $arr_condition[0] => (isset($arr_condition[1]) ? $arr_condition[1] : 'ASC')
            ]);
            unset($query['order']);
        }

        foreach ($query as $col => $val) {
            $arr_condition = explode(',', $val);
            $value = $arr_condition[0];
            if (isset($arr_condition[1])) {
                $operator = $arr_condition[1];
                switch ($operator) {
                    case 'like':
                        $criteria->andWhere(Criteria::expr()->contains($col, $value));
                        break;
                    case '>':
                        $criteria->andWhere(Criteria::expr()->gt($col, $value));
                        break;
                    case '>=':
                        $criteria->andWhere(Criteria::expr()->gte($col, $value));
                        break;
                    case '<':
                        $criteria->andWhere(Criteria::expr()->lt($col, $value));
                        break;
                    case '<=':
                        $criteria->andWhere(Criteria::expr()->lte($col, $value));
                        break;
                    default:
                        $criteria->andWhere(Criteria::expr()->eq($col, $value));
                        break;
                }
            } else {
                $criteria->andWhere(Criteria::expr()->eq($col, $value));
            }
        }

        return $criteria;
    }
}