<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Service;

use wooppay\YiiToSymfonyValidatorsBundle\Validator\CompareConstraint;

class Converter
{
    public function toSymfonyValidator(string $type, array $params)
    {
        switch ($type) {
            case 'compare':
                return new CompareConstraint($params);
        }
    }
}