<?php
namespace OpenApiPHP\ModelGenerator\Lib;

use League\CLImate\CLImate;

class ModelGenerator{
  private $parser = null;

  private $schemas = [];

  private $annotator = null;
  /**
   *
   * CLIMate object
   */
  private $climate = null;

  public function __construct($input, $output, $ns, $overwrite=true){
    $this->parser = new OpenApiDocumentParser($input);

    $this->annotator = new ModelAnnotator;
    // ---------------------------------------------------------------
    // Init Climate
    // ---------------------------------------------------------------
    $this->climate = new CLImate;
  }

  public function run(){
    // ---------------------------------------------------------------
    // Capture all schemas
    // ---------------------------------------------------------------
    $this->schemas = $this->parser->getJsonPath('components.schemas');
    // ---------------------------------------------------------------
    // Count schemas
    // ---------------------------------------------------------------
    $count = count($this->schemas);

    $this->climate->out("Found $count schemas");

    $this->processSchemas();
  }

  private function processSchemas(){
    foreach ($this->schemas as $key => $schema) {
      $this->annotator->parseSchema($key, $schema);
    }
  }
}