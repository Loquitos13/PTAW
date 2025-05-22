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


  /* Ajustes para telas menores */
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
                <span class="ms-2">John Doe</span>
              </div>
            </div>
          </div>
        </div>

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
          <div class="row gx-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Store Settings</h5>

                  <div class="mb-3">
                    <label for="storeName" class="form-label">Store Name</label>
                    <input type="text" class="form-control" id="storeName" value="Print&Go">
                  </div>

                  <div class="mb-3">
                    <label for="contactEmail" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contactEmail" value="contact@print&go.com">
                  </div>

                  <div class="mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <div class="position-relative">
                      <select class="form-select" id="currency">
                        <option selected>EUR (€)</option>
                        <option>USD ($)</option>
                        <option>GBP (£)</option>
                      </select>
                      <i class="bi bi-chevron-down position-absolute end-0 top-50 translate-middle-y me-3"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Business Hours</h5>
                  
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="openTime" class="form-label">Opening Time</label>
                      <input type="time" class="form-control" id="openTime" value="09:00">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="closeTime" class="form-label">Closing Time</label>
                      <input type="time" class="form-control" id="closeTime" value="18:00">
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label for="timezone" class="form-label">Timezone</label>
                    <select class="form-select" id="timezone">
                      <option selected>Europe/Lisbon</option>
                      <option>Europe/London</option>
                      <option>America/New_York</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Quick Actions</h5>

                  <button class="btn btn-primary w-100 mb-3" id="exportBtn">
                    <i class="bi bi-download me-2"></i>
                    Export Store Data
                  </button>

                  <button class="btn btn-light w-100 mb-3" id="clearCacheBtn">
                    <i class="bi bi-trash me-2"></i>
                    Clear Cache
                  </button>

                  <button class="btn btn-outline-secondary w-100 mb-3" id="supportBtn">
                    <i class="bi bi-envelope me-2"></i>
                    Contact Support
                  </button>

                  <button class="btn btn-primary w-100" id="saveChangesBtn">
                    <i class="bi bi-floppy me-2"></i>
                    Save Changes
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="security-content" class="tab-content">
          <div class="row gx-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <h5 class="card-title mb-4">Security Settings</h5>

                  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                      <p class="mb-0 fw-medium">Two-factor Authentication</p>
                      <p class="text-muted small mb-0">Add an extra layer of security to your account</p>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="twoFactorSwitch">
                    </div>
                  </div>

                  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                      <p class="mb-0 fw-medium">Login Notifications</p>
                      <p class="text-muted small mb-0">Get notified when someone logs into your account</p>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="loginNotificationSwitch" checked>
                    </div>
                  </div>

                  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                      <p class="mb-0 fw-medium">Session Timeout</p>
                      <p class="text-muted small mb-0">Automatically log out after inactivity</p>
                    </div>
                    <select class="form-select" style="width: auto;">
                      <option>30 minutes</option>
                      <option selected>1 hour</option>
                      <option>4 hours</option>
                      <option>Never</option>
                    </select>
                  </div>

                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="mb-0 fw-medium">Login History</p>
                      <p class="text-muted small mb-0">View and manage devices where you're logged in</p>
                    </div>
                    <a href="#" class="link-primary">View History</a>
                  </div>
                </div>
              </div>

              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title mb-4">Password Settings</h5>
                  
                  <div class="mb-3">
                    <label for="currentPassword" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="currentPassword">
                  </div>
                  
                  <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newPassword">
                  </div>
                  
                  <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirmPassword">
                  </div>
                  
                  <button class="btn btn-primary">Update Password</button>
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
                      <p class="text-muted small mb-0">All security features enabled</p>
                    </div>
                  </div>
                  
                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-key-fill text-primary me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Strong Password</p>
                      <p class="text-muted small mb-0">Last changed 30 days ago</p>
                    </div>
                  </div>
                  
                  <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history text-warning me-3 fs-4"></i>
                    <div>
                      <p class="mb-0 fw-medium">Last Login</p>
                      <p class="text-muted small mb-0">Today at 2:30 PM</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="notifications-content" class="tab-content"></div>
        <div id="team-content" class="tab-content"></div>
        <div id="billing-content" class="tab-content"></div>
        </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>