<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";
// ---------------------------------------------------------------
// Init CLIMate
// ---------------------------------------------------------------
$climate = new League\CLImate\CLImate;

$climate->lightCyan("Open API PHP Model Generator")->br();
$climate->out("For usage help do -h or --help");

// ---------------------------------------------------------------
// Define the command line arguments
// ---------------------------------------------------------------
$climate->arguments->add([
  'input' => [
    'prefix' => 'i',
    'longPrefix' => 'input',
    'description' => "Path to the open api specification json file",
    'required' => true,
  ],
  'output' => [
    'prefix' => 'o',
    'longPrefix' => 'out',
    'description' => "Output dir where to put the generated models",
    'required' => true
  ],
  'model-ns' => [
    'longPrefix' => 'model-ns',
    'description' => "Model namespace. The namespace of the Model files",
    'required' => true
  ],
  'no-overwrite' => [
    'longPrefix' => 'no-overwrite',
    'description' => "Tells model generator not to overwrite existing files",
    'noValue' => true
  ],
  'help' => [
    'prefix' => 'h',
    'longPrefix' => 'help',
    'help' => "This help menu",
    'noValue' => true
  ]
]);
// ---------------------------------------------------------------
// If help is requested, print it
// ---------------------------------------------------------------
if($climate->arguments->defined('help')){
  print $climate->usage();
  exit;
}

try {
  // ---------------------------------------------------------------
  // Parse the CLIMate arguments
  // ---------------------------------------------------------------
  $climate->arguments->parse();

  $ifile     = $climate->arguments->get('input');
  $out_dir   = $climate->arguments->get('output');
  $model_ns  = $climate->arguments->get('model-ns');
  $overwrite = $climate->arguments->get('no-overwrite')?? false;

} catch (League\CLImate\Exceptions\InvalidArgumentException $e) {
  $climate->red()->inline("Invalid Argument:  ");
  $climate->out("Bad input arguments passed to script");
  $climate->out("Pass -h or --help to see the options");
}

