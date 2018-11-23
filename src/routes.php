<?php

use Slim\Http\Request;
use Slim\Http\Response;

require 'db.php';

// Routes

/*
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    //return $this->renderer->render($response, 'index.phtml', $args);
    $response->getBody()->write("Hello, $name");
    return $response;
});
*/

// All customers
$app->get('/api/customers', function(Request $request, Response $response, array $args) {
    //$name = $args['name'];
    // Sample log message
    $this->logger->info("Slim-Skeleton '/api/customers' route");

    $sql = "SELECT * FROM customer_contact";
    try {
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});

// Get Single Customer
$app->get('/api/customers/{id}', function(Request $request, Response $response, array $args) {

    $this->logger->info("Slim-Skeleton '/api/customers/' route");

    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM customer_contact WHERE id=$id";
    try {
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});

// Add customer
$app->post('/api/customers/add', function(Request $request, Response $response, array $args) {
    $name = $request->getParam('name');
    $tel = $request->getParam('tel');
    $email = $request->getParam('email');
    $address = $request->getParam('address');

    $sql = "INSERT INTO customer_contact (name, tel, email, address) VALUES (:name, :tel, :email, :address)";
    try {
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);

        $stmt->execute();
        echo '{"notice": {"text": "Customer Added"}}';
    } catch(PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});

// Update customer
$app->put('/api/customers/update/{id}', function(Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $name = $request->getParam('name');
    $tel = $request->getParam('tel');
    $email = $request->getParam('email');
    $address = $request->getParam('address');

    $sql = "UPDATE customer_contact SET 
            name = :name, 
            tel = :tel, 
            email = :email, 
            address = :address
            WHERE id = $id";
    try {
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);

        $stmt->execute();
        echo '{"notice": {"text": "Customer Updated"}}';
    } catch(PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});

// delete customer
$app->delete('/api/customers/delete/{id}', function(Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM customer_contact WHERE id = $id";
    try {
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Customer Deleted"}}';
    } catch(PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});