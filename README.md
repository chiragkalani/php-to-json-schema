# Introduction

This library is a PHP implementation of the [JSON Schema](https://json-schema.org/) standard. It allows you to generate
JSON Schema from PHP classes

# Usage

```php

#[JsonSchema(title: "Person", description: "A person object")]
class Person
{
    #[JsonProperty(type: "string", description: "The person's name", required: true, minLength: 1, maxLength: 100)]
    public string $name;

    #[JsonProperty(type: "integer", description: "The person's age", required: true, minimum: 0, maximum: 120)]
    public int $age;

    #[JsonProperty(type: "string", description: "The person's email", required: false, format: "email")]
    public ?string $email;

    #[JsonProperty(type: "array", description: "The person's hobbies", required: false, minItems: 1, maxItems: 10)]
    public array $hobbies;

    #[JsonProperty(type: "string", description: "The person's gender", required: false, enum: ["male", "female", "other"])]
    public ?string $gender;
}

$generator = new SchemaGenerator();
$schema = $generator->generate(Person::class);
```
