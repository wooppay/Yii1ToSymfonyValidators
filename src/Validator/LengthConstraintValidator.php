<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LengthConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof LengthConstraint) {
            throw new UnexpectedTypeException($constraint, LengthConstraint::class);
        }

        $params = $constraint->getParams();

        if ($params['allowEmpty'] == false && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            if (!empty($params['encoding'])) {
                $value = mb_convert_encoding($value, $params['encoding']);
            }

            if (!empty($params['is'])) {
                if (strlen($value) != $params['is']) {
                    $this->addViolation($constraint, 'lengthShouldBeEqualTo', $params['is']);
                }
            } else {
                if (strlen($value) < $params['min']) {
                    $this->addViolation($constraint, 'stringTooShortThan', $params['min']);
                }
    
                if (strlen($value) > $params['max']) {
                    $this->addViolation($constraint, 'stringTooLongThan', $params['max']);
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