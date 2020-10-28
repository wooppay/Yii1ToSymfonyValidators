<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Service;

use Symfony\Component\Validator\Constraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\CompareConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\RequiredConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\NumericalConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\LengthConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\MatchConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\InConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\DateConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\EmailConstraint;

class Converter
{
    public function toSymfonyValidator(string $type, array $params): ?Constraint
    {
        switch ($type) {
            case 'compare':
                return new CompareConstraint($params);
            case 'required':
                return new RequiredConstraint($params);
            case 'numerical':
                return new NumericalConstraint($params);
            case 'length':
                return new LengthConstraint($params);
            case 'match':
                return new MatchConstraint($params);
            case 'in':
                return new InConstraint($params);
            case 'date':
                return new DateConstraint($params);
            case 'email':
                return new EmailConstraint($params);
            default:
                return null;
        }
    }
}