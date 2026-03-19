<?php
require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../helpers/Auth.php';
require_once __DIR__ . '/../../helpers/Url.php';

$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PreNacer</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/custom.css">
    
    
    <style>
        :root {
            --apple-bg: #f5f5f7;
            --apple-text: #1d1d1f;
            --apple-gray: #86868b;
            --apple-blue: #367d84;
            --apple-blue-hover: #2a6369;
            --apple-border: #d2d2d7;
            --apple-card: #ffffff;
            --apple-sidebar: #ffffff;
            --apple-sidebar-active: #f5f5f7;
            --apple-shadow: rgba(0, 0, 0, 0.04);
        }

        * { box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--apple-bg);
            color: var(--apple-text);
            overflow-x: hidden;
            margin: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: var(--apple-sidebar);
            box-shadow: 0 2px 20px var(--apple-shadow);
            border-right: 1px solid rgba(0,0,0,0.04);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }

        .sidebar-header img {
            height: 36px;
            margin-right: 0.75rem;
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
            color: var(--apple-text);
        }

        .sidebar-menu {
            padding: 1rem 0.75rem;
            list-style: none;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu li {
            margin-bottom: 4px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            color: var(--apple-gray);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: var(--apple-sidebar-active);
            color: var(--apple-text);
        }

        .sidebar-menu a.active {
            background: var(--apple-sidebar-active);
            color: var(--apple-blue);
        }

        .sidebar-menu a i {
            width: 20px;
            font-size: 15px;
            margin-right: 10px;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: var(--apple-card);
            height: 64px;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px var(--apple-shadow);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: var(--apple-blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
        }

        .user-role {
            font-size: 12px;
            color: var(--apple-gray);
        }

        .logout-btn {
            padding: 8px 16px;
            border: 1px solid var(--apple-border);
            border-radius: 8px;
            background: transparent;
            color: var(--apple-gray);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: #ffebe6;
            color: #bf2b2b;
            border-color: #ffebe6;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--apple-text);
            margin: 0 0 8px 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--apple-gray);
            margin: 0;
        }

        /* Apple Cards */
        .card {
            background: var(--apple-card);
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px var(--apple-shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            padding: 16px 20px;
            font-weight: 600;
            font-size: 15px;
        }

        .card-body {
            padding: 20px;
        }

        /* Apple Buttons */
        .btn-apple {
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-apple-primary {
            background: var(--apple-blue);
            color: white;
        }

        .btn-apple-primary:hover {
            background: var(--apple-blue-hover);
            transform: translateY(-1px);
        }

        .btn-apple-secondary {
            background: var(--apple-bg);
            color: var(--apple-text);
            border: 1px solid var(--apple-border);
        }

        .btn-apple-secondary:hover {
            background: #e8e8ed;
        }

        .btn-apple-danger {
            background: #ffebe6;
            color: #bf2b2b;
        }

        .btn-apple-danger:hover {
            background: #ffd5cc;
        }

        /* Apple Tables */
        .table {
            margin: 0;
            font-size: 14px;
        }

        .table thead th {
            background: var(--apple-bg);
            color: var(--apple-gray);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--apple-bg);
        }

        /* Apple Form Controls */
        .form-control, .form-select {
            border: 1px solid var(--apple-border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            background: var(--apple-bg);
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--apple-blue);
            box-shadow: 0 0 0 3px rgba(54, 125, 132, 0.15);
            background: white;
            outline: none;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--apple-text);
            margin-bottom: 6px;
        }

        /* Apple Badges */
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #d1e7dd;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #ffebe6;
            color: #bf2b2b;
        }

        .badge-info {
            background: #d1e7dd;
            color: #367d84;
        }

        /* Apple Alerts */
        .alert {
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 14px;
            border: none;
        }

        /* Pagination */
        .pagination {
            gap: 6px;
        }

        .page-link {
            border: none;
            border-radius: 8px;
            color: var(--apple-gray);
            padding: 8px 14px;
            font-size: 14px;
        }

        .page-link:hover {
            background: var(--apple-bg);
            color: var(--apple-blue);
        }

        .page-item.active .page-link {
            background: var(--apple-blue);
            color: white;
        }

        /* Stats Cards */
        .stats-card {
            background: var(--apple-card);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 12px var(--apple-shadow);
        }

        .stats-card .stats-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .stats-card .stats-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--apple-text);
            margin: 0;
        }

        .stats-card .stats-label {
            font-size: 13px;
            color: var(--apple-gray);
            margin: 0;
        }

        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .action-btn-view {
            background: #d1e7dd;
            color: #367d84;
        }

        .action-btn-edit {
            background: #fff3cd;
            color: #856404;
        }

        .action-btn-delete {
            background: #ffebe6;
            color: #bf2b2b;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        /* Search Box */
        .search-box {
            position: relative;
            max-width: 300px;
        }

        .search-box input {
            padding-left: 38px;
            border-radius: 10px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--apple-gray);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
