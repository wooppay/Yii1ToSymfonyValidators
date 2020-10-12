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

        if ($params['allowEmpty'] == false && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            if ($params['integerOnly'] == true) {
                if (!preg_match("/^\s*[+-]?\d+\s*$/", $value)) {
                    $this->addViolation($constraint, 'invalidNumberFormat');
                }
            } else {
                if (!preg_match('/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/', $value)) {
                    $this->addViolation($constraint, 'invalidNumberFormat');
                }
            }
    
            if ($value < $params['min']) {
                $this->addViolation($constraint, 'numberTooSmall', $params['min']);
            }
    
            if ($value > $params['max']) {
                $this->addViolation($constraint, 'numberTooBig', $params['max']);
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