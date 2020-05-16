<?php
/**
 * @brief Funções da API de acesso a tabela admin
 * @file worldometers.php
 * @author Vinicius Pontes
 */
$app->post("/worldometers", function ($request, $response) {
    require_once ('db/dbconnect.php');

    $input = $request->getParsedBody();

    $response = VerifyRequiredParameters(array('selectedDate'), $input);
    if (isset($response['error'])) {
        return $this->response->withJson($response, 404);
    }

    $selectedDate = $input['selectedDate'];

    try {
        $query = "SELECT
        worldometers_id,
        measurement_date,
        country,
        total_cases,
        new_cases,
        total_deaths,
        new_deaths,
        total_recovered,
        active_cases,
        serious_cases,
        cases_per_million,
        deaths_per_million,
        total_tests,
        tests_per_million,
        population
        FROM worldometers
        WHERE measurement_date = :measurement_date
        ORDER BY total_deaths DESC";
        $sth = $db->prepare($query);
        $sth->execute(array(':measurement_date' => $selectedDate));
        $data = $sth->fetchAll();
        //
        $status = 200;
        $result = $data;
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    } catch (PDOException $e) {
        $status = 409;
        $result = array();
        $result["success"] = false;
        $result["message"] = $e->getMessage();
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    } catch (Exception $x) {
        $status = 409;
        $result = array();
        $result["success"] = false;
        $result["message"] = $x->getMessage();
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    }
})->add($middleware);


$app->post("/worldometers/country", function ($request, $response) {
    require_once ('db/dbconnect.php');

    $input = $request->getParsedBody();

    $response = VerifyRequiredParameters(array('country'), $input);
    if (isset($response['error'])) {
        return $this->response->withJson($response, 404);
    }

    $country = $input['country'];

    try {
        $query = "SELECT
        worldometers_id,
        measurement_date,
        country,
        total_cases,
        new_cases,
        total_deaths,
        new_deaths,
        total_recovered,
        active_cases,
        serious_cases,
        cases_per_million,
        deaths_per_million,
        total_tests,
        tests_per_million,
        population
        FROM worldometers
        WHERE country = :country
        ORDER BY worldometers_id DESC LIMIT 30";
        $sth = $db->prepare($query);
        $sth->execute(array(':country' => $country));
        $data = $sth->fetchAll();
        //
        $status = 200;
        $result = $data;
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    } catch (PDOException $e) {
        $status = 409;
        $result = array();
        $result["success"] = false;
        $result["message"] = $e->getMessage();
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    } catch (Exception $x) {
        $status = 409;
        $result = array();
        $result["success"] = false;
        $result["message"] = $x->getMessage();
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    }
})->add($middleware);

