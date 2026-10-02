<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Point of Sale') ?> | Complete POS</title>

    <style>
        :root {
            --navy: #172554;
            --blue: #2563eb;
            --blue-dark: #1d4ed8;
            --surface: #ffffff;
            --background: #f1f5f9;
            --border: #dbe4ee;
            --text: #1e293b;
            --muted: #64748b;
            --danger: #b91c1c;
            --success: #166534;
            --warning: #92400e;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 16px 5%;
            background: var(--navy);
            color: white;
        }

        .navbar .brand {
            margin-right: auto;
            color: white;
            font-size: 21px;
            font-weight: 700;
            text-decoration: none;
        }

        .navbar a,
        .navbar button {
            border: 0;
            background: transparent;
            color: #dbeafe;
            font: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .navbar a:hover,
        .navbar button:hover {
            color: white;
        }

        .navbar form {
            margin: 0;
        }

        .navbar-user {
            color: #fde68a;
            font-size: 14px;
        }

        main {
            width: min(1180px, 92%);
            min-height: 76vh;
            margin: 32px auto;
        }

        .page-heading,
        .panel-heading,
        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .page-heading {
            margin-bottom: 24px;
        }

        h1,
        h2 {
            color: var(--navy);
        }

        h1,
        h2,
        p {
            margin-top: 0;
        }

        .button {
            display: inline-block;
            padding: 11px 17px;
            border: 0;
            border-radius: 7px;
            background: var(--blue);
            color: white;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover {
            background: var(--blue-dark);
        }

        .button-secondary {
            background: #475569;
        }

        .form-card,
        .panel,
        .stat-card,
        .empty-state {
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 5px 18px rgba(15, 23, 42, 0.06);
        }

        .form-card {
            max-width: 720px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 700;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #b8c4d1;
            border-radius: 7px;
            background: white;
            font: inherit;
        }

        small,
        .muted {
            color: var(--muted);
        }

        small {
            display: block;
            margin-top: 6px;
        }

        .required {
            color: var(--danger);
        }

        .alert {
            margin-bottom: 18px;
            padding: 14px 17px;
            border-radius: 8px;
        }

        .alert ul {
            margin-bottom: 0;
        }

        .alert-error {
            background: #fee2e2;
            color: var(--danger);
        }

        .alert-success {
            background: #dcfce7;
            color: var(--success);
        }

        .alert-warning {
            background: #fef3c7;
            color: var(--warning);
        }

        .table-wrapper {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #eaf0f8;
            color: var(--navy);
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat-card span {
            display: block;
            margin-bottom: 9px;
            color: var(--muted);
        }

        .stat-card strong {
            color: var(--navy);
            font-size: 29px;
        }

        .stat-card-wide {
            grid-column: span 2;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .panel-heading h2 {
            margin-bottom: 0;
            font-size: 20px;
        }

        .panel-heading {
            margin-bottom: 17px;
        }

        .stock-badge {
            display: inline-block;
            min-width: 35px;
            padding: 5px 9px;
            border-radius: 999px;
            background: #fee2e2;
            color: var(--danger);
            text-align: center;
            font-weight: 700;
        }

        footer {
            padding: 21px;
            background: var(--navy);
            color: #dbeafe;
            text-align: center;
        }

        @media (max-width: 900px) {
            .navbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .navbar .brand {
                margin-right: 0;
            }

            .stats-grid,
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .stat-card-wide {
                grid-column: span 1;
            }
        }

        @media (max-width: 600px) {
            .page-heading,
            .panel-heading,
            .form-actions {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <?php
        $staffName = session()->get('full_name')
            ?? session()->get('fullName')
            ?? session()->get('username')
            ?? 'Staff';
    ?>

    <nav class="navbar">
        <a class="brand" href="<?= base_url('dashboard') ?>">Complete POS</a>
        <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('products') ?>">Products</a>
        <a href="<?= base_url('customers') ?>">Customers</a>
        <a href="<?= base_url('users') ?>">Staff</a>
        <a href="<?= base_url('sales/new') ?>">Record Sale</a>
        <a href="<?= base_url('sales') ?>">Sales History</a>

        <span class="navbar-user"><?= esc($staffName) ?></span>

        <form action="<?= base_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit">Logout</button>
        </form>
    </nav>

    <main>
