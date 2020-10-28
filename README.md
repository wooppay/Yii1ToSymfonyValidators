# Yii1ToSymfonyValidators

This is an open-source bundle for converting Yii's constraint to Symfony's constraint.

Installation
============

1. Configure your project by adding this repository:
```composer
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/wooppay/Yii1ToSymfonyValidators"
    }
]
```

2. Install this bundle using Composer:
```bash
composer require wooppay/yii-to-symfony-validators-bundle
```

Configuration
=============

1. This bundle using **TranslatorInterface** to return error messages. So you need to configure your *translation.yaml* file:
```yaml
# config/packages/translation.yaml
framework:
    default_locale: en
    translator:
        default_path: '%kernel.project_dir%/translations'
        fallbacks:
            - en
```

2. Then you need to create translation files named by **validation** + locale + extension. For example:
```
validation.en.php
validation.en.yaml
validation.ru.php
```

> NOTE: The translation domain **MUST BE** named as "validation".

Usage
=====

1. In your *FormBuilder* create the **Converter** object. And then use **toSymfonyValidator()** method by passing type of validator and validation parameters. This method returns **Constraint** object of symfony:
```php
use wooppay\YiiToSymfonyValidatorsBundle\Service\Converter;

class YourForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $converter = new Converter();
        $constraint = $converter->toSymfonyValidator('compare',
            [
            'strict' => false,
            'message' => "err",
            'operator' => ">=",
            'allowEmpty' => false,
            'compareValue' => 5,
            'compareAttribute' => ""
            ]
        );
        
        // ...
    }
}
```

2. Then you can use converted constraint to validate your form's fields:
```php
use wooppay\YiiToSymfonyValidatorsBundle\Service\Converter;

class YourForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ...
        
        $builder
            ->add('test', IntegerType::class, [
                // ...
                'constraints' => [
                    $constraint
                ]
            ])
            ->add('save', SubmitType::class)
        ;
    }
}
```

Keys for translations
=====================
>NOTE: Descriptions that contain the **%value%** means that value parameter passed there. So you need to add this value to your translation to inform user.
1. CompareConstraint
- **emptyValue**: contains message that value should not be empty;
- **valueShouldBeGreaterThan**: contains message that value should be greater than **%value%**;
- **valueShouldBeLessThan**: contains message that value should be less than **%value%**;
- **valueShouldBeEqualOrGreaterThan**: contains message that value should be equal or greater than **%value%**;
- **valueShouldBeEqualOrLessThan**: contains message that value should be equal or less than **%value%**;
- **valueShouldBeEqual**: contains message that value should be equal to **%value%**;
- **valueShouldNotBeEqual**: contains message that value should not be equal to **%value%**;

2. RequiredConstraint
- **emptyValue**: contains message that value should not be empty;

3. NumericalConstraint
- **emptyValue**: contains message that value should not be empty;
- **invalidNumberFormat**: contains message that value has invalid format;
- **numberTooSmallThan**: contains message that value is too small than **%value%**;
- **numberTooBigThan**: contains message that value is too big than **%value%**;

4. LengthConstraint
- **emptyValue**: contains message that value should not be empty;
- **lengthShouldBeEqualTo**: contains message that value length should be equal to **%value%**;
- **stringTooShortThan**: contains message that value length is too short than **%value%**;
- **stringTooLongThan**: contains message that value length is too long than **%value%**;

5. MatchConstraint
- **emptyValue**: contains message that value should not be empty;
- **invalidFormat**: contains message that value has invalid format;

6. InConstraint
- **emptyValue**: contains message that value should not be empty;
- **valueShouldBeInList**: contains message that value should be in the list of **%value%**;
- **valueShouldNotBeInList**: contains message that value should not be in the list of **%value%**;

7. EmailConstraint
- **emptyValue**: contains message that value should not be empty;
- **invalidEmailFormat**: contains message that value has invalid format;