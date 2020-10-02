<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Service;

use Symfony\Component\Validator\Constraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\CompareConstraint;

class Converter
{
    public function toSymfonyValidator(string $type, array $params): Constraint
    {
        switch ($type) {
            case 'compare':
                return new CompareConstraint($params);
        }
    }
}