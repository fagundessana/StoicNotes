<?php
// Traz as funções que falam com a API
require __DIR__ . '/api.php';

$hoje    = date('Y-m-d');           // data de hoje
$data    = $_GET['data'] ?? $hoje;  // data escolhida no formulário (ou hoje)
$r       = null;                    // resultado (frase, autor...)
$erro    = '';                      // mensagem de erro para o usuário
$detalhe = '';                      // motivo técnico do erro

// 1) Valida o dado digitado pelo usuário
if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $data, $m) || !checkdate((int)$m[2], (int)$m[3], (int)$m[1])) {
    $erro = 'Data inválida. Escolha uma data no calendário.';
} elseif ($data > $hoje) {
    $erro = 'A reflexão de amanhã só começa amanhã. Escolha hoje ou uma data passada.';
} else {
    // 2) Pede a frase para a API (função que vamos criar no api.php)
    $r = obter_reflexao($data, $detalhe);
    if (!$r) {
        $erro = 'Não foi possível buscar a frase agora.';
    }
}

$dataBr = $r ? date('d/m/Y', strtotime($data)) : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reflexão do dia | Stoic Notes</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="topo"><a href="index.html">📅 Stoic Notes</a></header>

  <main class="pagina">

    <!-- Caderninho: só se preenche quando existe uma frase -->
    <aside class="caderno <?= $r ? 'aberto' : '' ?>">
      <div class="caderno-topo"></div>
      <div class="caderno-folha">
        <?php if ($r): ?>
          <p class="linha">Hoje é dia: <?= $dataBr ?></p>
          <p class="linha">Autor da frase: <?= htmlspecialchars($r['autor']) ?></p>
          <p class="nota">Sua próxima reflexão começa <span class="destaque">amanhã</span>!</p>
          <p class="nota pequena">Pratique hoje. Aprenda amanhã. Evolua a cada dia.</p>
        <?php endif; ?>
      </div>
    </aside>

    <section class="conteudo">
      <h1 class="faixa">🔖 Reflexão do dia</h1>

      <!-- FORMULÁRIO: campo de pesquisa (data) + botão de consulta -->
      <form class="busca" method="get" action="reflexao.php">
        <label for="data">Escolha o dia:</label>
        <input type="date" id="data" name="data" max="<?= $hoje ?>" value="<?= htmlspecialchars($data) ?>" required>
        <button type="submit" class="botao pequeno">Refletir</button>
      </form>
      <p class="aviso">As frases são atualizadas <span class="destaque">diariamente</span></p>

      <!-- ÁREA DE RESULTADOS -->
      <div class="cartao">
        <span class="lateral"><i></i></span>
        <div class="cartao-texto">

          <?php if ($erro): ?>
            <p class="erro"><?= htmlspecialchars($erro) ?></p>
            <?php if ($detalhe): ?>
              <p class="detalhe">Detalhe técnico: <?= htmlspecialchars($detalhe) ?></p>
            <?php endif; ?>

          <?php elseif ($r): ?>
            <blockquote>“<?= htmlspecialchars($r['traduzida']) ?>”</blockquote>
            <p class="autor">— <?= htmlspecialchars($r['autor']) ?></p>
            <p class="status"><?= htmlspecialchars(rotulo_status($r['status'])) ?></p>
            <?php if ($r['traduzida'] !== $r['original']): ?>
              <p class="original">Original: “<?= htmlspecialchars($r['original']) ?>”</p>
            <?php endif; ?>
          <?php endif; ?>

        </div>
      </div>

      <p class="lema">Uma frase por <span class="forte">dia</span>. Uma nova
        <span class="forte">perspectiva</span>. Um passo em direção a <span class="forte">si mesmo</span>.</p>
    </section>

  </main>

  <footer class="rodape">📓 | Stoic Notes foi desenvolvido por Ana Fagundes</footer>
</body>
</html>