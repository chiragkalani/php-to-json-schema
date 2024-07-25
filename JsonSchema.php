<?php

namespace PHPToJsonSchema;

#[\Attribute] class JsonSchema
{
    public function __construct(
        public string $title,
        public ?string $description = null
    )
    {
    }
}
