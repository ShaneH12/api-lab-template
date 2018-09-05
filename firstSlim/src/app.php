<?php
namespace holbein\Slim;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';

class App
{

private $app;
public function __construct($db){

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = 'root';
$config['db']['dbname'] = 'apidb';

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['db'] = $db;

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('./logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};


$app->get('/player', function (Request $request, Response $response) {
    $this->logger->addInfo("GET /player");
    $player = $this->db->query('SELECT * from player')->fetchAll();
    $jsonResponse = $response->withJson($player);

    return $jsonResponse;
});

$app->get('/player/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("GET /player/".$id);
    $person = $this->db->query('SELECT * from player where id='.$id)->fetch();
    $jsonResponse = $response->withJson($person);

    return $jsonResponse;
});

$app->put('/player/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("PUT /player/".$id);

    // build query string
    $updateString = "UPDATE people SET ";
    $fields = $request->getParsedBody();
    $keysArray = array_keys($fields);
    $last_key = end($keysArray);
    foreach($fields as $field => $value) {
      $updateString = $updateString . "$field = '$value'";
      if ($field != $last_key) {
        // conditionally add a comma to avoid sql syntax problems
        $updateString = $updateString . ", ";
      }
    }
    $updateString = $updateString . " WHERE id = $id;";

    // execute query
    $this->db->exec($updateString);
    // return updated record
    $person = $this->db->query('SELECT * from player where id='.$id)->fetch();
    $jsonResponse = $response->withJson($person);

    return $jsonResponse;
});
$app->delete('/player/{id}', function (Request $request, Response $response, array $args) {
  $id = $args['id'];
  $this->logger->addInfo("DELETE /player/".$id);
  $person = $this->db->exec('DELETE FROM player where id='.$id);
  $jsonResponse = $response->withJson($person);

  return;
});

$this->app = $app;

}
/**
* Get an instance of the application.
*
* @return \Slim\App
*/
public function get()
{
  return $this->app;
}
}
