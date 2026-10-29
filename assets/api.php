
<?php
// ============================================================
// api.php: APIs públicas, requisições HTTP e JSON
// ============================================================

date_default_timezone_set('America/Sao_Paulo');

define('API_FRASES', 'https://www.onestoicquote.com/api/quote');
define('API_TRADUCAO', 'https://api.mymemory.translated.net/get');


// ------------------------------------------------------------
// 1) Faz uma requisição GET e devolve o JSON como array
// ------------------------------------------------------------
function pegar_json(string $url, ?string &$erro = null): ?array
{
    $corpo = false;
    $status = 0;

    // Preferência: cURL
    if (function_exists('curl_init')) {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ],
            CURLOPT_USERAGENT => 'StoicNotes/1.0'
        ]);

        $corpo = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($corpo === false) {
            $erro = 'Erro de conexão cURL: ' . curl_error($ch);
        }

        curl_close($ch);

    } else {
        // Alternativa: file_get_contents
        if (!filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN)) {
            $erro = 'A configuração allow_url_fopen está desativada '
                  . 'e a extensão cURL não está disponível no PHP.';
            return null;
        }

        $contexto = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 15,
                'ignore_errors' => true,
                'header' =>
                    "Accept: application/json\r\n" .
                    "User-Agent: StoicNotes/1.0\r\n"
            ]
        ]);

        $corpo = @file_get_contents($url, false, $contexto);

        if ($corpo === false) {
            $e = error_get_last();
            $erro = 'Falha ao acessar a URL: '
                  . ($e['message'] ?? 'erro desconhecido');
        } else {
            // Verifica o status HTTP retornado pelo servidor.
            $cabecalhos = $http_response_header ?? [];

            foreach ($cabecalhos as $cabecalho) {
                if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $cabecalho, $m)) {
                    $status = (int) $m[1];
                }
            }
        }
    }

    if ($corpo === false) {
        return null;
    }

    if ($status < 200 || $status >= 300) {
        $erro = "A API respondeu com status HTTP $status.";
        return null;
    }

    $dados = json_decode($corpo, true);

    if (!is_array($dados)) {
        $erro = 'A resposta recebida não contém um JSON válido.';
        return null;
    }

    return $dados;
}


// ------------------------------------------------------------
// 2) Busca a frase estoica de uma data
// ------------------------------------------------------------
function buscar_frase(string $data, ?string &$erro = null): ?array
{
    $url = API_FRASES . '?' . http_build_query([
        'date' => $data
    ]);

    $json = pegar_json($url, $erro);

    if ($json === null) {
        return null;
    }

    if (isset($json['error'])) {
        $erro = 'A API recusou a consulta: '
              . (is_string($json['error'])
                  ? $json['error']
                  : json_encode($json['error']));
        return null;
    }

    if (empty($json['quote'])) {
        $erro = 'A resposta da API não contém o campo "quote".';
        return null;
    }

    return [
        'frase' => trim((string) $json['quote']),
        'autor' => trim((string) ($json['author'] ?? 'Autor desconhecido')),
        'status' => $json['source']['status'] ?? 'unverified',
        'link' => $json['url'] ?? ''
    ];
}


// ------------------------------------------------------------
// 3) Traduz a frase para português
// Se falhar, mantém o texto original.
// ------------------------------------------------------------
function traduzir(string $texto): string
{
    $url = API_TRADUCAO . '?' . http_build_query([
        'q' => $texto,
        'langpair' => 'en|pt-BR'
    ]);

    $erro = null;
    $json = pegar_json($url, $erro);

    if ($json === null) {
        return $texto;
    }

    $ok = isset($json['responseStatus'])
       && (int) $json['responseStatus'] === 200;

    $traducao = $json['responseData']['translatedText'] ?? '';

    if ($ok && is_string($traducao) && $traducao !== '') {
        return html_entity_decode(
            $traducao,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );
    }

    return $texto;
}


// ------------------------------------------------------------
// 4) Obtém a reflexão e utiliza o cache diário
// ------------------------------------------------------------
function obter_reflexao(string $data, ?string &$erro = null): ?array
{
    // Valida a data antes de utilizá-la no caminho do cache.
    $dataObjeto = DateTimeImmutable::createFromFormat('!Y-m-d', $data);

    if (!$dataObjeto || $dataObjeto->format('Y-m-d') !== $data) {
        $erro = 'Data inválida. Utilize o formato AAAA-MM-DD.';
        return null;
    }

    $pasta = __DIR__ . '/cache';
    $arquivo = $pasta . '/' . $data . '.json';

    // Se já existe uma reflexão salva para esse dia, reutiliza.
    if (is_file($arquivo)) {
        $conteudo = @file_get_contents($arquivo);
        $salvo = $conteudo !== false
            ? json_decode($conteudo, true)
            : null;

        if (
            is_array($salvo)
            && isset($salvo['original'], $salvo['traduzida'], $salvo['autor'])
        ) {
            return $salvo;
        }
    }

    // Busca uma nova frase na API.
    $api = buscar_frase($data, $erro);

    if ($api === null) {
        return null;
    }

    $reflexao = [
        'autor' => $api['autor'],
        'original' => $api['frase'],
        'traduzida' => traduzir($api['frase']),
        'status' => $api['status'],
        'link' => $api['link']
    ];

    // Salva a reflexão para evitar requisições repetidas.
    if (!is_dir($pasta)) {
        @mkdir($pasta, 0755, true);
    }

    if (is_dir($pasta) && is_writable($pasta)) {
        $json = json_encode(
            $reflexao,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if ($json !== false) {
            @file_put_contents($arquivo, $json, LOCK_EX);
        }
    }

    return $reflexao;
}


// ------------------------------------------------------------
// 5) Traduz o status de verificação da frase
// ------------------------------------------------------------
function rotulo_status(string $status): string
{
    return [
        'verified' => 'Atribuição verificada',
        'unverified' => 'Atribuição ainda não verificada',
        'disputed' => 'Atribuição contestada',
        'misattributed' => 'Atribuição incorreta'
    ][$status] ?? 'Atribuição ainda não verificada';
}
