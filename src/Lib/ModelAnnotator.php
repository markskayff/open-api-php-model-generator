<?php
namespace OpenApiPHP\ModelGenerator\Lib;

class ModelAnnotator{
  private $models = [];

  private $ref_queue = [];

  private $allOf_queue = [];

  public function __construct(){

  }

  public function parseSchema($key, $schema){
    // ---------------------------------------------------------------
    // Our own schema
    // ---------------------------------------------------------------
    $model = [];
    // ---------------------------------------------------------------
    // Check if for the abscense of the "type" property
    // This means we will have a $ref or an allOf
    // property definition
    // ---------------------------------------------------------------
    if(empty($schema['type'])){
      // ---------------------------------------------------------------
      // These two queued cases are similar:
      // They both means we are going to copy the propreties 
      // of one object into another
      // The only difference is the allOf will overwrite
      // any existing property for each subsequent item object in its
      // collection
      // ---------------------------------------------------------------
      if(array_key_exists('$ref', $schema)){
        // ---------------------------------------------------------------
        // Add this schema to the $ref_queue
        // ---------------------------------------------------------------
        $this->ref_queue[$key] = $schema;
        print "Schema queued as \$ref: $key" . PHP_EOL;
      }
      if(array_key_exists('allOf', $schema)){
        // ---------------------------------------------------------------
        // Add this schema to the $allOf_queue
        // ---------------------------------------------------------------
        $this->allOf_queue[$key] = $schema;
        print "Schema queued as allOf: $key" . PHP_EOL;
      }
      // ---------------------------------------------------------------
      // Return. We are gonna process the queued
      // objects at a later stage
      // ---------------------------------------------------------------
      return null;
    }
    // ---------------------------------------------------------------
    // Assign the type
    // ---------------------------------------------------------------
    $model['type'] = $schema['type'];
    // ---------------------------------------------------------------
    // There are special cases where the type is not an object
    // When there is no type, we have caught that above
    // and queued this elements.
    // ---------------------------------------------------------------
    if($model['type'] != "object"){
      // ---------------------------------------------------------------
      // TODO. This object is probably a string type
      // This is a special case and we will have to see
      // how to parse it later and how it will behave its validation 
      // process
      // ---------------------------------------------------------------
      print "Special model type: {$model['type']}" . PHP_EOL;
      return null;
    }
    // ---------------------------------------------------------------
    // Check for required keys
    // ---------------------------------------------------------------
    if(array_key_exists('required', $schema)){
      $model['required'] = $schema['required'];
    }
    // ---------------------------------------------------------------
    // Some schemas might not have a "properties" key
    // This means there will be probably an allOf key,
    // requiring the merging of the properties into a new object
    // ---------------------------------------------------------------
    if(!array_key_exists('properties', $schema)){
      // ---------------------------------------------------------------
      // Check for the allOf property key
      // ---------------------------------------------------------------
      if(array_key_exists('allOf', $schema)){
        // ---------------------------------------------------------------
        // TODO: This might be a property schema
        // having an allOf, and this requires a merging strategy
        // ---------------------------------------------------------------
        print "Note: $key requires a merging strategy" . PHP_EOL;
      }
      return null;
    }

    $model['properties'] = [];
    // ---------------------------------------------------------------
    // Finally process all schema properties
    // ---------------------------------------------------------------
    foreach($schema['properties'] as $key => $prop){
      // ---------------------------------------------------------------
      // Process one individual property
      // ---------------------------------------------------------------
      $p = $this->parseProperty($prop, $key);
      // ---------------------------------------------------------------
      // Add the processed property to our 'properties' object
      // for the model/schema
      // ---------------------------------------------------------------
      $model['properties'][$key] = $p;
    }
  }

  private function parseProperty($prop, $key){
    // ---------------------------------------------------------------
    // Check for a type key in the property.
    // If we don't have a type it means we have a $ref or an 
    // allOf property
    // ---------------------------------------------------------------
    if(!array_key_exists('type', $prop)){
      // ---------------------------------------------------------------
      // Check if the property has a $ref type
      // ---------------------------------------------------------------
       if(array_key_exists('$ref', $prop)){
        // ---------------------------------------------------------------
        // Property will be processed as being of this type
        // Get the model name from the $ref pointer
        // ---------------------------------------------------------------
        $model_name = $this->getModelFromPointer($prop['$ref']);
        print "Found '\$ref' in property $key. Model Name: $model_name" . PHP_EOL;
        // ---------------------------------------------------------------
        // Return this property as being of model_name type
        // ---------------------------------------------------------------
        return ['type' => $model_name];
      }
      // ---------------------------------------------------------------
      // Check if the property has an allOf key
      // ---------------------------------------------------------------
      if(array_key_exists('allOf', $prop)){
        print "Found 'allOf' in property $key" . PHP_EOL;
      }
      return;
    }
    // ---------------------------------------------------------------
    // We do have a type.
    // Process each particular type
    // ---------------------------------------------------------------
    $t = $prop['type'];
    switch ($t) {
      case 'array':
        return $this->processArrayProperty($prop);

      case 'string':
        return $this->processStringProperty($prop);

      case 'integer':
      case 'number':
        return $this->processIntegerNumberProperty($prop);

      case 'boolean':
        return ['type' => 'boolean'];

      default:
       
        break;
    }
  }

  private function processIntegerNumberProperty($prop){
    // ---------------------------------------------------------------
    // Type will be integer or number
    // ---------------------------------------------------------------
    $p = ['type' => $prop['type'] == "number"? "double" : "integer"];

    return $p;
  }

  /**
   * Process String Property
   * 
   */
  private function processStringProperty($prop){
    $p = ['type' => "string"];
    if(array_key_exists('enum', $prop)){
      $p['enum'] = $prop['enum'];
    }
    return $p;
  }

  /**
   * Process Array Property
   * 
   */
  private function processArrayProperty($prop){
    $p = ['type' => 'array'];
    // ---------------------------------------------------------------
    // Check if we have a $ref in items
    // ---------------------------------------------------------------
    if(array_key_exists('$ref', $prop['items'])){
      $p['items_type'] = $this->getModelFromPointer($prop['items']['$ref']);
    }
    // ---------------------------------------------------------------
    // Check if we have a type in items
    // ---------------------------------------------------------------
    else if(array_key_exists('type', $prop['items'])){
      $p['items_type'] = $prop['items']['type'];
    }
    // ---------------------------------------------------------------
    // Check for an enum property in imtes
    // ---------------------------------------------------------------
    if(array_key_exists('enum', $prop['items'])){
      $p['enum'] = $prop['items']['enum'];
    }
    return $p;
  }

  /**
   * Get Model from Pointer
   * Finds the basename from a pointer string.
   */
  private function getModelFromPointer($pointer){
    $info = pathinfo($pointer);
    return $info['basename'];
  }
}