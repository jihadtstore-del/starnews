<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row gx-0">
    <aside class="col-12 col-lg-3 col-xl-2 border-end bg-white sidebar p-3">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">Equipment</h6>
        </div>
        <input type="text" class="form-control mb-3" id="equipment-search" placeholder="Search equipment...">
        <div id="equipment-list" class="list-group small"></div>
    </aside>
    <main class="col-12 col-lg-9 col-xl-10 p-4">
        <div id="equipment-content">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Welcome</h5>
                    <p class="text-muted">Select an equipment item to view stock and usage details.</p>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
