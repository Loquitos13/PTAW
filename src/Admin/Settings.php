<?php
session_start()
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print & Go - Settings</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/Dashboard.css">
  <link rel="stylesheet" href="css/Settings.css">
</head>

<style>
  body {
    margin: 0;
    padding: 0;
    overflow: auto;
    align-items: center;
  }

  #menu-mobile {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 80%;
    height: 100%;
    background-color: white;
    transform: translateX(-10%);
    transition: transform 2000ms ease;
    overflow: hidden;
    z-index: 999;
    flex-direction: column-reverse;
    margin-top: 0;
    justify-content: flex-end;
    gap: 20px;
    align-items: center;
    padding-top: 100px;
  }

  .fixed-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: white;
    z-index: 999;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  #menu-mobile.open {
    display: flex;
    transform: translateX(0);
  }

  #menu-mobile a {
    color: #4F46E5;
    text-decoration: none;
    font-size: 1.5rem;
    margin: 15px 0;
  }

  #menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1000;
    font-size: 2rem;
    background: none;
    border: none;
    color: black;
    cursor: pointer;
  }

  #logo-header-mobile {
    display: none;
    background: none;
    border: none;
  }

  .alert {
    border-radius: 8px;
    border: none;
    font-weight: 500;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }

  .loading-spinner {
    display: none;
    text-align: center;
    padding: 20px;
  }

  .loading-spinner.show {
    display: block;
  }

  .spinner-border-sm {
    width: 1rem;
    height: 1rem;
  }

  @media (max-width: 1200px) {
    body {
      width: 100%;
      padding-top: 70px;
    }

    .header-desktop {
      display: none !important;
    }

    #a-logo-header-mobile {
      display: flex;
      width: max-content;
      justify-content: center;
      padding-top: 20px;
      padding-bottom: 20px;
      width: 100%;
    }

    #logo-header-mobile {
      display: block;
      width: 100px;
      height: auto;
      margin: 0 auto;
    }

    #menu-toggle {
      display: block;
      top: 0;
    }

    #menu-mobile {
      position: fixed;
      height: 100%;
      overflow: hidden;
    }

    #menu-mobile li {
      list-style: none;
    }

    #menu-mobile .social {
      display: flex;
      height: 100%;
      width: 100%;
      align-items: center;
      align-self: flex-end;
      justify-content: center;
    }

    .container1 {
      display: none;
    }

    .left,
    .right {
      width: 100%;
      justify-content: center;
    }

    .left a {
      width: auto;
    }

    #containerHeroe {
      flex-direction: column;
      height: auto;
      padding: 20px 20px;
      width: 100%;
    }

    .esqHeroe,
    .dirHeroe {
      width: 100%;
      text-align: center;
    }

    .dirHeroe img {
      position: static;
      height: auto;
      width: 80%;
    }

    #prodDestaques {
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .containerDestaques {
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100%;
      gap: 20px;
    }

    .feedback-carousel-container {
      max-width: 90%;
      max-height: 400px;
    }

    #featureSection {
      padding: 5vh;
    }

    #containerFeatures {
      flex-direction: column;
    }

    .featureBox p {
      width: 90%;
    }

    #cta h3 {
      text-align: center;
      font-size: 100%;
    }

    #cta p {
      text-align: center;
      font-size: 90%;
    }

    #containerFooter {
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }
  }
</style>

