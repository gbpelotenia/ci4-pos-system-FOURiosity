<nav class="navbar">
    <div class="container nav-inner">
        <a class="brand" href="<?= site_url('/') ?>">CI4 POS</a>
        <div class="nav-links">
            <a href="<?= site_url('/') ?>">Dashboard</a>
            <a href="<?= site_url('products') ?>">Products</a>
            <a href="<?= site_url('customers') ?>">Customers</a>
            <a href="<?= site_url('sales') ?>">Sales</a>
            <a href="<?= site_url('users') ?>">Staff</a>
        </div>
        <div class="nav-user">
            <span><?= esc(session()->get('full_name')) ?></span>
            <form method="post" action="<?= site_url('logout') ?>">
                <?= csrf_field() ?>
                <button class="btn btn-small btn-outline" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>
