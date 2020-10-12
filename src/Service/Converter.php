<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Service;

use Symfony\Component\Validator\Constraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\CompareConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\RequiredConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\NumericalConstraint;

class Converter
{
    public function toSymfonyValidator(string $type, array $params): Constraint
    {
        switch ($type) {
            case 'compare':
                return new CompareConstraint($params);
            case 'required':
                return new RequiredConstraint($params);
            case 'numerical':
                return new NumericalConstraint($params);
        }
    }
}