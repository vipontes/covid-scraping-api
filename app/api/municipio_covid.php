<?php
/**
 * @brief Funções da API de acesso a tabela cidades
 * @file municipio_covid.php
 * @author Vinicius Pontes
 */

/**
 * @brief
 */
$app->get("/brasil/cidades/{data}", function ($request, $response) {
    require_once ('db/dbconnect.php');

    $day = $request->getAttribute('data');

    try {
        $query = "SELECT
        mc.municipio_covid_id,
        mc.municipio_id,
        m.nome,
        m.populacao,
        e.estado_id,
        e.estado_sigla,
        r.regiao_descricao,
        mc.data_medicao,
        mc.obitos,
        mc.novos_casos,
        mc.casos_acumulado,
        (mc.obitos / (m.populacao / 100000)) AS mortalidade,
        (mc.obitos / casos_acumulado) * 100 AS letalidade
        FROM municipio_covid mc
        INNER JOIN municipio m ON m.municipio_id = mc.municipio_id
        INNER JOIN estado e ON m.estado_id = e.estado_id
        INNER JOIN regiao r ON r.regiao_id = e.regiao_id
        WHERE mc.data_medicao = :day
        ORDER BY obitos DESC";
        $sth = $db->prepare($query);
        $sth->execute(array(':day' => $day));
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
