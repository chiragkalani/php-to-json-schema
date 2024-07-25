<?php

namespace PHPToJsonSchema;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class SchemaGenerator
{
    /**
     * @throws ReflectionException
     */
    function generate(string $className): array
    {
        $reflector = new ReflectionClass($className);
        $schemaAttributes = $reflector->getAttributes(JsonSchema::class);
        $schema = ['type' => 'object', 'properties' => []];

        if (!empty($schemaAttributes)) {
            $schemaAttr = $schemaAttributes[0]->newInstance();
            $schema['title'] = $schemaAttr->title;
            if ($schemaAttr->description) {
                $schema['description'] = $schemaAttr->description;
            }
        }

        $requiredFields = [];
        foreach ($reflector->getProperties() as $property) {
            $attributes = $property->getAttributes(JsonProperty::class);
            if (!empty($attributes)) {
                $attribute = $attributes[0]->newInstance();
                $propertySchema = [
                    'type' => $attribute->type ?? $this->detectType($property),
                    'description' => $attribute->description
                ];
                if ($attribute->format) {
                    $propertySchema['format'] = $attribute->format;
                }
                if ($attribute->minimum !== null) {
                    $propertySchema['minimum'] = $attribute->minimum;
                }
                if ($attribute->maximum !== null) {
                    $propertySchema['maximum'] = $attribute->maximum;
                }
                if ($attribute->enum !== null) {
                    $propertySchema['enum'] = $attribute->enum;
                }
                if ($attribute->minLength !== null) {
                    $propertySchema['minLength'] = $attribute->minLength;
                }
                if ($attribute->maxLength !== null) {
                    $propertySchema['maxLength'] = $attribute->maxLength;
                }
                if ($attribute->minItems !== null) {
                    $propertySchema['minItems'] = $attribute->minItems;
                }
                if ($attribute->maxItems !== null) {
                    $propertySchema['maxItems'] = $attribute->maxItems;
                }

                $schema['properties'][$property->getName()] = $propertySchema;
                if ($attribute->required) {
                    $requiredFields[] = $property->getName();
                }
            }
        }

        if (!empty($requiredFields)) {
            $schema['required'] = $requiredFields;
        }

        return $schema;
    }

    private function detectType(\ReflectionProperty $property): string
    {
        $type = $property->getType();

        if ($type instanceof ReflectionNamedType) {
            $typeName = $type->getName();

            return match ($typeName) {
                'string' => 'string',
                'int', 'integer' => 'integer',
                'bool', 'boolean' => 'boolean',
                'float', 'double' => 'number',
                'array' => 'array',
                default => 'object',
            };
        }

        return 'object';
    }

}
