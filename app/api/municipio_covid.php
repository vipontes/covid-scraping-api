<?php
/**
 * @brief Funções da API de acesso a tabela cidades
 * @file municipio_covid.php
 * @author Vinicius Pontes
 */

/**
 * @brief
 */
$app->get("/brasil/cidades", function ($request, $response) {
    require_once ('db/dbconnect.php');

    try {
        $query = "SELECT MAX(data_medicao) AS ultima_medicao FROM municipio_covid";
        $sth = $db->prepare($query);
        $sth->execute();
        $maxDate = $sth->fetch();

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
        mc.novos_obitos,
        Mortalidade(mc.obitos, m.populacao) AS mortalidade,
        Letalidade(mc.obitos, mc.casos_acumulado) AS letalidade
        FROM municipio_covid mc
        INNER JOIN municipio m ON m.municipio_id = mc.municipio_id
        INNER JOIN estado e ON m.estado_id = e.estado_id
        INNER JOIN regiao r ON r.regiao_id = e.regiao_id
        WHERE mc.data_medicao = :day
        ORDER BY obitos DESC";
        $sth = $db->prepare($query);
        $sth->execute(array(':day' => $maxDate['ultima_medicao']));
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

/**
 *
 */
$app->get("/brasil/cidades/criticas", function ($request, $response) {
    require_once ('db/dbconnect.php');

    try {
        $query = "SELECT MAX(data_medicao) AS ultima_medicao FROM municipio_covid";
        $sth = $db->prepare($query);
        $sth->execute();
        $maxDate = $sth->fetch();

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
        mc.novos_obitos,
        Mortalidade(mc.obitos, m.populacao) AS mortalidade,
        Letalidade(mc.obitos, mc.casos_acumulado) AS letalidade
        FROM municipio_covid mc
        INNER JOIN municipio m ON m.municipio_id = mc.municipio_id
        INNER JOIN estado e ON m.estado_id = e.estado_id
        INNER JOIN regiao r ON r.regiao_id = e.regiao_id
        WHERE m.populacao >= 100000 AND mc.data_medicao = :day
        ORDER BY obitos DESC, populacao DESC LIMIT 10";
        $sth = $db->prepare($query);
        $sth->execute(array(':day' => $maxDate['ultima_medicao']));
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

/**
 *
 */
$app->get("/brasil/cidades/controladas", function ($request, $response) {
    require_once ('db/dbconnect.php');

    try {
        $query = "SELECT MAX(data_medicao) AS ultima_medicao FROM municipio_covid";
        $sth = $db->prepare($query);
        $sth->execute();
        $maxDate = $sth->fetch();

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
        mc.novos_obitos,
        Mortalidade(mc.obitos, m.populacao) AS mortalidade,
        Letalidade(mc.obitos, mc.casos_acumulado) AS letalidade
        FROM municipio_covid mc
        INNER JOIN municipio m ON m.municipio_id = mc.municipio_id
        INNER JOIN estado e ON m.estado_id = e.estado_id
        INNER JOIN regiao r ON r.regiao_id = e.regiao_id
        WHERE m.populacao >= 100000 AND mc.data_medicao = :day
        ORDER BY obitos ASC, populacao DESC LIMIT 10";
        $sth = $db->prepare($query);
        $sth->execute(array(':day' => $maxDate['ultima_medicao']));
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

/**
 *
 */
$app->get("/brasil/populacao", function ($request, $response) {
    require_once ('db/dbconnect.php');

    try {
        $query = "SELECT MAX(data_medicao) AS ultima_medicao FROM populacao_covid";
        $sth = $db->prepare($query);
        $sth->execute();
        $maxDate = $sth->fetch();

        $query = "SELECT
        p.codigo_pais,
        p.pais_nome,
        p.populacao,
        pc.obitos,
        pc.novos_obitos,
        pc.novos_casos,
        pc.casos_acumulado,
        pc.recuperados,
        pc.acompanhamento
        FROM populacao_covid pc
        INNER JOIN populacao p ON p.codigo_pais = pc.codigo_pais
        WHERE pc.data_medicao = :day AND p.codigo_pais = 55";
        $sth = $db->prepare($query);
        $sth->execute(array(':day' => $maxDate['ultima_medicao']));
        $data = $sth->fetch();
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
