<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ObjectValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use ValidatorHasConfigInterfaceTrait;

    public function __construct(bool $negated=false, bool $dumpable=true, string $description=null)
    {
        $this->_tConfig = new Config\BasicValidatorConfig($negated, $dumpable, $description);
    }

    public function assertTrue($value): void
    {
        if(is_object($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be an Object, "%s" was given',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_object($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must NOT be an Object, "%s" was given',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    /**
     * @param Config\ValidatorConfigInterface $config
     * @return ValidatorInterface
     * @throws \InvalidArgumentException
     */
    public static function fromConfig(Config\ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof Config\BasicValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new \InvalidArgumentException($msg);
        }

        /**
         * @var Config\ValidatorConfigInterface $config
         */
        return new self($config->isNegated(), $config->isDumpable());
    }
}