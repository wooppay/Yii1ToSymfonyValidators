<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LengthConstraint extends Constraint
{
    public $message = '{{ string }}';

    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }

    public function getParams()
    {
        return $this->params;
    }
}