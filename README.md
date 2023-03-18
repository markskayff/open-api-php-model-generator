# Open API v3 PHP Model generator
---

This version of the project aims to generate PHP Model classes from an Open API v3 json schema. 

The generator is aiming specifically towards **"#components/schemas"**. 

Future versions of this project might cover further generators for fields under the "components" key.

The Open API Specification (from now "OAS") documentation can be found here: https://spec.openapis.org/oas/latest.html

### General idea about the project

The idea is to have "a models base" available that you can re-use throughout your own API building process.

A single PHP Model class will be generated from every **"#components/schema"** object in the oas json file.

Each class will have the ability to:

- Validate its required properties
- Validate each property type
- Validate the property value against any constraints defined on it
- Make properties referencing other Model types to validate themselves.

### About the components object in OAS

About this "components" object the OAS spec says:

"Holds a set of reusable objects for different aspects of the OAS. All objects defined within the components object will have no effect on the API unless they are explicitly referenced from properties outside the components object."

### Property validation

The property validation follows the validation schema described in the [JSON Schema Validation Draft](https://json-schema.org/draft/2020-12/json-schema-validation.html#name-a-vocabulary-for-structural)

