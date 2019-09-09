<?php

namespace webignition\BasilTestIdentifierFactory;

use webignition\BasilModel\Identifier\ElementIdentifier;
use webignition\BasilModel\Identifier\ElementIdentifierInterface;
use webignition\BasilModel\Identifier\IdentifierInterface;
use webignition\BasilModel\Identifier\IdentifierTypes;
use webignition\BasilModel\Identifier\ReferenceIdentifier;
use webignition\BasilModel\Value\ElementExpressionInterface;
use webignition\BasilModel\Value\PageElementReference;

class TestIdentifierFactory
{
    public static function createElementIdentifier(
        ElementExpressionInterface $elementExpression,
        int $position = null,
        ?string $name = null,
        ?ElementIdentifierInterface $parentIdentifier = null
    ): ElementIdentifierInterface {
        $identifier = new ElementIdentifier($elementExpression, $position);

        if (null !== $name) {
            $identifier = $identifier->withName($name);
        }

        if ($identifier instanceof ElementIdentifierInterface &&
            $parentIdentifier instanceof ElementIdentifierInterface) {
            $identifier = $identifier->withParentIdentifier($parentIdentifier);
        }

        if ($identifier instanceof ElementIdentifierInterface) {
            return $identifier;
        }

        throw new \RuntimeException('Identifier is not an ElementIdentifierInterface instance');
    }

    public static function createPageElementReferenceIdentifier(
        PageElementReference $pageElementReference,
        ?string $name = null
    ): IdentifierInterface {
        $identifier = new ReferenceIdentifier(
            IdentifierTypes::PAGE_ELEMENT_REFERENCE,
            $pageElementReference
        );

        if (null !== $name) {
            $identifier = $identifier->withName($name);
        }

        if ($identifier instanceof IdentifierInterface) {
            return $identifier;
        }

        throw new \RuntimeException('Identifier is not an IdentifierInterface instance');
    }
}
