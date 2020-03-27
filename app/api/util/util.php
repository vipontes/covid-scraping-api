<?php

/**
 * @brief Verifica se os parâmetros obrigatórios foram passados
 */
function VerifyRequiredParameters($required_fields, $request_params)
{
    $error = false;
    $error_fields = array();

    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) /* || strlen(trim($request_params[$field])) <= 0 */) {
            $error = true;
            $error_fields[] = $field;
        }
    }
    if ($error) {
        // Required field(s) are missing or empty
        return array(
            'error' => true,
            'message' => 'O(s) campo(s) [' . implode(', ', $error_fields) . '] é(são) obrigatório(s).',
        );
    }

    // return appropriate response when successful?
    return array(
        'success' => true,
    );
}
