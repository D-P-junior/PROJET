/**
 * LAMONDJAI - Core Application logic
 */

const app = {
  // --- State ---
  state: {
    user: JSON.parse(localStorage.getItem('lamondjai_user')) || null,
    currentView: 'auth',
    clients: JSON.parse(localStorage.getItem('lamondjai_clients')) || [
      { id: 1, name: 'Jean Dupont', phone: '0123456789' },
      { id: 2, name: 'Marie Kouassi', phone: '0876543210' }
    ],
    debts: JSON.parse(localStorage.getItem('lamondjai_debts')) || [
      { id: 1, clientId: 1, amountTotal: 15000, amountRemaining: 5000, dueDate: '2026-03-10', status: 'En cours', createdAt: new Date().toISOString() },
      { id: 2, clientId: 2, amountTotal: 25000, amountRemaining: 25000, dueDate: '2026-02-20', status: 'En retard', createdAt: new Date().toISOString() }
    ],
    payments: JSON.parse(localStorage.getItem('lamondjai_payments')) || []
  },

  // --- Initialization ---
  init() {
    this.render();
    if (this.state.user) {
      this.navigate('dashboard');
    } else {
      this.navigate('auth');
    }
  },

  // --- Storage ---

  // --- Persistence ---
  save() {
    localStorage.setItem('lamondjai_user', JSON.stringify(this.state.user));
    localStorage.setItem('lamondjai_clients', JSON.stringify(this.state.clients));
    localStorage.setItem('lamondjai_debts', JSON.stringify(this.state.debts));
    localStorage.setItem('lamondjai_payments', JSON.stringify(this.state.payments));
  },

  // --- Navigation ---
  navigate(view) {
    this.state.currentView = view;
    this.render();

    // Smooth transitions/scroll to top
    window.scrollTo(0, 0);

    // Update Lucide icons
    if (window.lucide) {
      lucide.createIcons();
    }
  },

  // --- Rendering ---
  render() {
    const root = document.getElementById('app');

    if (this.state.currentView === 'auth') {
      this.renderAuth(root);
    } else {
      this.renderLayout(root);
    }

    if (window.lucide) {
      lucide.createIcons();
    }
  },

  renderAuth(root) {
    const tpl = document.getElementById('tpl-auth').content.cloneNode(true);
    root.innerHTML = '';
    root.appendChild(tpl);

    const form = document.getElementById('auth-form');
    const toggleLink = document.getElementById('auth-toggle-link');
    const title = document.getElementById('auth-title');
    const subtitle = document.getElementById('auth-subtitle');
    const groupName = document.getElementById('group-name');
    const submitBtn = document.getElementById('auth-submit');
    const toggleText = document.getElementById('auth-toggle-text');

    let isLogin = true;

    toggleLink.onclick = (e) => {
      e.preventDefault();
      isLogin = !isLogin;
      title.innerText = isLogin ? 'Connexion' : 'Inscription';
      subtitle.innerText = isLogin ? 'Gérez vos dettes en toute simplicité.' : 'Créez un compte pour commencer.';
      groupName.style.display = isLogin ? 'none' : 'block';
      submitBtn.innerText = isLogin ? 'Se connecter' : "S'inscrire";
      toggleText.innerText = isLogin ? 'Pas encore de compte ?' : 'Déjà un compte ?';
      toggleLink.innerText = isLogin ? "S'inscrire" : 'Se connecter';
    };

    form.onsubmit = (e) => {
      e.preventDefault();
      const email = document.getElementById('auth-email').value;
      const name = isLogin ? email.split('@')[0] : document.getElementById('auth-name').value;

      this.state.user = { name, email };
      this.save();
      this.navigate('dashboard');
    };
  },

  renderLayout(root) {
    root.innerHTML = '';
    const layoutTpl = document.getElementById('tpl-layout').content.cloneNode(true);
    root.appendChild(layoutTpl);

    const displayName = document.getElementById('user-display-name');
    if (displayName) displayName.innerText = this.state.user.name;

    const logoutBtn = document.getElementById('btn-logout');
    if (logoutBtn) logoutBtn.onclick = (e) => {
      e.preventDefault();
      this.state.user = null;
      this.save();
      this.navigate('auth');
    };

    const logoutBtnMobile = document.getElementById('btn-logout-mobile');
    if (logoutBtnMobile) logoutBtnMobile.onclick = (e) => {
      e.preventDefault();
      this.state.user = null;
      this.save();
      this.navigate('auth');
    };

    // Nav navigation
    const navLinks = document.querySelectorAll('.nav-link[data-view]');
    navLinks.forEach(link => {
      link.classList.toggle('active', link.getAttribute('data-view') === this.state.currentView);
      link.onclick = (e) => {
        e.preventDefault();
        this.navigate(link.getAttribute('data-view'));
      };
    });

    const mainView = document.getElementById('main-view');
    if (this.state.currentView === 'dashboard') {
      this.renderDashboard(mainView);
    } else if (this.state.currentView === 'debts') {
      this.renderDebts(mainView);
    } else if (this.state.currentView === 'clients') {
      this.renderClients(mainView);
    }
  },

  renderDashboard(container) {
    const tpl = document.getElementById('tpl-dashboard').content.cloneNode(true);
    container.innerHTML = '';
    container.appendChild(tpl);

    document.getElementById('dash-user-name').innerText = this.state.user.name;

    // Stats
    const totalDebt = this.state.debts.reduce((acc, d) => acc + (d.status !== 'Payé' ? d.amountRemaining : 0), 0);
    const totalRecovered = this.state.payments.reduce((acc, p) => acc + p.amount, 0);

    // Risk clients: 2 or more overdue debts
    const clientsWithDebts = this.state.clients.map(c => {
      const clientDebts = this.state.debts.filter(d => d.clientId === c.id && d.status === 'En retard');
      return { ...c, overdueCount: clientDebts.length };
    });
    const riskClientsCount = clientsWithDebts.filter(c => c.overdueCount >= 2).length;

    document.getElementById('stat-total-debts').innerText = `${totalDebt.toLocaleString()} FCFA`;
    document.getElementById('stat-total-recovered').innerText = `${totalRecovered.toLocaleString()} FCFA`;
    document.getElementById('stat-client-count').innerText = this.state.clients.length;
    document.getElementById('stat-risk-clients').innerText = riskClientsCount;

    // Recent Debts
    const list = document.getElementById('recent-debts-list');
    const recent = [...this.state.debts].sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)).slice(0, 5);

    recent.forEach(debt => {
      const client = this.state.clients.find(c => c.id === debt.clientId);
      const tr = document.createElement('tr');
      const statusClass = debt.status === 'En retard' ? 'danger' : (debt.status === 'Payé' ? 'success' : 'info');

      tr.innerHTML = `
        <td style="font-weight: 500;">${client ? client.name : 'Unknown'}</td>
        <td>${debt.amountRemaining.toLocaleString()} FCFA</td>
        <td>${new Date(debt.dueDate).toLocaleDateString()}</td>
        <td><span class="badge badge-${statusClass}">${debt.status}</span></td>
      `;
      list.appendChild(tr);
    });

    document.getElementById('btn-add-debt-quick').onclick = () => this.showModal('add-debt');
  },

  renderDebts(container) {
    container.innerHTML = `
      <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
          <h1>Liste des dettes</h1>
          <p class="text-muted">Suivez et gérez les remboursements de vos clients.</p>
        </div>
        <button class="btn btn-primary" onclick="app.showModal('add-debt')">
          <i data-lucide="plus"></i> Nouvelle dette
        </button>
      </header>
      
      <div class="card">
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Client</th>
                <th>Montant Total</th>
                <th>Reste</th>
                <th>Échéance</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="debts-table-body">
              <!-- JS -->
            </tbody>
          </table>
        </div>
      </div>
    `;

    const tbody = document.getElementById('debts-table-body');
    this.state.debts.forEach(debt => {
      const client = this.state.clients.find(c => c.id === debt.clientId);
      const clientOverdueCount = this.state.debts.filter(d => d.clientId === debt.clientId && d.status === 'En retard').length;
      const isRisk = clientOverdueCount >= 2;

      const tr = document.createElement('tr');
      const statusClass = debt.status === 'En retard' ? 'danger' : (debt.status === 'Payé' ? 'success' : 'info');

      tr.innerHTML = `
        <td style="font-weight: 500;">
          <div style="display: flex; align-items: center; gap: 0.5rem;">
            ${client ? client.name : 'Unknown'}
            ${isRisk ? '<span class="badge badge-danger" style="font-size: 0.6rem; padding: 0.1rem 0.4rem;">Risque</span>' : ''}
          </div>
          <div class="text-muted" style="font-size: 0.75rem;">${client ? client.phone : ''}</div>
        </td>
        <td>${debt.amountTotal.toLocaleString()} FCFA</td>
        <td style="font-weight: 600;">${debt.amountRemaining.toLocaleString()} FCFA</td>
        <td>${new Date(debt.dueDate).toLocaleDateString()}</td>
        <td><span class="badge badge-${statusClass}">${debt.status}</span></td>
        <td>
          <div style="display: flex; gap: 0.5rem;">
            <button class="btn btn-outline" style="padding: 0.4rem;" title="Rappel" onclick="app.sendReminder(${debt.id})">
              <i data-lucide="bell" style="width: 16px;"></i>
            </button>
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;" onclick="app.showModal('payment', ${debt.id})">
              Payer
            </button>
          </div>
        </td>
      `;
      tbody.appendChild(tr);
    });
  },

  renderClients(container) {
    container.innerHTML = `
      <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
          <h1>Mes Clients</h1>
          <p class="text-muted">Gérez votre base de données clients et leur historique.</p>
        </div>
      </header>
      
      <div class="card">
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Dette totale</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="clients-table-body">
              <!-- JS -->
            </tbody>
          </table>
        </div>
      </div>
    `;

    const tbody = document.getElementById('clients-table-body');
    this.state.clients.forEach(client => {
      const clientDebts = this.state.debts.filter(d => d.clientId === client.id);
      const totalRemaining = clientDebts.reduce((acc, d) => acc + (d.status !== 'Payé' ? d.amountRemaining : 0), 0);
      const overdueCount = clientDebts.filter(d => d.status === 'En retard').length;

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td style="font-weight: 500;">${client.name}</td>
        <td>${client.phone}</td>
        <td style="font-weight: 600;">${totalRemaining.toLocaleString()} FCFA</td>
        <td>
          ${overdueCount >= 2 ? '<span class="badge badge-danger">Risque Élevé</span>' : (overdueCount > 0 ? '<span class="badge badge-warning">À surveiller</span>' : '<span class="badge badge-success">Sain</span>')}
        </td>
        <td>
          <button class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;" onclick="app.navigate('debts')">
            Voir dettes
          </button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  },

  // --- Modals ---
  showModal(type, data) {
    const container = document.getElementById('modal-container');
    const content = document.getElementById('modal-content');
    container.style.display = 'flex';

    if (type === 'add-debt') {
      this.renderAddDebtModal(content);
    } else if (type === 'payment') {
      this.renderPaymentModal(content, data);
    }

    lucide.createIcons();
  },

  closeModal() {
    document.getElementById('modal-container').style.display = 'none';
  },

  renderAddDebtModal(container) {
    container.innerHTML = `
      <h3 style="margin-bottom: 1.5rem;">Ajouter une nouvelle dette</h3>
      <form id="form-add-debt">
        <div class="form-group">
          <label class="form-label">Nom du client</label>
          <input type="text" id="debt-client-name" class="form-input" placeholder="Ex: Jean Kouassi" required>
        </div>
        <div class="form-group">
          <label class="form-label">Numéro de téléphone</label>
          <input type="tel" id="debt-client-phone" class="form-input" placeholder="0123456789" required>
        </div>
        <div class="form-group">
          <label class="form-label">Montant de la dette (FCFA)</label>
          <input type="number" id="debt-amount" class="form-input" placeholder="5000" required>
        </div>
        <div class="form-group">
          <label class="form-label">Date d'échéance</label>
          <input type="date" id="debt-due-date" class="form-input" required>
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
          <button type="button" class="btn btn-outline" style="flex: 1;" onclick="app.closeModal()">Annuler</button>
          <button type="submit" class="btn btn-primary" style="flex: 1;">Confirmer</button>
        </div>
      </form>
    `;

    document.getElementById('form-add-debt').onsubmit = (e) => {
      e.preventDefault();
      const name = document.getElementById('debt-client-name').value;
      const phone = document.getElementById('debt-client-phone').value;
      const amount = parseInt(document.getElementById('debt-amount').value);
      const dueDate = document.getElementById('debt-due-date').value;

      let client = this.state.clients.find(c => c.name.toLowerCase() === name.toLowerCase());
      if (!client) {
        client = { id: Date.now(), name, phone };
        this.state.clients.push(client);
      }

      this.state.debts.push({
        id: Date.now() + 1,
        clientId: client.id,
        amountTotal: amount,
        amountRemaining: amount,
        dueDate: dueDate,
        status: new Date(dueDate) < new Date() ? 'En retard' : 'En cours',
        createdAt: new Date().toISOString()
      });

      this.save();
      this.closeModal();
      this.render();
      alert('Dette ajoutée avec succès !');
    };
  },

  renderPaymentModal(container, debtId) {
    const debt = this.state.debts.find(d => d.id === debtId);
    const client = this.state.clients.find(c => c.id === debt.clientId);

    container.innerHTML = `
      <h3 style="margin-bottom: 0.5rem;">Enregistrer un paiement</h3>
      <p class="text-muted text-sm" style="margin-bottom: 1.5rem;">Client: ${client.name} | Reste: ${debt.amountRemaining.toLocaleString()} FCFA</p>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <button class="btn btn-outline" id="btn-pay-cash" style="flex-direction: column; height: 100px; gap: 0.5rem;">
          <i data-lucide="banknote" style="width: 32px; height: 32px; color: #10b981;"></i>
          Espèces / Cash
        </button>
        <button class="btn btn-outline" id="btn-pay-online" style="flex-direction: column; height: 100px; gap: 0.5rem;">
          <i data-lucide="credit-card" style="width: 32px; height: 32px; color: #2563eb;"></i>
          Paiement en ligne
        </button>
      </div>

      <div id="payment-details" style="display: none;">
        <div class="form-group">
          <label class="form-label">Montant payé (FCFA)</label>
          <input type="number" id="pay-amount" class="form-input" max="${debt.amountRemaining}" value="${debt.amountRemaining}">
        </div>
        <button class="btn btn-primary" style="width: 100%" id="confirm-payment">Valider le paiement</button>
      </div>
    `;

    const details = document.getElementById('payment-details');
    let method = 'cash';

    document.getElementById('btn-pay-cash').onclick = () => {
      method = 'cash';
      details.style.display = 'block';
    };

    document.getElementById('btn-pay-online').onclick = () => {
      method = 'online';
      details.style.display = 'block';
      alert('Génération du lien de paiement sécurisé pour ' + client.name + '...');
    };

    document.getElementById('confirm-payment').onclick = () => {
      const amount = parseInt(document.getElementById('pay-amount').value);
      if (amount <= 0 || amount > debt.amountRemaining) return alert('Montant invalide');

      debt.amountRemaining -= amount;
      if (debt.amountRemaining <= 0) debt.status = 'Payé';

      this.state.payments.push({
        id: Date.now(),
        debtId: debt.id,
        amount: amount,
        method: method,
        date: new Date().toISOString()
      });

      this.save();
      this.closeModal();
      this.render();
      alert('Paiement enregistré et preuve horodatée générée !');
    };
  },

  sendReminder(debtId) {
    const debt = this.state.debts.find(d => d.id === debtId);
    const client = this.state.clients.find(c => c.id === debt.clientId);
    alert(`Rappel envoyé à ${client.name} (${client.phone}) via SMS & WhatsApp.\nMontant: ${debt.amountRemaining} FCFA\nÉchéance: ${new Date(debt.dueDate).toLocaleDateString()}`);
  }
};

window.app = app;
document.addEventListener('DOMContentLoaded', () => app.init());
