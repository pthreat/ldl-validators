<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\ValidatorInterface;

class AndValidatorChain extends AbstractValidatorChain
{
    use ValidatorValidateTrait;

    public const OPERATOR = ' && ';

    public function assertTrue($value, ...$params): void
    {
        $this->reset();

        if(0 === $this->count()){
            return;
        }

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            $this->setLastExecuted($validator);

            try {
                $validator->validate($value, ...$params);
                $this->getSucceeded()->append($validator);
            }catch(\Exception $e){
                $this->getFailed()->append($validator);
                throw $e;
            }
        }

    }

    public function assertFalse($value, ...$params): void
    {
        $this->reset();

        if(0 === $this->count()){
            return;
        }

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            $this->setLastExecuted($validator);

            try {
                $validator->validate($value, ...$params);
                $this->getSucceeded()->append($validator);
            }catch(\Exception $e){
                $this->getFailed()->append($validator);
                break;
            }
        }

        if($this->getFailed()->count() > 0){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Failed to assert that value "%s" complies to: %s',
                var_export($value, true),
                ValidatorChainExprDumper::dump($this)
            )
        );
    }

    public static function fromConfig(
        ValidatorConfigInterface $config,
        iterable $validators=null
    ): ValidatorChainInterface
    {
        if(!$config instanceof Config\ValidatorChainConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new \InvalidArgumentException($msg);
        }

        return self::factory(
            $validators,
            $config->isDumpable(),
            $config->isNegated(),
            $config->getDescription()
        );
    }
}
