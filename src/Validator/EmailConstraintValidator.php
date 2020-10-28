<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EmailConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EmailConstraint) {
            throw new UnexpectedTypeException($constraint, EmailConstraint::class);
        }

        $params = $constraint->getParams();

        if (!filter_var($params['allowEmpty'], FILTER_VALIDATE_BOOLEAN) && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            if ($params['pattern'] && !preg_match($params['pattern'], $value)) {
                $this->addViolation($constraint, 'invalidEmailFormat');
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