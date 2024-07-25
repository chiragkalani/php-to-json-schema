<?php

namespace PHPToJsonSchema;

#[\Attribute] class JsonProperty
{
    public function __construct(
        public ?string $type = null,
        public ?string $format = null,
        public ?string $description = null,
        public bool $required = false,
        public $minimum = null,
        public $maximum = null,
        public ?array $enum = null,
        public ?int $minLength = null,
        public ?int $maxLength = null,
        public ?int $minItems = null,
        public ?int $maxItems = null
    )
    {
    }
}
