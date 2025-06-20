// settings.js - Admin Settings functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AdminSettings when DOM is ready
    window.adminSettings = new AdminSettings();
});

class AdminSettings {
    constructor() {
        this.adminId = null;
        this.init();
    }

    init() {
        // Get admin ID from global variable
        this.adminId = window.adminId || 1;
        this.setupEventListeners();
        this.loadAdminData();
    }

    setupEventListeners() {
        // Tab navigation
        document.querySelectorAll('.settings-tabs .nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchTab(e.target.dataset.tab);
            });
        });

        // Forms
        const generalForm = document.getElementById('general-form');
        if (generalForm) {
            generalForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateGeneralSettings();
            });
        }

        const passwordForm = document.getElementById('password-form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.updatePassword();
            });
        }

        const addMemberForm = document.getElementById('add-member-form');
        if (addMemberForm) {
            addMemberForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.addTeamMember();
            });
        }
    }

    switchTab(tabName) {
        // Update tab links
        document.querySelectorAll('.settings-tabs .nav-link').forEach(link => {
            link.classList.remove('active');
        });
        const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
        });
        const activeContent = document.getElementById(`${tabName}-content`);
        if (activeContent) {
            activeContent.style.display = 'block';
        }

        // Load tab data
        if (tabName === 'team') {
            this.loadTeamData();
        }
    }

    async loadAdminData() {
        this.showLoading('general', true);
        try {
            const response = await fetch('../../admin/getAdminInfo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_admin: this.adminId })
            });

            const data = await response.json();
            
            if (data.status === 'success') {
                this.populateAdminForm(data.data);
            } else {
                this.showAlert('Error loading admin data: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Error loading admin data:', error);
            this.showAlert('Error loading admin data', 'danger');
        } finally {
            this.showLoading('general', false);
        }
    }

    populateAdminForm(adminData) {
        const nomeField = document.getElementById('nome_admin');
        const emailField = document.getElementById('email_admin');
        const contactoField = document.getElementById('contacto_admin');
        const funcaoField = document.getElementById('funcao_admin');
        const lastLoginField = document.getElementById('last-login');

        if (nomeField) nomeField.value = adminData.nome_admin || '';
        if (emailField) emailField.value = adminData.email_admin || '';
        if (contactoField) contactoField.value = adminData.contacto_admin || '';
        if (funcaoField) funcaoField.value = adminData.funcao_admin || '';
        
        if (lastLoginField) {
            const creationDate = adminData.data_criacao_admin ? 
                new Date(adminData.data_criacao_admin).toLocaleDateString('pt-PT') : 
                'N/A';
            lastLoginField.textContent = `Account created: ${creationDate}`;
        }
    }

    async updateGeneralSettings() {
        const formData = new FormData(document.getElementById('general-form'));
        const data = Object.fromEntries(formData);
        
        // Add admin ID to the data
        data.id_admin = this.adminId;

        try {
            const response = await fetch('../../admin/updateAdminInfo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                this.showAlert('Information updated successfully', 'success');
            } else {
                this.showAlert(result.message || 'Error updating information', 'danger');
            }
        } catch (error) {
            console.error('Error updating settings:', error);
            this.showAlert('Error updating settings', 'danger');
        }
    }

    async updatePassword() {
        const formData = new FormData(document.getElementById('password-form'));
        const data = Object.fromEntries(formData);

        if (data.new_password !== data.confirm_password) {
            this.showAlert('Password confirmation does not match', 'danger');
            return;
        }

        if (data.new_password.length < 8) {
            this.showAlert('Password must be at least 8 characters long', 'danger');
            return;
        }

        try {
            const response = await fetch('../../admin/updateAdminPassword.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_admin: this.adminId,
                    current_password: data.current_password,
                    new_password: data.new_password
                })
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                this.showAlert('Password updated successfully', 'success');
                document.getElementById('password-form').reset();
            } else {
                this.showAlert(result.message || 'Error updating password', 'danger');
            }
        } catch (error) {
            console.error('Error updating password:', error);
            this.showAlert('Error updating password', 'danger');
        }
    }

    async loadTeamData() {
        this.showLoading('team', true);
        try {
            const [teamResponse, usersResponse] = await Promise.all([
                fetch('../../admin/getTeamMembers.php', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({})
                }),
                fetch('../../admin/getAllUsers.php', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({})
                })
            ]);

            const teamData = await teamResponse.json();
            const usersData = await usersResponse.json();

            if (teamData.status === 'success') {
                this.displayTeamMembers(teamData.data);
                this.updateTeamStats(teamData.data);
            }

            if (usersData.status === 'success') {
                this.populateUserSelect(usersData.data);
            }
        } catch (error) {
            console.error('Error loading team data:', error);
            this.showAlert('Error loading team data', 'danger');
        } finally {
            this.showLoading('team', false);
        }
    }

    displayTeamMembers(members) {
        const teamsList = document.getElementById('teamMembersList');
        if (!teamsList) return;

        if (!members || members.length === 0) {
            teamsList.innerHTML = '<p class="text-muted">No team members yet.</p>';
            return;
        }

        teamsList.innerHTML = members.map(member => `
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="bg-${member.role === 'admin' ? 'success' : 'primary'} rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width: 40px; height: 40px; color: white; font-weight: bold;">
                        ${(member.first_name || 'U').charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <p class="mb-0 fw-medium">${member.first_name || 'Unknown'}</p>
                        <p class="text-muted small mb-0">${member.email || 'No email'}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-${member.role === 'admin' ? 'success' : 'light text-dark'} me-2">
                        ${member.role}
                    </span>
                    <button class="btn btn-sm btn-outline-danger" onclick="adminSettings.removeMember(${member.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    populateUserSelect(users) {
        const select = document.getElementById('member_select');
        if (!select) return;

        select.innerHTML = '<option value="">Select a user...</option>';
        if (users && Array.isArray(users)) {
            users.forEach(user => {
                select.innerHTML += `<option value="${user.id_cliente}">${user.nome_cliente} (${user.email_cliente})</option>`;
            });
        }
    }

    updateTeamStats(members) {
        const totalMembers = members ? members.length : 0;
        const activeMembers = members ? members.filter(m => m.status === 'active').length : 0;
        const adminMembers = members ? members.filter(m => m.role === 'admin').length : 0;

        const totalElement = document.getElementById('total-members');
        const activeElement = document.getElementById('active-members');
        const adminElement = document.getElementById('admin-members');

        if (totalElement) totalElement.textContent = totalMembers;
        if (activeElement) activeElement.textContent = activeMembers;
        if (adminElement) adminElement.textContent = adminMembers;
    }

    async addTeamMember() {
        const formData = new FormData(document.getElementById('add-member-form'));
        const data = Object.fromEntries(formData);

        if (!data.id_cliente) {
            this.showAlert('Please select a user', 'danger');
            return;
        }

        try {
            const response = await fetch('../../admin/addTeamMember.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                this.showAlert('Team member added successfully', 'success');
                document.getElementById('add-member-form').reset();
                this.loadTeamData();
            } else {
                this.showAlert(result.message || 'Error adding team member', 'danger');
            }
        } catch (error) {
            console.error('Error adding team member:', error);
            this.showAlert('Error adding team member', 'danger');
        }
    }

    async removeMember(memberId) {
        if (!confirm('Are you sure you want to remove this member?')) {
            return;
        }

        try {
            const response = await fetch('../../admin/removeTeamMember.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ member_id: memberId })
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                this.showAlert('Team member removed successfully', 'success');
                this.loadTeamData();
            } else {
                this.showAlert(result.message || 'Error removing team member', 'danger');
            }
        } catch (error) {
            console.error('Error removing team member:', error);
            this.showAlert('Error removing team member', 'danger');
        }
    }

    showAlert(message, type) {
        const alertContainer = document.getElementById('alert-container');
        if (!alertContainer) return;

        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

        alertContainer.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Auto-hide success alerts after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    }

    showLoading(tabName, show) {
        const loadingElement = document.getElementById(`${tabName}-loading`);
        if (!loadingElement) return;

        if (show) {
            loadingElement.classList.add('show');
        } else {
            loadingElement.classList.remove('show');
        }
    }
}