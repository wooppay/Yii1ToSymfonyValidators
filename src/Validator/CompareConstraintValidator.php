<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CompareConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CompareConstraint) {
            throw new UnexpectedTypeException($constraint, CompareConstraint::class);
        }

        $params = $constraint->getParams();

        if (!filter_var($params['allowEmpty'], FILTER_VALIDATE_BOOLEAN) && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            switch ($params['operator']) {
                case '>':
                    if ($value <= $params['compareValue']) {
                        $this->addViolation($constraint, 'valueShouldBeGreaterThan', $params['compareValue']);
                    }
                    break;
                case '<':
                    if ($value >= $params['compareValue']) {
                        $this->addViolation($constraint, 'valueShouldBeLessThan', $params['compareValue']);
                    }
                    break;
                case '>=':
                    if ($value < $params['compareValue']) {
                        $this->addViolation($constraint, 'valueShouldBeEqualOrGreaterThan', $params['compareValue']);
                    }
                    break;
                case '<=':
                    if ($value > $params['compareValue']) {
                        $this->addViolation($constraint, 'valueShouldBeEqualOrLessThan', $params['compareValue']);
                    }
                    break;
            }

            if ($params['strict'] == true) {
                switch ($params['operator']) {
                    case '=':
                        if ($value !== $params['compareValue']) {
                            $this->addViolation($constraint, 'valueShouldBeEqual', $params['compareValue']);
                        }
                        break;
                    case '!=':
                        if ($value === $params['compareValue']) {
                            $this->addViolation($constraint, 'valueShouldNotBeEqual', $params['compareValue']);
                        }
                        break;
                }
            } else {
                switch ($params['operator']) {
                    case '=':
                        if ($value != $params['compareValue']) {
                            $this->addViolation($constraint, 'valueShouldBeEqual', $params['compareValue']);
                        }
                        break;
                    case '!=':
                        if ($value == $params['compareValue']) {
                            $this->addViolation($constraint, 'valueShouldNotBeEqual', $params['compareValue']);
                        }
                        break;
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