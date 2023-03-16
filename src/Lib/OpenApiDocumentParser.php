<?php
namespace OpenApiPHP\ModelGenerator\Lib;

class OpenApiDocumentParser{
  private $document=null;

  /**
   *
   * @throws JsonException, InvalidArgumentException
   */
  public function __construct(string $path){
    // ---------------------------------------------------------------
    // Check if $path exists as a file
    // ---------------------------------------------------------------
    if(!file_exists($path)){
      throw new \InvalidArgumentException("Path is an invalid file path");
    }
    // ---------------------------------------------------------------
    // Check if path is a valid file
    // ---------------------------------------------------------------
    if(!is_file($path)){
      throw new \InvalidArgumentException("Path is not a valid file.");
    }
    $this->document = json_decode(file_get_contents($path), true, JSON_THROW_ON_ERROR);
  }

  public function getJsonPath($path){
    // ---------------------------------------------------------------
    // Represent the json keys tree
    // with an flat array, being the sucessive elements a 
    // representation of the nesting json.
    // ---------------------------------------------------------------
    $tree = explode(".", $path);
    // ---------------------------------------------------------------
    // Copy the document into a $current buffer
    // ---------------------------------------------------------------
    $current = $this->document;

    foreach ($tree as $key) {
      if(!array_key_exists($key, $current)){
        throw new \InvalidArgumentException('Bad $path key in getJsonPath');
      }
      // ---------------------------------------------------------------
      // Copy the current key value back into $current
      // ---------------------------------------------------------------
      $current = $current[$key];
    }
    // ---------------------------------------------------------------
    // The final value of current will be the leaf
    // we are looking for
    // ---------------------------------------------------------------
    return $current;
  }
}