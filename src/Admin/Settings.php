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
  <script src="js/Settings.js" defer></script>
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
                <p class="text-muted">Manage your store preferences and security</p>
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

        
        <div id="alert-container"></div>

        <div class="row mb-4">
          <div class="col-12">
            <ul class="nav nav-tabs settings-tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#" data-tab="general">General</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-tab="security">Security</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-tab="notifications">Notifications</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-tab="team">Team</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-tab="billing">Billing</a>
              </li>
            </ul>
          </div>
        </div>

        
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

        <
        <div id="security-content" class="tab-content">
          <div class="loading-spinner" id="security-loading">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading security settings...</p>
          </div>

          <div class="row gx-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Security Settings</h5>

                  <form id="security-form">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Login Notifications</p>
                        <p class="text-muted small mb-0">Get notified when someone logs into your account</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="login_alerts" name="login_alerts">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Session Timeout</p>
                        <p class="text-muted small mb-0">Automatically log out after inactivity</p>
                      </div>
                      <select class="form-select" style="width: auto;" id="session_timeout" name="session_timeout">
                        <option value="1800">30 minutes</option>
                        <option value="3600">1 hour</option>
                        <option value="7200">2 hours</option>
                        <option value="14400">4 hours</option>
                        <option value="28800">8 hours</option>
                      </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Login Attempts Limit</p>
                        <p class="text-muted small mb-0">Maximum failed login attempts before lockout</p>
                      </div>
                      <select class="form-select" style="width: auto;" id="login_attempts_limit" name="login_attempts_limit">
                        <option value="3">3 attempts</option>
                        <option value="5">5 attempts</option>
                        <option value="10">10 attempts</option>
                      </select>
                    </div>

                    <div class="mb-4 pb-3 border-bottom">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                          <p class="mb-0 fw-medium">IP Address Restrictions</p>
                          <p class="text-muted small mb-0">Restrict access to specific IP addresses</p>
                        </div>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="ip_restriction" name="ip_restriction">
                        </div>
                      </div>
                      <div id="ip-restriction-settings" style="display: none;">
                        <label for="allowed_ips" class="form-label">Allowed IP Addresses (one per line)</label>
                        <textarea class="form-control" id="allowed_ips" name="allowed_ips" rows="3" placeholder="192.168.1.1&#10;10.0.0.1"></textarea>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-shield-check me-2"></i>
                      Update Security Settings
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Security Status</h5>

                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-shield-check text-success me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Account Secure</p>
                      <p class="text-muted small mb-0">All security features configured</p>
                    </div>
                  </div>

                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-key-fill text-primary me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Strong Password</p>
                      <p class="text-muted small mb-0">Password strength: Strong</p>
                    </div>
                  </div>

                  <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history text-warning me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Security Check</p>
                      <p class="text-muted small mb-0">Last check: Today</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

     
        <div id="notifications-content" class="tab-content">
          <div class="loading-spinner" id="notifications-loading">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading notification settings...</p>
          </div>

          <div class="row gx-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Email Notifications</h5>

                  <form id="notifications-form">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Email Notifications</p>
                        <p class="text-muted small mb-0">Receive notifications via email</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="email_notifications" name="email_notifications">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">SMS Notifications</p>
                        <p class="text-muted small mb-0">Receive important alerts via SMS</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="sms_notifications" name="sms_notifications">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Push Notifications</p>
                        <p class="text-muted small mb-0">Show notifications in your browser</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="push_notifications" name="push_notifications">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Security Alerts</p>
                        <p class="text-muted small mb-0">Important security notifications</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="security_alerts" name="security_alerts">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Weekly Reports</p>
                        <p class="text-muted small mb-0">Receive weekly sales and analytics reports</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="weekly_reports" name="weekly_reports">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">System Updates</p>
                        <p class="text-muted small mb-0">Notifications about system updates</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="system_updates" name="system_updates">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Team Invitations</p>
                        <p class="text-muted small mb-0">Notifications for team invitations</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="team_invitations" name="team_invitations">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                      <div>
                        <p class="mb-0 fw-medium">Marketing Updates</p>
                        <p class="text-muted small mb-0">News and updates about new features</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="marketing_emails" name="marketing_emails">
                      </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <p class="mb-0 fw-medium">Billing Alerts</p>
                        <p class="text-muted small mb-0">Payment and billing notifications</p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="billing_alerts" name="billing_alerts">
                      </div>
                    </div>

                    <div class="mt-4">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-bell me-2"></i>
                        Save Notification Preferences
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Notification Settings</h5>

                  <div class="mb-3">
                    <label for="notificationFrequency" class="form-label">Frequency</label>
                    <select class="form-select" id="notificationFrequency">
                      <option selected>Instant</option>
                      <option>Every 15 minutes</option>
                      <option>Hourly</option>
                      <option>Daily digest</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="quietHours" class="form-label">Quiet Hours</label>
                    <div class="row">
                      <div class="col-6">
                        <input type="time" class="form-control" value="22:00">
                      </div>
                      <div class="col-6">
                        <input type="time" class="form-control" value="08:00">
                      </div>
                    </div>
                    <small class="text-muted">No notifications during these hours</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        
        <div id="team-content" class="tab-content">
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

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Team Permissions</h5>

                  <div class="table-responsive">
                    <table class="table table-borderless">
                      <thead>
                        <tr class="border-bottom">
                          <th scope="col">Permission</th>
                          <th scope="col" class="text-center">Owner</th>
                          <th scope="col" class="text-center">Admin</th>
                          <th scope="col" class="text-center">Member</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Manage Products</td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                        </tr>
                        <tr>
                          <td>View Orders</td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                          <td>Manage Team</td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                          <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                        </tr>
                        <tr>
                          <td>Access Analytics</td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                        </tr>
                        <tr>
                          <td>Settings Access</td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                          <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Create New Team</h5>

                  <form id="create-team-form">
                    <div class="mb-3">
                      <label for="name" class="form-label">Team Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter team name" required>
                    </div>

                    <div class="mb-3">
                      <label for="team_description" class="form-label">Description (optional)</label>
                      <textarea class="form-control" id="team_description" name="team_description" rows="3" placeholder="Describe your team"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                      <i class="bi bi-people me-2"></i>
                      Create Team
                    </button>
                  </form>
                </div>
              </div>

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Add Team Member</h5>

                  <form id="invite-member-form">
                    <div class="mb-3">
                      <label for="invite_team" class="form-label">Select Team</label>
                      <select class="form-select" id="invite_team" name="invite_team" required>
                        <option value="">Select a team...</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="invite_email" class="form-label">Email Address</label>
                      <input type="email" class="form-control" id="invite_email" name="invite_email" placeholder="Enter email address" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                      <i class="bi bi-person-plus me-2"></i>
                      Send Invitation
                    </button>
                  </form>

                  <hr class="my-4">

                  <div class="text-center">
                    <h6 class="fw-medium mb-2">Team Statistics</h6>
                    <div class="row text-center" id="team-stats">
                      <div class="col-4">
                        <div class="text-primary fw-bold fs-4" id="total-teams">0</div>
                        <small class="text-muted">Total</small>
                      </div>
                      <div class="col-4">
                        <div class="text-success fw-bold fs-4" id="active-teams">0</div>
                        <small class="text-muted">Active</small>
                      </div>
                      <div class="col-4">
                        <div class="text-warning fw-bold fs-4" id="total-members">0</div>
                        <small class="text-muted">Members</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        
        <div id="billing-content" class="tab-content">
          <div class="loading-spinner" id="billing-loading">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading billing information...</p>
          </div>

          <div class="row gx-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Current Plan</h5>

                  <div id="current-plan-info">
                    <!-- Current plan will be loaded here -->
                  </div>
                </div>
              </div>

              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Choose Your Plan</h5>

                  <div class="row" id="billing-plans">
                    <div class="col-md-3 mb-3">
                      <div class="card h-100 plan-card" data-plan="free">
                        <div class="card-body text-center">
                          <h6 class="card-title">Free</h6>
                          <div class="h4 text-primary plan-price">€0<small class="text-muted">/month</small></div>
                          <p class="card-text">Basic features for getting started</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="card h-100 plan-card" data-plan="basic">
                        <div class="card-body text-center">
                          <h6 class="card-title">Basic</h6>
                          <div class="h4 text-primary plan-price">€9.99<small class="text-muted">/month</small></div>
                          <p class="card-text">Perfect for small teams</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="card h-100 plan-card" data-plan="premium">
                        <div class="card-body text-center">
                          <h6 class="card-title">Premium</h6>
                          <div class="h4 text-primary plan-price">€19.99<small class="text-muted">/month</small></div>
                          <p class="card-text">Advanced features for growing teams</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="card h-100 plan-card" data-plan="enterprise">
                        <div class="card-body text-center">
                          <h6 class="card-title">Enterprise</h6>
                          <div class="h4 text-primary plan-price">€49.99<small class="text-muted">/month</small></div>
                          <p class="card-text">Full featured for large organizations</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="text-center mb-3">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="billing_cycle" id="monthly" value="monthly" checked>
                      <label class="form-check-label" for="monthly">Monthly</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="billing_cycle" id="yearly" value="yearly">
                      <label class="form-check-label" for="yearly">Yearly (Save 20%)</label>
                    </div>
                  </div>

                  <div class="text-center">
                    <button id="update-plan-btn" class="btn btn-primary">
                      <i class="bi bi-credit-card me-2"></i>
                      Update Plan
                    </button>
                  </div>
                </div>
              </div>

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Billing History</h5>

                  <div id="billing-history">
                    <!-- Billing history will be loaded here -->
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Billing Summary</h5>

                  <div id="billing-summary">
                    <!-- Billing summary will be loaded here -->
                  </div>
                </div>
              </div>

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Usage Statistics</h5>

                  <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                      <span class="small">Products</span>
                      <span class="small fw-medium">45 / Unlimited</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar bg-primary" style="width: 25%"></div>
                    </div>
                  </div>

                  <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                      <span class="small">Orders This Month</span>
                      <span class="small fw-medium">127 / Unlimited</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar bg-success" style="width: 35%"></div>
                    </div>
                  </div>

                  <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                      <span class="small">Team Members</span>
                      <span class="small fw-medium" id="team-members-count">0 / 10</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar bg-warning" style="width: 30%"></div>
                    </div>
                  </div>

                  <div>
                    <div class="d-flex justify-content-between mb-2">
                      <span class="small">Storage Used</span>
                      <span class="small fw-medium">2.3GB / 50GB</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar bg-info" style="width: 5%"></div>
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
            this.apiBase = '../../restapi/AdminSettings.php';
            this.currentTab = 'general';
            this.selectedPlan = 'free';
            this.selectedBillingCycle = 'monthly';

            this.init();
        }

        init() {
            this.setupEventListeners();
            this.loadInitialData();
        }

        setupEventListeners() {
          
            document.querySelectorAll('.settings-tabs .nav-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.switchTab(e.target.dataset.tab);
                });
            });

            document.getElementById('general-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateGeneralSettings();
            });

           
            document.getElementById('password-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.updatePassword();
            });

            document.getElementById('security-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateSecuritySettings();
            });

            
            document.getElementById('notifications-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateNotificationSettings();
            });

           
            document.getElementById('create-team-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.createTeam();
            });

           
            document.getElementById('invite-member-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.inviteTeamMember();
            });

            
            document.querySelectorAll('.plan-card').forEach(card => {
                card.addEventListener('click', (e) => {
                    this.selectPlan(e.currentTarget.dataset.plan);
                });
            });

            
            document.querySelectorAll('input[name="billing_cycle"]').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    this.selectedBillingCycle = e.target.value;
                    this.updatePlanPrices();
                });
            });

           
            document.getElementById('update-plan-btn').addEventListener('click', () => {
                this.updateBillingPlan();
            });

            
            document.getElementById('ip_restriction').addEventListener('change', (e) => {
                const ipSettings = document.getElementById('ip-restriction-settings');
                ipSettings.style.display = e.target.checked ? 'block' : 'none';
            });
        }

        switchTab(tabName) {
           
            document.querySelectorAll('.settings-tabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            document.getElementById(`${tabName}-content`).style.display = 'block';

            this.currentTab = tabName;

            
            this.loadTabData(tabName);
        }

        loadInitialData() {
            this.loadTabData('general');
        }

        loadTabData(tabName) {
            switch(tabName) {
                case 'general':
                    this.loadUserData();
                    break;
                case 'security':
                    this.loadSecuritySettings();
                    break;
                case 'notifications':
                    this.loadNotificationSettings();
                    break;
                case 'team':
                    this.loadTeamData();
                    break;
                case 'billing':
                    this.loadBillingData();
                    break;
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

        async loadSecuritySettings() {
            this.showLoading('security', true);
            try {
                const response = await this.apiCall('GET', 'security_settings');
                if (response.success) {
                    const settings = response.data;
                    document.getElementById('login_alerts').checked = settings.login_alerts == 1;
                    document.getElementById('session_timeout').value = settings.session_timeout;
                    document.getElementById('login_attempts_limit').value = settings.login_attempts_limit;
                    document.getElementById('ip_restriction').checked = settings.ip_restriction == 1;
                    document.getElementById('allowed_ips').value = settings.allowed_ips || '';

                    
                    const ipSettings = document.getElementById('ip-restriction-settings');
                    ipSettings.style.display = settings.ip_restriction == 1 ? 'block' : 'none';
                }
            } catch (error) {
                this.showAlert('Error loading security settings', 'danger');
            } finally {
                this.showLoading('security', false);
            }
        }

        async updateSecuritySettings() {
            const formData = new FormData(document.getElementById('security-form'));
            const data = {
                login_alerts: document.getElementById('login_alerts').checked ? 1 : 0,
                session_timeout: parseInt(formData.get('session_timeout')),
                login_attempts_limit: parseInt(formData.get('login_attempts_limit')),
                ip_restriction: document.getElementById('ip_restriction').checked ? 1 : 0,
                allowed_ips: formData.get('allowed_ips')
            };

            try {
                const response = await this.apiCall('POST', 'update_security', data);
                this.showAlert(response.message, response.success ? 'success' : 'danger');
            } catch (error) {
                this.showAlert('Error updating security settings', 'danger');
            }
        }

        async loadNotificationSettings() {
            this.showLoading('notifications', true);
            try {
                const response = await this.apiCall('GET', 'notification_settings');
                if (response.success) {
                    const settings = response.data;
                    document.getElementById('email_notifications').checked = settings.email_notifications == 1;
                    document.getElementById('sms_notifications').checked = settings.sms_notifications == 1;
                    document.getElementById('push_notifications').checked = settings.push_notifications == 1;
                    document.getElementById('security_alerts').checked = settings.security_alerts == 1;
                    document.getElementById('marketing_emails').checked = settings.marketing_emails == 1;
                    document.getElementById('weekly_reports').checked = settings.weekly_reports == 1;
                    document.getElementById('system_updates').checked = settings.system_updates == 1;
                    document.getElementById('team_invitations').checked = settings.team_invitations == 1;
                    document.getElementById('billing_alerts').checked = settings.billing_alerts == 1;
                }
            } catch (error) {
                this.showAlert('Error loading notification settings', 'danger');
            } finally {
                this.showLoading('notifications', false);
            }
        }

        async updateNotificationSettings() {
            const data = {
                email_notifications: document.getElementById('email_notifications').checked ? 1 : 0,
                sms_notifications: document.getElementById('sms_notifications').checked ? 1 : 0,
                push_notifications: document.getElementById('push_notifications').checked ? 1 : 0,
                security_alerts: document.getElementById('security_alerts').checked ? 1 : 0,
                marketing_emails: document.getElementById('marketing_emails').checked ? 1 : 0,
                weekly_reports: document.getElementById('weekly_reports').checked ? 1 : 0,
                system_updates: document.getElementById('system_updates').checked ? 1 : 0,
                team_invitations: document.getElementById('team_invitations').checked ? 1 : 0,
                billing_alerts: document.getElementById('billing_alerts').checked ? 1 : 0
            };

            try {
                const response = await this.apiCall('POST', 'update_notifications', data);
                this.showAlert(response.message, response.success ? 'success' : 'danger');
            } catch (error) {
                this.showAlert('Error updating notification settings', 'danger');
            }
        }

        async loadTeamData() {
            this.showLoading('team', true);
            try {
                const response = await this.apiCall('GET', 'teams');
                if (response.success) {
                    this.displayTeams(response.data);
                    this.populateTeamSelect(response.data);
                    this.updateTeamStats(response.data);
                }
            } catch (error) {
                this.showAlert('Error loading team data', 'danger');
            } finally {
                this.showLoading('team', false);
            }
        }

        displayTeams(teams) {
            const teamsList = document.getElementById('teamMembersList');
            if (teams.length === 0) {
                teamsList.innerHTML = '<p class="text-muted">No teams created yet.</p>';
                return;
            }

            teamsList.innerHTML = teams.map(team => `
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 40px; height: 40px; color: white; font-weight: bold;">
                            ${team.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <p class="mb-0 fw-medium">${team.name}</p>
                            <p class="text-muted small mb-0">${team.user_role} • ${team.member_count} member(s)</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge ${team.user_role === 'owner' ? 'bg-primary' : 'bg-light text-dark'} me-2">
                            ${team.user_role}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        populateTeamSelect(teams) {
            const select = document.getElementById('invite_team');
            select.innerHTML = '<option value="">Select a team...</option>';
            teams.forEach(team => {
                if (team.user_role === 'owner' || team.user_role === 'admin') {
                    select.innerHTML += `<option value="${team.id}">${team.name}</option>`;
                }
            });
        }

        updateTeamStats(teams) {
            const totalTeams = teams.length;
            const activeTeams = teams.filter(t => t.status === 'active').length;
            const totalMembers = teams.reduce((sum, team) => sum + parseInt(team.member_count), 0);

            document.getElementById('total-teams').textContent = totalTeams;
            document.getElementById('active-teams').textContent = activeTeams;
            document.getElementById('total-members').textContent = totalMembers;
            document.getElementById('team-members-count').textContent = `${totalMembers} / 10`;
        }

        async createTeam() {
            const formData = new FormData(document.getElementById('create-team-form'));
            const data = Object.fromEntries(formData);

            try {
                const response = await this.apiCall('POST', 'create_team', data);
                this.showAlert(response.message, response.success ? 'success' : 'danger');

                if (response.success) {
                    document.getElementById('create-team-form').reset();
                    this.loadTeamData(); // Reload team list
                }
            } catch (error) {
                this.showAlert('Error creating team', 'danger');
            }
        }

        async inviteTeamMember() {
            const formData = new FormData(document.getElementById('invite-member-form'));
            const data = Object.fromEntries(formData);

            try {
                const response = await this.apiCall('POST', 'invite_team_member', data);
                this.showAlert(response.message, response.success ? 'success' : 'danger');

                if (response.success) {
                    document.getElementById('invite-member-form').reset();
                }
            } catch (error) {
                this.showAlert('Error inviting team member', 'danger');
            }
        }

        async loadBillingData() {
            this.showLoading('billing', true);
            try {
                const [billingResponse, historyResponse] = await Promise.all([
                    this.apiCall('GET', 'billing_info'),
                    this.apiCall('GET', 'billing_history')
                ]);

                if (billingResponse.success) {
                    this.displayCurrentPlan(billingResponse.data);
                    this.displayBillingSummary(billingResponse.data);
                    this.selectedPlan = billingResponse.data.plan_type;
                    this.selectedBillingCycle = billingResponse.data.billing_cycle;
                    this.updatePlanSelection();
                }

                if (historyResponse.success) {
                    this.displayBillingHistory(historyResponse.data);
                }
            } catch (error) {
                this.showAlert('Error loading billing data', 'danger');
            } finally {
                this.showLoading('billing', false);
            }
        }

        displayCurrentPlan(billing) {
            const currentPlanInfo = document.getElementById('current-plan-info');
            currentPlanInfo.innerHTML = `
                <div class="d-flex justify-content-between align-items-center p-4 bg-light rounded">
                    <div>
                        <h6 class="fw-bold mb-1 text-capitalize">${billing.plan_type} Plan</h6>
                        <p class="text-muted mb-0">Current subscription plan</p>
                    </div>
                    <div class="text-end">
                        <div class="fs-4 fw-bold text-primary">€${billing.price}</div>
                        <small class="text-muted">per ${billing.billing_cycle === 'yearly' ? 'year' : 'month'}</small>
                    </div>
                </div>
                ${billing.status !== 'active' ? `
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Subscription status: ${billing.status}
                    </div>
                ` : ''}
            `;
        }

        displayBillingSummary(billing) {
            const billingSummary = document.getElementById('billing-summary');
            const nextBilling = billing.next_billing_date ?
                new Date(billing.next_billing_date).toLocaleDateString('pt-PT') : 'N/A';

            billingSummary.innerHTML = `
                <div class="d-flex justify-content-between mb-3">
                    <span>Current Plan</span>
                    <span class="fw-medium text-capitalize">${billing.plan_type}</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>Next Billing Date</span>
                    <span class="fw-medium">${nextBilling}</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>Amount Due</span>
                    <span class="fw-bold text-primary">€${billing.price}</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3">
                    <span>Status</span>
                    <span class="fw-medium text-capitalize text-${billing.status === 'active' ? 'success' : 'warning'}">${billing.status}</span>
                </div>
            `;
        }

        displayBillingHistory(history) {
            const billingHistory = document.getElementById('billing-history');
            if (history.length === 0) {
                billingHistory.innerHTML = '<p class="text-muted">No billing history available.</p>';
                return;
            }

            billingHistory.innerHTML = `
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr class="border-bottom">
                                <th scope="col">Date</th>
                                <th scope="col">Description</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${history.map(item => `
                                <tr>
                                    <td>${new Date(item.created_at).toLocaleDateString('pt-PT')}</td>
                                    <td>${item.description || 'Subscription Payment'}</td>
                                    <td>€${item.amount} ${item.currency}</td>
                                    <td>
                                        <span class="badge bg-${this.getStatusColor(item.status)}">
                                            ${this.translateStatus(item.status)}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        getStatusColor(status) {
            const colors = {
                'completed': 'success',
                'pending': 'warning',
                'failed': 'danger',
                'refunded': 'secondary'
            };
            return colors[status] || 'secondary';
        }

        translateStatus(status) {
            const translations = {
                'completed': 'Paid',
                'pending': 'Pending',
                'failed': 'Failed',
                'refunded': 'Refunded'
            };
            return translations[status] || status;
        }

        selectPlan(planType) {
            this.selectedPlan = planType;
            this.updatePlanSelection();
        }

        updatePlanSelection() {
            document.querySelectorAll('.plan-card').forEach(card => {
                card.classList.remove('border-primary');
                card.style.borderWidth = '1px';
            });
            const selectedCard = document.querySelector(`[data-plan="${this.selectedPlan}"]`);
            if (selectedCard) {
                selectedCard.classList.add('border-primary');
                selectedCard.style.borderWidth = '2px';
            }
            this.updatePlanPrices();
        }

        updatePlanPrices() {
            const prices = {
                'free': { monthly: 0, yearly: 0 },
                'basic': { monthly: 9.99, yearly: 99.99 },
                'premium': { monthly: 19.99, yearly: 199.99 },
                'enterprise': { monthly: 49.99, yearly: 499.99 }
            };

            document.querySelectorAll('.plan-card').forEach(card => {
                const planType = card.dataset.plan;
                const priceElement = card.querySelector('.plan-price');
                const price = prices[planType][this.selectedBillingCycle];
                const period = this.selectedBillingCycle === 'yearly' ? '/year' : '/month';
                priceElement.innerHTML = `€${price}<small class="text-muted">${period}</small>`;
            });
        }

        async updateBillingPlan() {
            try {
                const response = await this.apiCall('POST', 'update_billing_plan', {
                    plan_type: this.selectedPlan,
                    billing_cycle: this.selectedBillingCycle
                });
                this.showAlert(response.message, response.success ? 'success' : 'danger');

                if (response.success) {
                    this.loadBillingData(); // Reload billing data
                }
            } catch (error) {
                this.showAlert('Error updating billing plan', 'danger');
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

    
    document.addEventListener('DOMContentLoaded', () => {
        new AdminSettings();
    });
  </script>
</body>

</html>