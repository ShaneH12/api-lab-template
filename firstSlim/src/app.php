<?php
namespace feather\firstSlim;
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

    if($person){
      $response =  $response->withJson($person);
    } else {
      $errorData = array('status' => 404, 'message' => 'not found');
      $response = $response->withJson($errorData, 404);
    }

    return $jsonResponse;
});

$app->post('/player', function (Request $request, Response $response) {
    $this->logger->addInfo("POST /player/");

    // build query string
    $createString = "INSERT INTO player ";
    $fields = $request->getParsedBody();
    $keysArray = array_keys($fields);
    $last_key = end($keysArray);
    $values = '(';
    $fieldNames = '(';
    foreach($fields as $field => $value) {
      $values = $values . "'"."$value"."'";
      $fieldNames = $fieldNames . "$field";
      if ($field != $last_key) {
        // conditionally add a comma to avoid sql syntax problems
        $values = $values . ", ";
        $fieldNames = $fieldNames . ", ";
      }
    }
    $values = $values . ')';
    $fieldNames = $fieldNames . ') VALUES ';
    $createString = $createString . $fieldNames . $values . ";";
    // execute query
    try {
      $this->db->exec($createString);
    } catch (\PDOException $e) {
      var_dump($e);
      $errorData = array('status' => 400, 'message' => 'Invalid data provided to create person');
      return $response->withJson($errorData, 400);
    }
    // return updated record
    $person = $this->db->query('SELECT * from player ORDER BY id desc LIMIT 1')->fetch();
    $jsonResponse = $response->withJson($person);

    return $jsonResponse;
});

$app->put('/player/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("PUT /player/".$id);

    // build query string
    $updateString = "UPDATE player SET ";
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

      try {
    $this->db->exec($updateString);
  } catch (\PDOException $e) {
    $errorData = array('status' => 400, 'message' => 'Invalid data provided to update');
    return $response->withJson($errorData, 400);

  }

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
