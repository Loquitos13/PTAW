<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8">
  <title>Admin - Settings</title>
  <link rel="stylesheet" href="css/Settings.css">
</head>
<body>
  <?php include '../includes/header-desktop-admin.php'; ?>
  <?php include '../includes/header-mobile-admin.php'; ?>

  <main class="settings-container">
    <aside class="sidebar">
      <ul>
        <li><a href="Dashboard.php">Dashboard</a></li>
        <li><a href="Products.html">Products</a></li>
        <li><a href="Orders.html">Orders</a></li>
        <li><a href="analytics.php">Analytics</a></li>
        <li><a href="Discounts.html">Discounts</a></li>
        <li class="active"><a href="Settings.html">Settings</a></li>
      </ul>
    </aside>

    <section class="settings-content">
      <h1>Settings</h1>
      <p class="subtitle">Manage your store preferences and security</p>

      <div class="tabs">
        <button class="tab active">General</button>
        <button class="tab">Security</button>
        <button class="tab">Notifications</button>
        <button class="tab">Team</button>
        <button class="tab">Billing</button>
      </div>

      <div class="settings-panels">
        <!-- Store Settings -->
        <div class="panel store-settings">
          <h2>Store Settings</h2>
          <label>Store Name <input type="text" value="Print&Go"></label>
          <label>Contact Email <input type="email" value="contact@print&go.com"></label>
          <label>Currency 
            <select>
              <option>EUR (€)</option>
              <option>USD ($)</option>
            </select>
          </label>
        </div>

        <!-- Security Settings -->
        <div class="panel security-settings">
          <h2>Security Settings</h2>
          <div class="toggle-section">
            <span>Two-factor Authentication</span>
            <input type="checkbox">
          </div>
          <div class="history-link">
            <span>Login History</span>
            <a href="#">View History</a>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="panel quick-actions">
          <h2>Quick Actions</h2>
          <button class="export">Export Store Data</button>
          <button class="clear">Clear Cache</button>
          <button class="support">Contact Support</button>
        </div>
      </div>
    </section>
  </main>

  <script src="js/Settings.js"></script>
</body>
</html>v