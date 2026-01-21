<h3 class="mb-4">Admin Dashboard</h3>
<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-bg-light shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Technicians</h6>
                <p class="display-6 mb-0"><?php echo $stats['technicians']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-light shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Equipments</h6>
                <p class="display-6 mb-0"><?php echo $stats['equipments']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-light shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Pending Issues</h6>
                <p class="display-6 mb-0"><?php echo $stats['pending']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-light shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Returned</h6>
                <p class="display-6 mb-0"><?php echo $stats['returned']; ?></p>
            </div>
        </div>
    </div>
</div>
