<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NumericalConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NumericalConstraint) {
            throw new UnexpectedTypeException($constraint, NumericalConstraint::class);
        }

        $params = $constraint->getParams();

        if (!filter_var($params['allowEmpty'], FILTER_VALIDATE_BOOLEAN) && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            if (filter_var($params['integerOnly'], FILTER_VALIDATE_BOOLEAN)) {
                if (!preg_match($params['integerPattern'], $value)) {
                    $this->addViolation($constraint, 'invalidNumberFormat');
                }
            } else {
                if (!preg_match($params['numberPattern'], $value)) {
                    $this->addViolation($constraint, 'invalidNumberFormat');
                }
            }
    
            if (!empty($params['min']) && $value < $params['min']) {
                $this->addViolation($constraint, 'numberTooSmallThan', $params['min']);
            }
    
            if (!empty($params['max']) && $value > $params['max']) {
                $this->addViolation($constraint, 'numberTooBigThan', $params['max']);
            }
        }
    }

    private function addViolation(Constraint $constraint, string $text, string $value = null)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $this->translator->trans($text, [
                (!$value) ? null : '%value%' => $value
            ], 'validation'))
            ->addViolation()
        ;
    }
}