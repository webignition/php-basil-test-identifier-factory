<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\BasilTestIdentifierFactory\Tests;

use webignition\BasilModel\Identifier\DomIdentifierInterface;
use webignition\BasilModel\Identifier\ReferenceIdentifierInterface;
use webignition\BasilModel\Value\ElementExpression;
use webignition\BasilModel\Value\ElementExpressionInterface;
use webignition\BasilModel\Value\ElementExpressionType;
use webignition\BasilModel\Value\PageElementReference;
use webignition\BasilTestIdentifierFactory\TestIdentifierFactory;

class TestIdentifierFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createElementIdentifierDataProvider
     */
    public function testCreateElementIdentifier(
        ElementExpressionInterface $elementExpression,
        ?int $position,
        ?string $name,
        ?DomIdentifierInterface $parentIdentifier,
        string $expectedIdentifierString,
        ?int $expectedPosition
    ) {
        $identifier = TestIdentifierFactory::createElementIdentifier(
            $elementExpression,
            $position,
            $name,
            $parentIdentifier
        );

        $this->assertInstanceOf(DomIdentifierInterface::class, $identifier);
        $this->assertSame($expectedIdentifierString, (string) $identifier);
        $this->assertSame($expectedPosition, $identifier->getPosition());
        $this->assertSame($name, $identifier->getName());
        $this->assertSame($parentIdentifier, $identifier->getParentIdentifier());
    }

    public function createElementIdentifierDataProvider(): array
    {
        $parentIdentifier = TestIdentifierFactory::createElementIdentifier(
            new ElementExpression('.parent', ElementExpressionType::CSS_SELECTOR),
            1,
            'parent'
        );

        return [
            'css selector, position=null' => [
                'elementExpression' => new ElementExpression('.selector', ElementExpressionType::CSS_SELECTOR),
                'position' => null,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => null,
            ],
            'css selector, position=1' => [
                'elementExpression' => new ElementExpression('.selector', ElementExpressionType::CSS_SELECTOR),
                'position' => 1,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => 1,
            ],
            'css selector, position=2' => [
                'elementExpression' => new ElementExpression('.selector', ElementExpressionType::CSS_SELECTOR),
                'position' => 2,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector":2',
                'expectedPosition' => 2,
            ],
            'css selector, name' => [
                'elementExpression' => new ElementExpression('.selector', ElementExpressionType::CSS_SELECTOR),
                'position' => null,
                'name' => 'identifier name',
                'parentIdentifier' => null,
                'expectedIdentifierString' => '".selector"',
                'expectedPosition' => null,
            ],
            'css selector, parent identifier' => [
                'elementExpression' => new ElementExpression('.selector', ElementExpressionType::CSS_SELECTOR),
                'position' => null,
                'name' => null,
                'parentIdentifier' => $parentIdentifier,
                'expectedIdentifierString' => '"{{ parent }} .selector"',
                'expectedPosition' => null,
            ],
            'xpath expression' => [
                'elementExpression' => new ElementExpression('//h1', ElementExpressionType::XPATH_EXPRESSION),
                'position' => null,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => null,
            ],
            'xpath expression, position=1' => [
                'elementExpression' => new ElementExpression('//h1', ElementExpressionType::XPATH_EXPRESSION),
                'position' => 1,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => 1,
            ],
            'xpath expression, position=2' => [
                'elementExpression' => new ElementExpression('//h1', ElementExpressionType::XPATH_EXPRESSION),
                'position' => 2,
                'name' => null,
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1":2',
                'expectedPosition' => 2,
            ],
            'xpath expression, name' => [
                'elementExpression' => new ElementExpression('//h1', ElementExpressionType::XPATH_EXPRESSION),
                'position' => null,
                'name' => 'identifier name',
                'parentIdentifier' => null,
                'expectedIdentifierString' => '"//h1"',
                'expectedPosition' => null,
            ],
            'xpath expression, parent identifier' => [
                'elementExpression' => new ElementExpression('//h1', ElementExpressionType::XPATH_EXPRESSION),
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
