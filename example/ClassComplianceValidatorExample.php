<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Config\ClassComplianceValidatorConfig;
use LDL\Validators\ClassComplianceValidator;
use LDL\Validators\StringEqualsValidator;
use LDL\Validators\Exception\ValidatorException;
use LDL\Validators\RegexValidator;
use LDL\Validators\Config\RegexValidatorConfig;

echo "Create class compliance validator from config\n";
echo "Set config class: 'RegexValidator'\n";

$config = ClassComplianceValidatorConfig::fromArray([
    'class' => RegexValidator::class
]);

$validator = ClassComplianceValidator::fromConfig($config);

echo "Validate integer, exception must be thrown\n";

try{
    $validator->validate(1);
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate regex config, exception must be thrown\n";

try{
    $validator->validate(new RegexValidatorConfig('#[0-9]+#'));
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate Exact string match validator, exception must be thrown\n";

try{
    $validator->validate(new StringEqualsValidator('test'));
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate regex validator\n";
$validator->validate(new RegexValidator('#[0-9]+#'));
echo "OK!\n";