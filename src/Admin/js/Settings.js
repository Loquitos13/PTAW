// settings.js - Admin Settings functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AdminSettings when DOM is ready
    window.adminSettings = new AdminSettings();
});

class AdminSettings {
    constructor() {
        this.adminId = null;
        this.apiBase = '../../restapi/PrintGoAPI.php'; 
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

        const createTeamForm = document.getElementById('create-team-form');
        if (createTeamForm) {
            createTeamForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createTeam();
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
        if (tabName === 'teams') {
            this.loadTeamsData();
        } else if (tabName === 'members') {
            this.loadMembersData();
        }
    }

    async loadAdminData() {
        this.showLoading('general', true);
        try {
            const response = await fetch(`${this.apiBase}/adminInfoByID/${this.adminId}`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });

            const data = await response.json();
            
            if (data && !data.error) {
                this.populateAdminForm(data);
            } else {
                this.showAlert('Error loading admin data: ' + (data.message || 'Unknown error'), 'danger');
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
            const response = await fetch(`${this.apiBase}/updateAdmin`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
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
            const response = await fetch(`${this.apiBase}/updateAdminPassword`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_admin: this.adminId,
                    current_password: data.current_password,
                    new_password: data.new_password
                })
            });

            const result = await response.json();
            
            if (result.success) {
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

    async loadTeamsData() {
        this.showLoading('teams', true);
        try {
            const response = await fetch(`${this.apiBase}/teams`, { 
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });

            const teamsData = await response.json();

            if (teamsData && Array.isArray(teamsData)) {
                this.displayTeams(teamsData);
                this.updateTeamsStats(teamsData);
            } else if (teamsData && !teamsData.error) {
                this.displayTeams([]);
                this.updateTeamsStats([]);
            } else {
                this.showAlert('Error loading teams: ' + (teamsData.message || 'Unknown error'), 'danger');
                this.displayTeams([]);
                this.updateTeamsStats([]);
            }
        } catch (error) {
            console.error('Error loading teams data:', error);
            this.showAlert('Error loading teams data', 'danger');
            this.displayTeams([]);
            this.updateTeamsStats([]);
        } finally {
            this.showLoading('teams', false);
        }
    }

    async loadMembersData() {
        this.showLoading('members', true);
        try {
            const [teamResponse, usersResponse] = await Promise.all([
                fetch(`${this.apiBase}/teamMembers`, { 
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' }
                }),
                fetch(`${this.apiBase}/allUsers`, { 
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' }
                })
            ]);

            const teamData = await teamResponse.json();
            const usersData = await usersResponse.json();

            if (teamData && Array.isArray(teamData)) {
                this.displayTeamMembers(teamData);
                this.updateMemberStats(teamData);
            } else {
                this.displayTeamMembers([]);
                this.updateMemberStats([]);
            }

            if (usersData && Array.isArray(usersData)) {
                this.populateUserSelect(usersData);
            } else {
                this.populateUserSelect([]);
            }
        } catch (error) {
            console.error('Error loading members data:', error);
            this.showAlert('Error loading members data', 'danger');
            this.displayTeamMembers([]);
            this.updateMemberStats([]);
            this.populateUserSelect([]);
        } finally {
            this.showLoading('members', false);
        }
    }

    displayTeams(teams) {
        const teamsList = document.getElementById('teamsList');
        if (!teamsList) return;

        if (!teams || teams.length === 0) {
            teamsList.innerHTML = '<p class="text-muted">No teams created yet.</p>';
            return;
        }

        teamsList.innerHTML = teams.map(team => `
            <div class="team-card card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-2">${team.nome_team || 'Unnamed Team'}</h6>
                            <p class="card-text text-muted small mb-2">${team.descricao_team || 'No description'}</p>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-people me-1"></i>
                                <span class="me-3">${team.member_count || 0} members</span>
                                <i class="bi bi-calendar me-1"></i>
                                <span>Created ${team.data_criacao_team ? new Date(team.data_criacao_team).toLocaleDateString('pt-PT') : 'Unknown'}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="adminSettings.viewTeamMembers(${team.id_team})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="adminSettings.deleteTeam(${team.id_team})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    displayTeamMembers(members) {
        const teamsList = document.getElementById('teamMembersList');
        if (!teamsList) return;

        if (!members || members.length === 0) {
            teamsList.innerHTML = '<p class="text-muted">No team members yet.</p>';
            return;
        }

        teamsList.innerHTML = members.map(member => `
            <div class="team-member-item d-flex justify-content-between align-items-center p-3 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="bg-${member.role === 'admin' ? 'success' : 'primary'} rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width: 40px; height: 40px; color: white; font-weight: bold;">
                        ${(member.first_name || 'U').charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <p class="mb-0 fw-medium">${member.first_name || 'Unknown'}</p>
                        <p class="text-muted small mb-0">${member.email || 'No email'}</p>
                        <p class="text-muted small mb-0">Team: ${member.team_name || 'Unknown'}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-${member.role === 'admin' ? 'success' : 'light text-dark'} me-2">
                        ${member.role || 'member'}
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

    updateTeamsStats(teams) {
        const totalTeams = teams ? teams.length : 0;
        const activeTeams = teams ? teams.filter(t => t.status_team === 'active').length : 0;
        const totalMembers = teams ? teams.reduce((sum, team) => sum + parseInt(team.member_count || 0), 0) : 0;

        const totalElement = document.getElementById('total-teams');
        const activeElement = document.getElementById('active-teams');
        const membersElement = document.getElementById('total-team-members');

        if (totalElement) totalElement.textContent = totalTeams;
        if (activeElement) activeElement.textContent = activeTeams;
        if (membersElement) membersElement.textContent = totalMembers;
    }

    updateMemberStats(members) {
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

    async createTeam() {
        const formData = new FormData(document.getElementById('create-team-form'));
        const data = Object.fromEntries(formData);
        
        // Add admin ID
        data.created_by_admin = this.adminId;

        try {
            const response = await fetch(`${this.apiBase}/createTeam`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.showAlert('Team created successfully', 'success');
                document.getElementById('create-team-form').reset();
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTeamModal'));
                if (modal) modal.hide();
                
                // Reload teams data
                this.loadTeamsData();
            } else {
                this.showAlert(result.message || 'Error creating team', 'danger');
            }
        } catch (error) {
            console.error('Error creating team:', error);
            this.showAlert('Error creating team', 'danger');
        }
    }

    async addTeamMember() {
        const formData = new FormData(document.getElementById('add-member-form'));
        const data = Object.fromEntries(formData);

        if (!data.id_cliente) {
            this.showAlert('Please select a user', 'danger');
            return;
        }

        try {
            const response = await fetch(`${this.apiBase}/addTeamMember`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.showAlert('Team member added successfully', 'success');
                document.getElementById('add-member-form').reset();
                this.loadMembersData();
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
            const response = await fetch(`${this.apiBase}/removeTeamMember/${memberId}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' }
            });

            const result = await response.json();
            
            if (result.success) {
                this.showAlert('Team member removed successfully', 'success');
                this.loadMembersData();
            } else {
                this.showAlert(result.message || 'Error removing team member', 'danger');
            }
        } catch (error) {
            console.error('Error removing team member:', error);
            this.showAlert('Error removing team member', 'danger');
        }
    }

    async deleteTeam(teamId) {
        if (!confirm('Are you sure you want to delete this team? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`${this.apiBase}/deleteTeam`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ team_id: teamId })
            });

            const result = await response.json();
            
            if (result.success) {
                this.showAlert('Team deleted successfully', 'success');
                this.loadTeamsData();
            } else {
                this.showAlert(result.message || 'Error deleting team', 'danger');
            }
        } catch (error) {
            console.error('Error deleting team:', error);
            this.showAlert('Error deleting team', 'danger');
        }
    }

    viewTeamMembers(teamId) {
        // Switch to members tab and filter by team
        this.switchTab('members');
        // You can add filtering logic here if needed
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