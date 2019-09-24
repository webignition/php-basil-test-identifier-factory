<?php

namespace webignition\BasilTestIdentifierFactory;

use webignition\BasilModel\Identifier\DomIdentifier;
use webignition\BasilModel\Identifier\DomIdentifierInterface;
use webignition\BasilModel\Identifier\ReferenceIdentifier;
use webignition\BasilModel\Identifier\ReferenceIdentifierInterface;
use webignition\BasilModel\Value\PageElementReference;

class TestIdentifierFactory
{
    public static function createElementIdentifier(
        string $locator,
        int $position = null,
        ?string $name = null,
        ?DomIdentifierInterface $parentIdentifier = null
    ): DomIdentifierInterface {
        $identifier = new DomIdentifier($locator);

        if (null !== $position) {
            $identifier = $identifier->withOrdinalPosition($position);
        }

        if (null !== $name) {
            $identifier = $identifier->withName($name);
        }

        if ($identifier instanceof DomIdentifierInterface && $parentIdentifier instanceof DomIdentifierInterface) {
            $identifier = $identifier->withParentIdentifier($parentIdentifier);
        }

        if ($identifier instanceof DomIdentifierInterface) {
            return $identifier;
        }

        throw new \RuntimeException('Identifier is not an ElementIdentifierInterface instance');
    }

    public static function createPageElementReferenceIdentifier(
        PageElementReference $pageElementReference,
        ?string $name = null
    ): ReferenceIdentifierInterface {
        $identifier = ReferenceIdentifier::createPageElementReferenceIdentifier(
            $pageElementReference
        );

        if (null !== $name) {
            $identifier = $identifier->withName($name);
        }

        if ($identifier instanceof ReferenceIdentifierInterface) {
            return $identifier;
        }

        throw new \RuntimeException('Identifier is not an IdentifierInterface instance');
    }
}
