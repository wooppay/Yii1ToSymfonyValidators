<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MatchConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof MatchConstraint) {
            throw new UnexpectedTypeException($constraint, MatchConstraint::class);
        }

        $params = $constraint->getParams();

        if ($params['allowEmpty'] == false && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            if ($params['not'] == false) {
                if (!preg_match($params['pattern'], $value)) {
                    $this->addViolation($constraint, 'invalidFormat');
                }
            } else {
                if (preg_match($params['pattern'], $value)) {
                    $this->addViolation($constraint, 'invalidFormat');
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