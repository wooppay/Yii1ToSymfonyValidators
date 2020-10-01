<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Service;

class Converter
{
    public function toSymfonyValidator(string $type, array $params)
    {
        switch ($type) {
            case 'compare':
                return new CompareValidator($params);
        }
    }
}