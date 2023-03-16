<?php
namespace OpenApiPHP\ModelGenerator\Lib;

class ModelGenerator{
  private $parser = null;


  public function __construct($input, $output, $ns, $overwrite=true){
    $this->parser = new OpenApiDocumentParser($input);
  }
}