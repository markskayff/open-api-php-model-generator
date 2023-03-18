# Open API v3 PHP Model generator
---

This version of the project aims to generate PHP Model classes from an Open API v3 json schema. 

The generator is specifically aiming towards **"components/schemas"**. 

Might be in future versions of this project we might cover more fields under the "components" key.

The Open API Specification (from now "OAS") documentation can be found here: https://spec.openapis.org/oas/latest.html

### About the components object in OAS

About this "components" object the OAS specs say:

"Holds a set of reusable objects for different aspects of the OAS. All objects defined within the components object will have no effect on the API unless they are explicitly referenced from properties outside the components object."

### Property validation

The property validation follows the validation schema described in the [JSON Schema Validation Draft](https://json-schema.org/draft/2020-12/json-schema-validation.html#name-a-vocabulary-for-structural)

