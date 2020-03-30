<?php
$middleware = function ($request, $response, $next) {
    $phpHeaders = getallheaders();
    $apiKey = $phpHeaders['token'];
    $status = 200;
    $result = array();

    if (!isset($apiKey)) {
        $result["success"] = false;
        $result["message"] = "apiKey não foi informada " . $apiKey;
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    }

    try {
        $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        $now = new DateTime();
        $mins = $now->getOffset() / 60;
        $sgn = ($mins < 0 ? -1 : 1);
        $mins = abs($mins);
        $hrs = floor($mins / 60);
        $mins -= $hrs * 60;
        $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
        
        // 
        $db->exec("SET time_zone='$offset';");

        $query = "SELECT api_key_id FROM api_key WHERE api_key = :api_key";
        $sth = $db->prepare($query);
        $sth->execute(array(':api_key' => $apiKey));
        $data = $sth->fetch();

        if ($data != false) {
            $response = $next($request, $response);
            return $response;
        } else {
            $status = 401;
            $result["success"] = false;
            $result["message"] = "apiKey inválida";
            header('Content-Type: application/json');
            return $this->response->withJson($result, $status);
        }
    } catch (PDOException $e) {
        $status = 409;
        $result["success"] = false;
        $result["message"] = $e->getMessage();
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    } catch (Exception $x) {
        $status = 409;
        $result["success"] = false;
        $result["message"] = $x->getMessage();
        header('Content-Type: application/json');
        return $this->response->withJson($result, $status);
    }
};
