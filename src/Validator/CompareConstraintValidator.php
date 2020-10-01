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
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $this->translator->trans('emptyValue', [], 'test'))
                ->addViolation();
        } else {
            switch ($params['operator']) {
                case '>':
                    if ($value <= $params['compareValue']) {
                        $this->context->buildViolation($constraint->message)
                            ->setParameter('{{ string }}', $this->translator->trans('valueShouldBeGreaterThan', [
                                '%value%' => $params['compareValue']
                            ], 'test'))
                            ->addViolation()
                        ;
                    }
                    break;
                case '<':
                    if ($value >= $params['compareValue']) {
                        $this->context->buildViolation($constraint->message)
                            ->setParameter('{{ string }}', $this->translator->trans('valueShouldBeLessThan', [
                                '%value%' => $params['compareValue']
                            ], 'test'))
                            ->addViolation()
                        ;
                    }
                    break;
                case '>=':
                    if ($value < $params['compareValue']) {
                        $this->context->buildViolation($constraint->message)
                            ->setParameter('{{ string }}', $this->translator->trans('valueShouldBeEqualOrGreaterThan', [
                                '%value%' => $params['compareValue']
                            ], 'test'))
                            ->addViolation()
                        ;
                    }
                    break;
                case '<=':
                    if ($value > $params['compareValue']) {
                        $this->context->buildViolation($constraint->message)
                            ->setParameter('{{ string }}', $this->translator->trans('valueShouldBeEqualOrLessThan', [
                                '%value%' => $params['compareValue']
                            ], 'test'))
                            ->addViolation()
                        ;
                    }
                    break;
            }

            if ($params['strict'] == true) {
                switch ($params['operator']) {
                    case '=':
                        if ($value !== $params['compareValue']) {
                            $this->context->buildViolation($constraint->message)
                                ->setParameter('{{ string }}', $this->translator->trans('valueShouldBeEqual', [
                                    '%value%' => $params['compareValue']
                                ], 'test'))
                                ->addViolation()
                            ;
                        }
                        break;
                    case '!=':
                        if ($value === $params['compareValue']) {
                            $this->context->buildViolation($constraint->message)
                                ->setParameter('{{ string }}', $this->translator->trans('valueShouldNotBeEqual', [
                                    '%value%' => $params['compareValue']
                                ], 'test'))
                                ->addViolation()
                            ;
                        }
                        break;
                }
            } else {
                switch ($params['operator']) {
                    case '=':
                        if ($value != $params['compareValue']) {
                            $this->context->buildViolation($constraint->message)
                                ->setParameter('{{ string }}', $this->translator->trans('valueShouldBeEqual', [
                                    '%value%' => $params['compareValue']
                                ], 'test'))
                                ->addViolation()
                            ;
                        }
                        break;
                    case '!=':
                        if ($value == $params['compareValue']) {
                            $this->context->buildViolation($constraint->message)
                                ->setParameter('{{ string }}', $this->translator->trans('valueShouldNotBeEqual', [
                                    '%value%' => $params['compareValue']
                                ], 'test'))
                                ->addViolation()
                            ;
                        }
                        break;
                }
            }
        }
    }
}