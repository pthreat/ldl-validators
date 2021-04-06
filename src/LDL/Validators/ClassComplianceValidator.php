<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ClassComplianceValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class ClassComplianceValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var ClassComplianceValidatorConfig
     */
    private $config;

    public function __construct(string $class, bool $strict=true)
    {
        $this->config = new ClassComplianceValidatorConfig($class, $strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(!is_object($value)){
            $msg = sprintf(
                'Value expected for "%s", must be an Object, "%s" was given',
                __CLASS__,
                gettype($value)
            );
            throw new TypeMismatchException($msg);
        }

        $class = $this->config->getClass();

        if($value instanceof $class) {
            return;
        }

        $msg = sprintf(
            'Value of class "%s", does not complies to class: "%s"',
            get_class($value),
            $class
        );

        throw new TypeMismatchException($msg);
    }


    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof ClassComplianceValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );

            throw new TypeMismatchException($msg);
        }

        /**
         * @var ClassComplianceValidatorConfig $config
         */
        return new self($config->getClass(), $config->isStrict());
    }

    /**
     * @return ClassComplianceValidatorConfig
     */
    public function getConfig(): ClassComplianceValidatorConfig
    {
        return $this->config;
    }
}