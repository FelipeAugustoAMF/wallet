<?= $this->extend('layouts/common') ?>

<?= $this->section('head') ?>
<style>
  /* Dashboard root */
  .dashboard-root {
    max-width: 1200px;
    width: 100%;
    margin: 2.5rem auto;
    background: var(--bg-panel);
    border-radius: .75rem;
    box-shadow: 0 6px 32px rgba(0,0,0,0.18);
    padding: 2.5rem 2rem 2rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }

  /* Header */
  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }
  .dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--clr-text);
    margin: 0;
  }
  .dashboard-user {
    color: var(--clr-text);
    opacity: .7;
    font-size: .9rem;
  }

  /* Balance card */
  .dashboard-balance-card {
    background: var(--bg-dark);
    border-radius: .75rem;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: .5rem;
  }
  .dashboard-balance-label {
    color: var(--clr-text);
    opacity: .8;
    font-size: 1.1rem;
  }
  .dashboard-balance-value {
    color: var(--clr-accent);
    font-size: 2.8rem;
    font-weight: 700;
    letter-spacing: 1px;
  }

  /* Actions grid */
  .dashboard-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px,1fr));
    gap: 2rem;
  }

  /* History */
  .dashboard-history {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  .dashboard-history-title {
    color: var(--clr-text);
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
  }
  .dashboard-table-wrapper {
    overflow-x: auto;
  }
  .dashboard-table {
    width: 100%;
    border-collapse: collapse;
  }
  .dashboard-table th,
  .dashboard-table td {
    padding: 1rem 1.25rem;
    text-align: left;
    color: var(--clr-text);
    border-bottom: 1px solid rgba(255,255,255,.1);
  }
  .dashboard-table th {
    background: var(--clr-accent-h);
    color: #fff;
    font-weight: 600;
  }
  .dashboard-table tr:hover {
    background: rgba(255,255,255,.05);
  }

  /* Small screens */
  @media (max-width: 600px) {
    .dashboard-root {
      padding: 1.5rem 1rem 1rem;
    }
    .dashboard-balance-card,
    .dashboard-history-title {
      text-align: center;
    }
    .dashboard-actions {
      grid-template-columns: 1fr;
    }
    .dashboard-table th,
    .dashboard-table td {
      padding: .75rem 1rem;
    }
    .dashboard-table {
      font-size: .9rem;
    }
  }
</style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
  <!-- Spinner -->
  <?= view('components/Loader') ?>

  <div class="dashboard-root">

    <!-- Header -->
    <div class="dashboard-header">
      <div>
        <div class="dashboard-title">Minha Carteira</div>
        <div id="userInfo" class="dashboard-user">Carregando usuário…</div>
      </div>
      <?= view('components/ButtonField', [
           'text'    => 'Sair',
           'variant' => 'secondary',
           'id'      => 'logoutBtn'
      ]) ?>
    </div>

    <!-- Saldo -->
    <div class="dashboard-balance-card">
      <div class="dashboard-balance-label">Saldo atual</div>
      <div id="balance" class="dashboard-balance-value">–</div>
    </div>

    <!-- Ações: Depósito e Transferência -->
    <div class="dashboard-actions">
      <!-- Depósito -->
      <div class="dashboard-card">
        <div class="dashboard-card-title">Depósito</div>
        <form id="depositForm" autocomplete="off">
          <?= view('components/InputField', [
              'id'          => 'depositAmount',
              'name'        => 'amount',
              'label'       => 'Valor (R$)',
              'type'        => 'number',
              'placeholder' => '0,00',
              'min'         => '0.01',
              'step'        => '0.01'
          ]) ?>
          <?= view('components/ButtonField', [
              'text'    => 'Depositar',
              'variant' => 'primary',
              'type'    => 'submit'
          ]) ?>
        </form>
      </div>

      <!-- Transferência -->
      <div class="dashboard-card">
        <div class="dashboard-card-title">Transferência</div>
        <form id="transferForm" autocomplete="off">
          <?= view('components/InputField', [
              'id'          => 'transferEmail',
              'name'        => 'toEmail',
              'label'       => 'E-mail destino',
              'type'        => 'email',
              'placeholder' => 'usuario@exemplo.com'
          ]) ?>
          <?= view('components/InputField', [
              'id'          => 'transferAmount',
              'name'        => 'amount',
              'label'       => 'Valor (R$)',
              'type'        => 'number',
              'placeholder' => '0,00',
              'min'         => '0.01',
              'step'        => '0.01'
          ]) ?>
          <?= view('components/ButtonField', [
              'text'    => 'Transferir',
              'variant' => 'primary',
              'type'    => 'submit'
          ]) ?>
        </form>
      </div>
    </div>

    <!-- Histórico -->
    <div class="dashboard-history">
      <div class="dashboard-history-title">Histórico de Transações</div>
      <div class="dashboard-table-wrapper">
        <table class="dashboard-table">
          <thead>
            <tr>
              <th>#</th><th>Tipo</th><th>Fluxo</th><th>Valor</th><th>Data</th>
            </tr>
          </thead>
          <tbody id="txnTable">
            <tr><td colspan="5">Carregando…</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const userI   = document.getElementById('userInfo');
  const balEl   = document.getElementById('balance');
  const txnTB   = document.getElementById('txnTable');
  const nfBR    = new Intl.NumberFormat('pt-BR',{
    style: 'currency', currency: 'BRL'
  });
  const dtBR    = ts => new Date(ts).toLocaleString('pt-BR');
  const show    = window.showLoader;
  const hide    = window.hideLoader;

  async function api(path, opts = {}) {
    opts.credentials = 'include';
    const res = await fetch(path, opts);
    if (!res.ok) throw await res.json();
    return res.json();
  }

  async function loadUser() {
    try {
      const u = await api('/api/user');
      userI.textContent = `${u.name} • ${u.email}`;
    } catch {
      userI.textContent = 'Erro ao carregar usuário';
    }
  }

  async function loadBalance() {
    try {
      const { balance } = await api('/api/balance');
      balEl.textContent = nfBR.format(balance);
    } catch {
      balEl.textContent = '–';
    }
  }

  async function loadTx() {
    try {
      const txns = await api('/api/transactions');
      txnTB.innerHTML = txns.map(x => `
        <tr>
          <td>${x.id}</td>
          <td>${{deposit:'Depósito',transfer:'Transferência',reversal:'Reversão'}[x.type]||x.type}</td>
          <td>${{in:'Entrada',out:'Saída'}[x.direction]||x.direction}</td>
          <td>${nfBR.format(x.amount)}</td>
          <td>${dtBR(x.created_at)}</td>
        </tr>
      `).join('');
    } catch {
      txnTB.innerHTML = '<tr><td colspan="5">Erro ao carregar histórico</td></tr>';
    }
  }

  document.getElementById('depositForm').addEventListener('submit', async e => {
    e.preventDefault();
    show();
    try {
      const v = +e.target.amount.value;
      if (v <= 0) { alert('Valor inválido'); return; }
      await api('/api/deposit', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ amount: v })
      });
      await loadBalance();
      await loadTx();
    } catch(err) {
      alert(err.messages || 'Erro no depósito');
    } finally { hide(); }
  });

  document.getElementById('transferForm').addEventListener('submit', async e => {
    e.preventDefault();
    show();
    try {
      const data = {
        toEmail: e.target.toEmail.value,
        amount:  +e.target.amount.value
      };
      if (!data.toEmail||data.amount<=0) { alert('Dados inválidos'); return; }
      await api('/api/transfer',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(data)
      });
      await loadBalance();
      await loadTx();
    } catch(err) {
      alert(err?.message ?? err?.messages?.error ?? 'Erro na transferência');
    } finally { hide(); }
  });

  document.getElementById('logoutBtn').addEventListener('click', () => {
    show();
    fetch('/api/logout',{credentials:'include'})
      .then(()=> location='/')
      .finally(hide);
  });

  (async () => {
    show();
    await loadUser();
    await loadBalance();
    await loadTx();
    hide();
  })();
});
</script>
<?= $this->endSection() ?>


