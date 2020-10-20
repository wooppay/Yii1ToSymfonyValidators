<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class InConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof InConstraint) {
            throw new UnexpectedTypeException($constraint, InConstraint::class);
        }

        $params = $constraint->getParams();

        if (!filter_var($params['allowEmpty'], FILTER_VALIDATE_BOOLEAN) && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            $range = explode(', ', $params['range']);

            if ($params['strict'] == false) {
                if ($params['not'] == false) {
                    if (!in_array($value, $range)) {
                        $this->addViolation($constraint, 'valueShouldBeInList', $params['range']);
                    }
                } else {
                    if (in_array($value, range)) {
                        $this->addViolation($constraint, 'valueShouldNotBeInList', $params['range']);
                    }
                }
            } else {
                if ($params['not'] == false) {
                    if (!in_array($value, $range, true)) {
                        $this->addViolation($constraint, 'valueShouldBeInList', $params['range']);
                    }
                } else {
                    if (in_array($value, $range, true)) {
                        $this->addViolation($constraint, 'valueShouldNotBeInList', $params['range']);
                    }
                }
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