<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');


error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'Database.php';


function checkAuth() {

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = 1; 
    }
    return $_SESSION['user_id'];
}

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? '';
    $user_id = checkAuth();


    if (in_array($action, ['user_data', 'update_general', 'update_password'])) {
        require_once '../classes/UserSettingsManager.php';
        $userSettings = new UserSettingsManager();
    }

    if (in_array($action, ['security_settings', 'update_security'])) {
        require_once '../classes/SecuritySettingsManager.php';
        $securitySettings = new SecuritySettingsManager();
    }

    if (in_array($action, ['notification_settings', 'update_notifications'])) {
        require_once '../classes/NotificationSettingsManager.php';
        $notificationSettings = new NotificationSettingsManager();
    }

    if (in_array($action, ['teams', 'create_team', 'invite_team_member'])) {
        require_once '../classes/TeamManager.php';
        $teamManager = new TeamManager();
    }

    if (in_array($action, ['billing_info', 'billing_history', 'update_billing_plan'])) {
        require_once '../classes/BillingManager.php';
        $billingManager = new BillingManager();
    }

    if ($method === 'GET') {
        switch ($action) {
            case 'user_data':
                if (isset($userSettings)) {
                    $data = $userSettings->getUserData($user_id);
                    echo json_encode(['success' => true, 'data' => $data]);
                } else {

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'id' => $user_id,
                            'username' => 'admin',
                            'email' => 'admin@printgo.com',
                            'first_name' => 'Admin',
                            'last_name' => 'User',
                            'role' => 'admin',
                            'status' => 'active',
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    ]);
                }
                break;

            case 'security_settings':
                if (isset($securitySettings)) {
                    $data = $securitySettings->getSecuritySettings($user_id);
                    echo json_encode(['success' => true, 'data' => $data]);
                } else {

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'login_alerts' => 1,
                            'session_timeout' => 3600,
                            'login_attempts_limit' => 5,
                            'ip_restriction' => 0,
                            'allowed_ips' => ''
                        ]
                    ]);
                }
                break;

            case 'notification_settings':
                if (isset($notificationSettings)) {
                    $data = $notificationSettings->getNotificationSettings($user_id);
                    echo json_encode(['success' => true, 'data' => $data]);
                } else {

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'email_notifications' => 1,
                            'sms_notifications' => 0,
                            'push_notifications' => 1,
                            'security_alerts' => 1,
                            'marketing_emails' => 0,
                            'weekly_reports' => 1,
                            'system_updates' => 1,
                            'team_invitations' => 1,
                            'billing_alerts' => 1
                        ]
                    ]);
                }
                break;

            case 'teams':
                if (isset($teamManager)) {
                    $data = $teamManager->getUserTeams($user_id);
                    echo json_encode(['success' => true, 'data' => $data]);
                } else {

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            [
                                'id' => 1,
                                'name' => 'Admin Team',
                                'description' => 'Main administration team',
                                'user_role' => 'owner',
                                'member_count' => 1,
                                'status' => 'active',
                                'joined_at' => date('Y-m-d H:i:s')
                            ]
                        ]
                    ]);
                }
                break;

            case 'billing_info':
                if (isset($billingManager)) {
                    $data = $billingManager->getBillingInfo($user_id);
                    echo json_encode(['success' => true, 'data' => $data]);
                } else {
                   
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'plan_type' => 'free',
                            'billing_cycle' => 'monthly',
                            'price' => 0.00,
                            'currency' => 'EUR',
                            'status' => 'active',
                            'next_billing_date' => null
                        ]
                    ]);
                }
                break;

            case 'billing_history':
                if (isset($billingManager)) {
                    $limit = $_GET['limit'] ?? 10;
                    $data = $billingManager->getBillingHistory($user_id, $limit);
                    echo json_encode(['success' => true, 'data' => $data]);
                } else {
                   
                    echo json_encode(['success' => true, 'data' => []]);
                }
                break;

            default:
                echo json_encode([
                    'success' => true,
                    'message' => 'Admin Settings API is working!',
                    'endpoints' => [
                        'GET' => ['user_data', 'security_settings', 'notification_settings', 'teams', 'billing_info', 'billing_history'],
                        'POST' => ['update_general', 'update_password', 'update_security', 'update_notifications', 'create_team', 'invite_team_member', 'update_billing_plan']
                    ],
                    'debug' => [
                        'user_id' => $user_id,
                        'timestamp' => date('Y-m-d H:i:s'),
                        'database_available' => class_exists('Database')
                    ]
                ]);
        }
    }

    elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        switch ($action) {
            case 'update_general':
                if (!isset($input['first_name']) || !isset($input['last_name']) || !isset($input['email'])) {
                    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                    break;
                }

                if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
                    break;
                }

                if (isset($userSettings)) {
                    $result = $userSettings->updateGeneralSettings($user_id, $input);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => true, 'message' => 'General settings updated successfully!']);
                }
                break;

            case 'update_password':
                if (!isset($input['current_password']) || !isset($input['new_password'])) {
                    echo json_encode(['success' => false, 'message' => 'Missing password fields']);
                    break;
                }

                if (strlen($input['new_password']) < 8) {
                    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
                    break;
                }

                if (isset($userSettings)) {
                    $result = $userSettings->updatePassword($user_id, $input['current_password'], $input['new_password']);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
                }
                break;

            case 'update_security':
                if (isset($securitySettings)) {
                    $result = $securitySettings->updateSecuritySettings($user_id, $input);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Security settings updated successfully!']);
                }
                break;

            case 'update_notifications':
                if (isset($notificationSettings)) {
                    $result = $notificationSettings->updateNotificationSettings($user_id, $input);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Notification settings updated successfully!']);
                }
                break;

            case 'create_team':
                if (!isset($input['name'])) {
                    echo json_encode(['success' => false, 'message' => 'Team name is required']);
                    break;
                }

                if (isset($teamManager)) {
                    $result = $teamManager->createTeam($user_id, $input['name'], $input['description'] ?? '');
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Team "' . $input['name'] . '" created successfully!', 'team_id' => rand(1, 100)]);
                }
                break;

            case 'invite_team_member':
                if (!isset($input['team_id']) || !isset($input['email'])) {
                    echo json_encode(['success' => false, 'message' => 'Team ID and email are required']);
                    break;
                }

                if (isset($teamManager)) {
                    $result = $teamManager->inviteTeamMember($input['team_id'], $user_id, $input['email']);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Invitation sent to ' . $input['email'] . ' successfully!']);
                }
                break;

            case 'update_billing_plan':
                if (!isset($input['plan_type']) || !isset($input['billing_cycle'])) {
                    echo json_encode(['success' => false, 'message' => 'Plan type and billing cycle are required']);
                    break;
                }

                if (isset($billingManager)) {
                    $result = $billingManager->updateBillingPlan($user_id, $input['plan_type'], $input['billing_cycle']);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Billing plan updated to ' . $input['plan_type'] . ' (' . $input['billing_cycle'] . ') successfully!']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action: ' . $action]);
        }
    }

    else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'action' => $action ?? 'none',
            'trace' => $e->getTraceAsString()
        ]
    ]);
}
?>