<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\BasilTestIdentifierFactory\Tests;

use webignition\BasilModel\Identifier\DomIdentifierInterface;
use webignition\BasilModel\Identifier\ReferenceIdentifierInterface;
use webignition\BasilModel\Value\PageElementReference;
use webignition\BasilTestIdentifierFactory\TestIdentifierFactory;

class TestIdentifierFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createElementIdentifierDataProvider
     */
    public function testCreateElementIdentifier(
        string $locator,
        ?int $position,
        ?string $name,
        ?DomIdentifierInterface $parentIdentifier,
        string $expectedIdentifierString,
        ?int $expectedPosition
    ) {
        $identifier = TestIdentifierFactory::createElementIdentifier(
            $locator,
            $position,
            $name,
            $parentIdentifier
        );

        $this->assertInstanceOf(DomIdentifierInterface::class, $identifier);
        $this->assertSame($expectedIdentifierString, (string) $identifier);
        $this->assertSame($expectedPosition, $identifier->getOrdinalPosition());
        $this->assertSame($name, $identifier->getName());
        $this->assertSame($parentIdentifier, $identifier->getParentIdentifier());
    }

    public function createElementIdentifierDataProvider(): array
    {
        $parentIdentifier = TestIdentifierFactory::createElementIdentifier(
            '.parent',
            1,
            'parent'
        );

        return [
            'css selector, position=null' => [
                'locator' => '.selector',
                'position' => null,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => null,
            ],
            'css selector, position=1' => [
                'locator' => '.selector',
                'position' => 1,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => 1,
            ],
            'css selector, position=2' => [
                'locator' => '.selector',
                'position' => 2,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector":2',
                'expectedPosition' => 2,
            ],
            'css selector, name' => [
                'locator' => '.selector',
                'position' => null,
                'name' => 'identifier name',
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => null,
            ],
            'css selector, parent identifier' => [
                'locator' => '.selector',
                'position' => null,
                'name' => null,
                'parentIdentifier' => $parentIdentifier,
                'expectedIdentifierString' => '"{{ parent }} .selector"',
                'expectedPosition' => null,
            ],
            'xpath expression' => [
                'locator' => '//h1',
                'position' => null,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => null,
            ],
            'xpath expression, position=1' => [
                'locator' => '//h1',
                'position' => 1,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => 1,
            ],
            'xpath expression, position=2' => [
                'locator' => '//h1',
                'position' => 2,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1":2',
                'expectedPosition' => 2,
            ],
            'xpath expression, name' => [
                'locator' => '//h1',
                'position' => null,
                'name' => 'identifier name',
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => null,
            ],
            'xpath expression, parent identifier' => [
                'locator' => '//h1',
                'position' => null,
                'name' => null,
                'parentIdentifier' => $parentIdentifier,
                'expectedIdentifierString' => '"{{ parent }} //h1"',
                'expectedPosition' => null,
            ],
        ];
    }

    /**
     * @dataProvider createPageElementReferenceIdentifierDataProvider
     */
    public function testCreatePageElementReferenceIdentifier(
        PageElementReference $pageElementReference,
        ?string $name,
        string $expectedIdentifierString
    ) {
        $identifier = TestIdentifierFactory::createPageElementReferenceIdentifier($pageElementReference, $name);

        $this->assertInstanceOf(ReferenceIdentifierInterface::class, $identifier);
        $this->assertEquals($name, $identifier->getName());
        $this->assertSame($expectedIdentifierString, (string) $identifier);
    }

    public function createPageElementReferenceIdentifierDataProvider(): array
    {
        $pageElementReference = new PageElementReference(
            'page_import_name.elements.element_name',
            'page_import_name',
            'element_name'
        );

        return [
            'reference' => [
                'pageElementReference' => $pageElementReference,
                'name' => null,
                'expectedIdentifierString' => 'page_import_name.elements.element_name',
            ],
            'reference, name' => [
                'pageElementReference' => $pageElementReference,
                'name' => 'name',
                'expectedIdentifierString' => 'page_import_name.elements.element_name',
            ],
        ];
    }
}
