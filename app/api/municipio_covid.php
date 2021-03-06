<?php
/**
 * @brief Funções da API de acesso a tabela cidades
 * @file municipio_covid.php
 * @author Vinicius Pontes
 */

/**
 * @brief
 */
$app->get("/brasil/lista/cidades", function ($request, $response) {
    require_once ('db/dbconnect.php');

    try {
        $query = "SELECT m.municipio_id, m.nome, e.estado_sigla
        FROM municipio m
        INNER JOIN estado e ON m.estado_id = e.estado_id
        ORDER BY nome";
        $sth = $db->prepare($query);
        $sth->execute();
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
        ORDER BY obitos DESC, casos_acumulado DESC LIMIT 10";
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
        ORDER BY obitos ASC, casos_acumulado ASC LIMIT 10";
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
        pc.data_medicao,
        pc.obitos,
        pc.novos_obitos,
        pc.novos_casos,
        pc.casos_acumulado,
        pc.recuperados,
        pc.acompanhamento,
        Mortalidade(pc.obitos, p.populacao) AS mortalidade,
        Letalidade(pc.obitos, pc.casos_acumulado) AS letalidade
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

/**
 *
 */
$app->get("/brasil/obitos/diario", function ($request, $response) {
    require_once ('db/dbconnect.php');

    try {
        $query = "SELECT data_medicao, SUM(novos_obitos) AS obitos
        FROM municipio_covid
        GROUP BY data_medicao
        ORDER BY data_medicao DESC LIMIT 45";
        $sth = $db->prepare($query);
        $sth->execute();
        $data = $sth->fetchAll();
        //
        $status = 200;
        $result = array_reverse($data);
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
$app->get("/brasil/cidades/historico/{municipioId}", function ($request, $response) {
    require_once ('db/dbconnect.php');

    $municipioId = $request->getAttribute('municipioId');

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
        mc.novos_obitos,
        Mortalidade(mc.obitos, m.populacao) AS mortalidade,
        Letalidade(mc.obitos, mc.casos_acumulado) AS letalidade
        FROM municipio_covid mc
        INNER JOIN municipio m ON m.municipio_id = mc.municipio_id
        INNER JOIN estado e ON m.estado_id = e.estado_id
        INNER JOIN regiao r ON r.regiao_id = e.regiao_id
        WHERE mc.municipio_id = :municipioId
        ORDER BY municipio_covid_id DESC LIMIT 45";
        $sth = $db->prepare($query);
        $sth->execute(array(':municipioId' => $municipioId));
        $data = $sth->fetchAll();
        //
        $status = 200;
        $result = array_reverse($data);
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
$app->get("/brasil/estados", function ($request, $response) {
    require_once ('db/dbconnect.php');

    try {
        $query = "SELECT MAX(data_medicao) AS ultima_medicao FROM municipio_covid";
        $sth = $db->prepare($query);
        $sth->execute();
        $maxDate = $sth->fetch();

        $query = "SELECT
        e.estado_sigla,
        mc.data_medicao,
        SUM(mc.obitos) AS obitosEstado,
        SUM(m.populacao) AS populacaoEstado,
        Mortalidade(SUM(mc.obitos), SUM(m.populacao)) AS mortalidade
        FROM municipio_covid mc
        INNER JOIN municipio m ON m.municipio_id = mc.municipio_id
        INNER JOIN estado e ON e.estado_id = m.estado_id
        GROUP BY e.estado_sigla, mc.data_medicao
        HAVING mc.data_medicao = :data_medicao
        ORDER BY obitosEstado";
        $sth = $db->prepare($query);
        $sth->execute(array(':data_medicao' => $maxDate['ultima_medicao']));
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
