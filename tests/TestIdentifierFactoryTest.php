<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\BasilTestIdentifierFactory\Tests;

use webignition\BasilModel\Identifier\ElementIdentifier;
use webignition\BasilModel\Identifier\ElementIdentifierInterface;
use webignition\BasilModel\Identifier\Identifier;
use webignition\BasilModel\Identifier\IdentifierTypes;
use webignition\BasilModel\Value\ObjectValue;
use webignition\BasilModel\Value\ObjectValueInterface;
use webignition\BasilModel\Value\ValueTypes;
use webignition\BasilTestIdentifierFactory\TestIdentifierFactory;

class TestIdentifierFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createCssElementIdentifierDataProvider
     */
    public function testCreateCssElementIdentifier(
        string $selector,
        ?int $position,
        ?string $name,
        ?ElementIdentifierInterface $parentIdentifier,
        string $expectedIdentifierString,
        int $expectedPosition
    ) {
        $identifier = TestIdentifierFactory::createCssElementIdentifier($selector, $position, $name, $parentIdentifier);

        $this->assertInstanceOf(ElementIdentifier::class, $identifier);
        $this->assertEquals(IdentifierTypes::ELEMENT_SELECTOR, $identifier->getType());
        $this->assertSame(ValueTypes::CSS_SELECTOR, $identifier->getValue()->getType());
        $this->assertSame($expectedIdentifierString, (string) $identifier);
        $this->assertSame($expectedPosition, $identifier->getPosition());
        $this->assertSame($name, $identifier->getName());
        $this->assertSame($parentIdentifier, $identifier->getParentIdentifier());
    }

    public function createCssElementIdentifierDataProvider(): array
    {
        $parentIdentifier = TestIdentifierFactory::createCssElementIdentifier('.parent', 1, 'parent');

        return [
            'selector' => [
                'selector' => '.selector',
                'position' => null,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => 1,
            ],
            'selector, position=1' => [
                'selector' => '.selector',
                'position' => 1,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => 1,
            ],
            'selector, position=2' => [
                'selector' => '.selector',
                'position' => 2,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector":2',
                'expectedPosition' => 2,
            ],
            'name' => [
                'selector' => '.selector',
                'position' => null,
                'name' => 'identifier name',
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => 1,
            ],
            'parent identifier' => [
                'selector' => '.selector',
                'position' => null,
                'name' => null,
                'parentIdentifier' => $parentIdentifier,
                'expectedIdentifierString' => '"{{ parent }} .selector"',
                'expectedPosition' => 1,
            ],
        ];
    }

    /**
     * @dataProvider createXpathElementIdentifierDataProvider
     */
    public function testCreateXpathElementIdentifier(
        string $selector,
        ?int $position,
        ?string $name,
        ?ElementIdentifierInterface $parentIdentifier,
        string $expectedIdentifierString,
        int $expectedPosition
    ) {
        $identifier = TestIdentifierFactory::createXpathElementIdentifier(
            $selector,
            $position,
            $name,
            $parentIdentifier
        );

        $this->assertInstanceOf(ElementIdentifier::class, $identifier);
        $this->assertEquals(IdentifierTypes::ELEMENT_SELECTOR, $identifier->getType());
        $this->assertSame(ValueTypes::XPATH_EXPRESSION, $identifier->getValue()->getType());
        $this->assertSame($expectedIdentifierString, (string) $identifier);
        $this->assertSame($expectedPosition, $identifier->getPosition());
        $this->assertSame($name, $identifier->getName());
        $this->assertSame($parentIdentifier, $identifier->getParentIdentifier());
    }

    public function createXpathElementIdentifierDataProvider(): array
    {
        $parentIdentifier = TestIdentifierFactory::createXpathElementIdentifier('//parent', 1, 'parent');

        return [
            'selector' => [
                'selector' => '//h1',
                'position' => null,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => 1,
            ],
            'selector, position=1' => [
                'selector' => '//h1',
                'position' => 1,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => 1,
            ],
            'selector, position=2' => [
                'selector' => '//h1',
                'position' => 2,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1":2',
                'expectedPosition' => 2,
            ],
            'name' => [
                'selector' => '//h1',
                'position' => null,
                'name' => 'identifier name',
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => 1,
            ],
            'parent identifier' => [
                'selector' => '//h1',
                'position' => null,
                'name' => null,
                'parentIdentifier' => $parentIdentifier,
                'expectedIdentifierString' => '"{{ parent }} //h1"',
                'expectedPosition' => 1,
            ],
        ];
    }

    /**
     * @dataProvider createPageElementReferenceIdentifierDataProvider
     */
    public function testCreatePageElementReferenceIdentifier(
        ObjectValueInterface $objectValue,
        ?string $name,
        string $expectedIdentifierString
    ) {
        $identifier = TestIdentifierFactory::createPageElementReferenceIdentifier($objectValue, $name);

        $this->assertInstanceOf(Identifier::class, $identifier);
        $this->assertEquals(IdentifierTypes::PAGE_ELEMENT_REFERENCE, $identifier->getType());
        $this->assertEquals($name, $identifier->getName());
        $this->assertSame($expectedIdentifierString, (string) $identifier);
    }

    public function createPageElementReferenceIdentifierDataProvider(): array
    {
        $objectValue = new ObjectValue(
            ValueTypes::PAGE_ELEMENT_REFERENCE,
            'page_import_name.elements.element_name',
            'page_import_name',
            'element_name'
        );

        return [
            'reference' => [
                'objectValue' => $objectValue,
                'name' => null,
                'expectedIdentifierString' => 'page_import_name.elements.element_name',
            ],
            'reference, name' => [
                'objectValue' => $objectValue,
                'name' => 'name',
                'expectedIdentifierString' => 'page_import_name.elements.element_name',
            ],
        ];
    }
}
