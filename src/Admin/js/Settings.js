// Settings.js - Sistema de navegação entre tabs
document.addEventListener('DOMContentLoaded', function() {
    // Elementos das tabs
    const tabLinks = document.querySelectorAll('.settings-tabs .nav-link');
    const tabContents = document.querySelectorAll('.tab-content');

    // Função para mostrar uma tab específica
    function showTab(tabName) {
        tabLinks.forEach(link => link.classList.remove('active'));
        tabContents.forEach(content => content.style.display = 'none'); // Esconde todos os conteúdos

        // Adiciona active na tab clicada
        const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
        const activeContent = document.getElementById(`${tabName}-content`);

        if (activeTab && activeContent) {
            activeTab.classList.add('active');
            activeContent.style.display = 'block'; // Mostra o conteúdo da tab ativa
        }
    }

    // Event listeners para os links das tabs
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Previne o comportamento padrão do link
            const tabName = this.getAttribute('data-tab');
            showTab(tabName);
        });
    });

    showTab('general');

    // Funções para as Quick Actions
    const exportBtn = document.getElementById('exportBtn');
    const clearCacheBtn = document.getElementById('clearCacheBtn');
    const supportBtn = document.getElementById('supportBtn');
    const saveChangesBtn = document.getElementById('saveChangesBtn');

    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            alert('A exportar dados da loja...');
            // Lógica para exportar dados
        });
    }

    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', function() {
            alert('A limpar cache...');
            // Lógica para limpar cache
        });
    }

    if (supportBtn) {
        supportBtn.addEventListener('click', function() {
            alert('A contactar suporte...');
            // Lógica para contactar suporte
        });
    }

    if (saveChangesBtn) {
        saveChangesBtn.addEventListener('click', function() {
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            this.disabled = true;
            
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-floppy me-2"></i> Save Changes';
                this.disabled = false;
                alert('Alterações guardadas com sucesso!');
                // Lógica para guardar alterações
            }, 2000); // Simula um atraso de 2 segundos para guardar
        });
    }

    // Função para configurar os event listeners dos switches da aba de Segurança
    function setupSecuritySwitches() {
        const twoFactorSwitch = document.getElementById('twoFactorSwitch');
        const loginNotificationSwitch = document.getElementById('loginNotificationSwitch');

        if (twoFactorSwitch) {
            twoFactorSwitch.addEventListener('change', function() {
                if (this.checked) {
                    console.log('Two-factor Authentication ATIVADO');
                    alert('Autenticação de dois fatores ATIVADA!');
                } else {
                    console.log('Two-factor Authentication DESATIVADO');
                    alert('Autenticação de dois fatores DESATIVADA!');
                }
            });
        }

        if (loginNotificationSwitch) {
            loginNotificationSwitch.addEventListener('change', function() {
                if (this.checked) {
                    console.log('Login Notifications ATIVADAS');
                    alert('Notificações de login ATIVADAS!');
                } else {
                    console.log('Login Notifications DESATIVADAS');
                    alert('Notificações de login DESATIVADAS!');
                }
            });
        }

        const updatePasswordBtn = document.querySelector('#security-content .btn-primary');
        if (updatePasswordBtn) {
            updatePasswordBtn.addEventListener('click', function() {
                const currentPass = document.getElementById('currentPassword').value;
                const newPass = document.getElementById('newPassword').value;
                const confirmPass = document.getElementById('confirmPassword').value;

                if (newPass !== confirmPass) {
                    alert('A nova password e a confirmação não correspondem!');
                    return;
                }
                if (currentPass === '' || newPass === '' || confirmPass === '') {
                    alert('Por favor, preencha todos os campos de password.');
                    return;
                }

                alert('Password atualizada com sucesso!');
            });
        }
    }
    setupSecuritySwitches();

    // Função para configurar os event listeners dos switches da aba de Notificações
    function setupNotificationSwitches() {
        const orderNotifications = document.getElementById('orderNotifications');
        const stockAlerts = document.getElementById('stockAlerts');
        const weeklyReports = document.getElementById('weeklyReports');
        const marketingUpdates = document.getElementById('marketingUpdates');
        const browserNotifications = document.getElementById('browserNotifications');
        const mobileNotifications = document.getElementById('mobileNotifications');
        const saveNotificationBtn = document.querySelector('#notifications-content .btn-primary');

        // Adiciona listeners para todos os switches
        [orderNotifications, stockAlerts, weeklyReports, marketingUpdates, browserNotifications, mobileNotifications].forEach(sw => {
            if (sw) {
                sw.addEventListener('change', function() {
                    console.log(`${this.id} is now ${this.checked ? 'ON' : 'OFF'}`);
                });
            }
        });

        // Listener para o botão "Save Preferences"
        if (saveNotificationBtn) {
            saveNotificationBtn.addEventListener('click', function() {
                alert('Preferências de Notificações guardadas!');
            });
        }
    }
    setupNotificationSwitches();


    // Função para adicionar um novo membro da equipa 
    window.addTeamMember = function() { // Tornar global para ser acessível via onclick
        const nameInput = document.getElementById('memberName');
        const emailInput = document.getElementById('memberEmail');
        const roleInput = document.getElementById('memberRole');
        const teamList = document.getElementById('teamMembersList');

        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const role = roleInput.value;

        if (name && email) {
            const memberHtml = `
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-${role === 'Owner' ? 'primary' : (role === 'Admin' ? 'success' : 'warning')} rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px; color: white; font-weight: bold;">
                            ${name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <p class="mb-0 fw-medium">${name}</p>
                            <p class="text-muted small mb-0">${email}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-${role === 'Owner' ? 'primary' : (role === 'Admin' ? 'info' : 'light text-dark')} me-2">${role}</span>
                        <button class="btn btn-sm btn-outline-danger" onclick="this.parentElement.parentElement.remove()">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            teamList.insertAdjacentHTML('beforeend', memberHtml);
            
            // Limpar formulário
            nameInput.value = '';
            emailInput.value = '';
            roleInput.value = 'Member'; // Reset para o valor padrão
            alert('Convite para novo membro da equipa enviado (frontend)!');
        } else {
            alert('Por favor, preencha o nome e o email do novo membro.');
        }
    };

});