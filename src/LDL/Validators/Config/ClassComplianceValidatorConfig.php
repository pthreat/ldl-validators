<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class ClassComplianceValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

    /**
     * @var string
     */
    private $class;

    /**
     * @var bool
     */
    private $strict;

    public function __construct(string $class, bool $strict=false, bool $negated=false, bool $dumpable=true)
    {
        if(!class_exists($class)){
            throw new \LogicException("Class \"$class\" does not exists");
        }

        $this->_tNegated = $negated;
        $this->_tDumpable = $dumpable;
        $this->class = $class;
    }

    public function isStrict() : bool
    {
        return $this->strict;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param array $data
     * @return ArrayFactoryInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('class', $data)){
            $msg = sprintf("Missing property 'class' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        try{
            return new self(
                (string) $data['class'],
                array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
                array_key_exists('dumpable', $data) ? (bool)$data['dumpable'] : true
            );
        }catch(\Exception $e){
            throw new ArrayFactoryException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'class' => $this->class,
            'negated' => $this->_tNegated,
            'dumpable' => $this->_tDumpable
        ];
    }
}