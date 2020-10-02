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

        if ($params['allowEmpty'] == false && empty($value)) {
            $this->addViolation('emptyValue');
        } else {
            switch ($params['operator']) {
                case '>':
                    if ($value <= $params['compareValue']) {
                        $this->addViolation('valueShouldBeGreaterThan', $params['compareValue']);
                    }
                    break;
                case '<':
                    if ($value >= $params['compareValue']) {
                        $this->addViolation('valueShouldBeLessThan', $params['compareValue']);
                    }
                    break;
                case '>=':
                    if ($value < $params['compareValue']) {
                        $this->addViolation('valueShouldBeEqualOrGreaterThan', $params['compareValue']);
                    }
                    break;
                case '<=':
                    if ($value > $params['compareValue']) {
                        $this->addViolation('valueShouldBeEqualOrLessThan', $params['compareValue']);
                    }
                    break;
            }

            if ($params['strict'] == true) {
                switch ($params['operator']) {
                    case '=':
                        if ($value !== $params['compareValue']) {
                            $this->addViolation('valueShouldBeEqual', $params['compareValue']);
                        }
                        break;
                    case '!=':
                        if ($value === $params['compareValue']) {
                            $this->addViolation('valueShouldNotBeEqual', $params['compareValue']);
                        }
                        break;
                }
            } else {
                switch ($params['operator']) {
                    case '=':
                        if ($value != $params['compareValue']) {
                            $this->addViolation('valueShouldBeEqual', $params['compareValue']);
                        }
                        break;
                    case '!=':
                        if ($value == $params['compareValue']) {
                            $this->addViolation('valueShouldNotBeEqual', $params['compareValue']);
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