<body style="background-color: #E5E7EB;">
  <div class="d-flex">
    <?php include '../includes/header-desktop-admin.php'; ?>
    <?php include '../includes/header-mobile-admin.php'; ?>

    <div class="flex-grow-1 p-4" id="main-content">
      <div class="container-fluid">
        <div class="row mb-4">
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div>
                <h1 class="mb-1">Settings</h1>
                <p class="text-muted">Manage your store preferences</p>
              </div>
              <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-sm-block">
                  <i class="bi bi-bell fs-5"></i>
                </div>
                <img src="../../imagens/admin.png" alt="Admin Profile" id="img-admin">
                <span class="ms-2">Admin</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Alert Container -->
        <div id="alert-container"></div>

        <!-- Tabs Navigation -->
        <div class="row mb-4">
          <div class="col-12">
            <ul class="nav nav-tabs settings-tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#" data-tab="general">General</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-tab="team">Team</a>
              </li>
            </ul>
          </div>
        </div>

        <!-- General Tab Content -->
        <div id="general-content" class="tab-content">
          <div class="loading-spinner" id="general-loading">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading user data...</p>
          </div>

          <div class="row gx-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Personal Information</h5>

                  <form id="general-form">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label for="email" class="form-label">Email Address</label>
                      <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-floppy me-2"></i>
                      Update Information
                    </button>
                  </form>
                </div>
              </div>

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Change Password</h5>

                  <form id="password-form">
                    <div class="mb-3">
                      <label for="current_password" class="form-label">Current Password</label>
                      <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" required>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-key me-2"></i>
                      Update Password
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Account Status</h5>

                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-shield-check text-success me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Account Secure</p>
                      <p class="text-muted small mb-0">All security features enabled</p>
                    </div>
                  </div>

                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-person-check text-primary me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Profile Complete</p>
                      <p class="text-muted small mb-0">Keep your information updated</p>
                    </div>
                  </div>

                  <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history text-warning me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Last Login</p>
                      <p class="text-muted small mb-0" id="last-login">Loading...</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Tab Content -->
        <div id="team-content" class="tab-content" style="display: none;">
          <div class="loading-spinner" id="team-loading">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading team information...</p>
          </div>

          <div class="row gx-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Team Members</h5>
                  <div id="teamMembersList">
                    <!-- Team members will be loaded here -->
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Add Team Member</h5>

                  <form id="add-member-form">
                    <div class="mb-3">
                      <label for="member_select" class="form-label">Select User</label>
                      <select class="form-select" id="member_select" name="member_select" required>
                        <option value="">Select a user...</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="member_role" class="form-label">Role</label>
                      <select class="form-select" id="member_role" name="member_role" required>
                        <option value="member">Member</option>
                        <option value="admin">Admin</option>
                      </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                      <i class="bi bi-person-plus me-2"></i>
                      Add Member
                    </button>
                  </form>
                </div>
              </div>

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Team Statistics</h5>
                  <div class="text-center">
                    <div class="row text-center" id="team-stats">
                      <div class="col-4">
                        <div class="text-primary fw-bold fs-4" id="total-members">0</div>
                        <small class="text-muted">Total</small>
                      </div>
                      <div class="col-4">
                        <div class="text-success fw-bold fs-4" id="active-members">0</div>
                        <small class="text-muted">Active</small>
                      </div>
                      <div class="col-4">
                        <div class="text-warning fw-bold fs-4" id="admin-members">0</div>
                        <small class="text-muted">Admins</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    class AdminSettings {
        constructor() {
            this.apiBase = 'Settings.php';
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.loadUserData();
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
            document.getElementById('general-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateGeneralSettings();
            });

            document.getElementById('password-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.updatePassword();
            });

            document.getElementById('add-member-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.addTeamMember();
            });
        }

        switchTab(tabName) {
            // Update tab links
            document.querySelectorAll('.settings-tabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            document.getElementById(`${tabName}-content`).style.display = 'block';

            // Load tab data
            if (tabName === 'team') {
                this.loadTeamData();
            }
        }

        async loadUserData() {
            this.showLoading('general', true);
            try {
                const response = await this.apiCall('GET', 'user_data');
                if (response.success) {
                    const user = response.data;
                    document.getElementById('first_name').value = user.first_name || '';
                    document.getElementById('last_name').value = user.last_name || '';
                    document.getElementById('email').value = user.email || '';
                    
                    const lastLogin = new Date(user.created_at).toLocaleDateString('pt-PT');
                    document.getElementById('last-login').textContent = `Account created: ${lastLogin}`;
                }
            } catch (error) {
                this.showAlert('Error loading user data', 'danger');
            } finally {
                this.showLoading('general', false);
            }
        }

        async updateGeneralSettings() {
            const formData = new FormData(document.getElementById('general-form'));
            const data = Object.fromEntries(formData);

            try {
                const response = await this.apiCall('POST', 'update_general', data);
                this.showAlert(response.message, response.success ? 'success' : 'danger');
            } catch (error) {
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

            try {
                const response = await this.apiCall('POST', 'update_password', {
                    current_password: data.current_password,
                    new_password: data.new_password
                });
                this.showAlert(response.message, response.success ? 'success' : 'danger');

                if (response.success) {
                    document.getElementById('password-form').reset();
                }
            } catch (error) {
                this.showAlert('Error updating password', 'danger');
            }
        }

        async loadTeamData() {
            this.showLoading('team', true);
            try {
                const [teamResponse, usersResponse] = await Promise.all([
                    this.apiCall('GET', 'team_members'),
                    this.apiCall('GET', 'all_users')
                ]);

                if (teamResponse.success) {
                    this.displayTeamMembers(teamResponse.data);
                    this.updateTeamStats(teamResponse.data);
                }

                if (usersResponse.success) {
                    this.populateUserSelect(usersResponse.data);
                }
            } catch (error) {
                this.showAlert('Error loading team data', 'danger');
            } finally {
                this.showLoading('team', false);
            }
        }

        displayTeamMembers(members) {
            const teamsList = document.getElementById('teamMembersList');
            if (members.length === 0) {
                teamsList.innerHTML = '<p class="text-muted">No team members yet.</p>';
                return;
            }

            teamsList.innerHTML = members.map(member => `
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-${member.role === 'admin' ? 'success' : 'primary'} rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 40px; height: 40px; color: white; font-weight: bold;">
                            ${member.first_name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <p class="mb-0 fw-medium">${member.first_name} ${member.last_name}</p>
                            <p class="text-muted small mb-0">${member.email}</p>
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
            select.innerHTML = '<option value="">Select a user...</option>';
            users.forEach(user => {
                select.innerHTML += `<option value="${user.id}">${user.first_name} ${user.last_name} (${user.email})</option>`;
            });
        }

        updateTeamStats(members) {
            const totalMembers = members.length;
            const activeMembers = members.filter(m => m.status === 'active').length;
            const adminMembers = members.filter(m => m.role === 'admin').length;

            document.getElementById('total-members').textContent = totalMembers;
            document.getElementById('active-members').textContent = activeMembers;
            document.getElementById('admin-members').textContent = adminMembers;
        }

        async addTeamMember() {
            const formData = new FormData(document.getElementById('add-member-form'));
            const data = Object.fromEntries(formData);

            try {
                const response = await this.apiCall('POST', 'add_team_member', data);
                this.showAlert(response.message, response.success ? 'success' : 'danger');

                if (response.success) {
                    document.getElementById('add-member-form').reset();
                    this.loadTeamData();
                }
            } catch (error) {
                this.showAlert('Error adding team member', 'danger');
            }
        }

        async removeMember(memberId) {
            if (!confirm('Are you sure you want to remove this member?')) {
                return;
            }

            try {
                const response = await this.apiCall('POST', 'remove_team_member', { member_id: memberId });
                this.showAlert(response.message, response.success ? 'success' : 'danger');

                if (response.success) {
                    this.loadTeamData();
                }
            } catch (error) {
                this.showAlert('Error removing team member', 'danger');
            }
        }

        async apiCall(method, action, data = null) {
            const url = `${this.apiBase}?action=${action}`;
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                }
            };

            if (data && (method === 'POST' || method === 'PUT')) {
                options.body = JSON.stringify(data);
            }

            const response = await fetch(url, options);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        }

        showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

            alertContainer.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

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
            if (show) {
                loadingElement.classList.add('show');
            } else {
                loadingElement.classList.remove('show');
            }
        }
    }

    
    let adminSettings;
    document.addEventListener('DOMContentLoaded', () => {
        adminSettings = new AdminSettings();
    });
  </script>
</body>

</html>