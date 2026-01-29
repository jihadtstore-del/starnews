const baseUrl = '/public';

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function fetchJson(url, options = {}) {
    return fetch(url, options).then((response) => response.json());
}

function loadEquipmentList(query = '') {
    fetchJson(`${baseUrl}/api/equipment_list.php?q=${encodeURIComponent(query)}`)
        .then((data) => {
            const list = document.getElementById('equipment-list');
            if (!list) return;
            list.innerHTML = '';
            data.items.forEach((item) => {
                const link = document.createElement('a');
                link.href = '#';
                link.className = 'list-group-item list-group-item-action';
                link.textContent = item.name;
                link.dataset.id = item.id;
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    document.querySelectorAll('#equipment-list .list-group-item').forEach((el) => el.classList.remove('active'));
                    link.classList.add('active');
                    loadEquipment(item.id);
                });
                list.appendChild(link);
            });
        });
}

function loadEquipment(equipmentId, activeTab = 'tab-stock-in') {
    const container = document.getElementById('equipment-content');
    if (!container) return;
    fetch(`${baseUrl}/equipment.php?id=${equipmentId}&partial=1`)
        .then((response) => response.text())
        .then((html) => {
            container.innerHTML = html;
            initEquipmentPage(activeTab);
        });
}

function initEquipmentPage(activeTab) {
    if (typeof window.EQUIPMENT_ID === 'undefined') return;

    const tabTrigger = document.querySelector(`[data-bs-target="#${activeTab}"]`);
    if (tabTrigger) {
        new bootstrap.Tab(tabTrigger).show();
    }

    const stockInForm = document.getElementById('stock-in-form');
    if (stockInForm) {
        stockInForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(stockInForm);
            fetch(`${baseUrl}/api/stock_in_add.php`, {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then(() => {
                    reloadEquipment('tab-stock-in');
                });
        });
    }

    const issueForm = document.getElementById('issue-form');
    if (issueForm) {
        issueForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(issueForm);
            fetch(`${baseUrl}/api/issue_add.php`, {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then(() => {
                    reloadEquipment('tab-stock-out');
                });
        });
    }

    const issueSearchBtn = document.getElementById('issue-search-btn');
    if (issueSearchBtn) {
        issueSearchBtn.addEventListener('click', () => loadIssueList(1));
    }

    const returnForm = document.getElementById('return-form');
    if (returnForm) {
        returnForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(returnForm);
            fetch(`${baseUrl}/api/issue_return.php`, {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('returnModal'));
                    if (modal) modal.hide();
                    reloadEquipment('tab-stock-out');
                });
        });
    }

    loadStockInList(1);
    loadIssueList(1);
    loadUsageList();
}

function reloadEquipment(activeTab) {
    const equipmentId = window.EQUIPMENT_ID;
    loadEquipment(equipmentId, activeTab);
}

function buildPagination(containerId, pagination, onPageClick) {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = '';
    const totalPages = Math.ceil(pagination.total / pagination.per_page);
    for (let i = 1; i <= totalPages; i += 1) {
        const li = document.createElement('li');
        li.className = `page-item ${i === pagination.page ? 'active' : ''}`;
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = i;
        a.addEventListener('click', (event) => {
            event.preventDefault();
            onPageClick(i);
        });
        li.appendChild(a);
        container.appendChild(li);
    }
}

function loadStockInList(page) {
    fetchJson(`${baseUrl}/api/stock_in_list.php?equipment_id=${window.EQUIPMENT_ID}&page=${page}`)
        .then((data) => {
            const tbody = document.querySelector('#stock-in-table tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            data.records.forEach((record, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${record.equipment_name}</td>
                    <td>${record.brand}</td>
                    <td>${record.model}</td>
                    <td>${record.qty}</td>
                    <td>${record.serial_no || '-'}</td>
                    <td>${record.stock_in_date}</td>
                    <td>${record.remarks || '-'}</td>
                `;
                tbody.appendChild(row);
            });

            const summary = document.getElementById('stock-in-summary');
            if (summary) {
                summary.innerHTML = `
                    <div class="col-md-4"><div class="summary-card"><span>Total Records</span><strong>${data.summary.total_records}</strong></div></div>
                    <div class="col-md-4"><div class="summary-card"><span>Total Qty In</span><strong>${data.summary.total_qty}</strong></div></div>
                    <div class="col-md-4"><div class="summary-card"><span>Last Stock In Date</span><strong>${data.summary.last_date || '-'}</strong></div></div>
                `;
            }

            buildPagination('stock-in-pagination', data.pagination, loadStockInList);
        });
}

function loadIssueList(page) {
    const userQ = document.getElementById('issue-search-user')?.value || '';
    const equipmentQ = document.getElementById('issue-search-equipment')?.value || '';
    fetchJson(`${baseUrl}/api/issue_list.php?equipment_id=${window.EQUIPMENT_ID}&page=${page}&user_q=${encodeURIComponent(userQ)}&equipment_q=${encodeURIComponent(equipmentQ)}`)
        .then((data) => {
            const tbody = document.querySelector('#issue-table tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            data.records.forEach((record, index) => {
                const returnCell = record.return_date
                    ? record.return_date
                    : '<span class="badge bg-warning text-dark badge-status">Not returned</span>';
                const actionCell = record.status === 'ISSUED'
                    ? `<button class="btn btn-sm btn-outline-primary" data-return-id="${record.id}">Return</button>`
                    : '-';
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${record.user_name}</td>
                    <td>${record.equipment_name}</td>
                    <td>${record.qty}</td>
                    <td>${record.unit_no}</td>
                    <td>${record.serial_no || '-'}</td>
                    <td>${record.phone}</td>
                    <td>${record.location_name || '-'}</td>
                    <td>${record.issue_date}</td>
                    <td>${returnCell}</td>
                    <td>${actionCell}</td>
                `;
                tbody.appendChild(row);
            });

            tbody.querySelectorAll('button[data-return-id]').forEach((button) => {
                button.addEventListener('click', () => {
                    const issueId = button.getAttribute('data-return-id');
                    document.getElementById('return-issue-id').value = issueId;
                    const modal = new bootstrap.Modal(document.getElementById('returnModal'));
                    modal.show();
                });
            });

            buildPagination('issue-pagination', data.pagination, loadIssueList);
        });
}

function loadUsageList() {
    fetchJson(`${baseUrl}/api/issue_list.php?equipment_id=${window.EQUIPMENT_ID}&status=ISSUED&per_page=100`)
        .then((data) => {
            const tbody = document.querySelector('#usage-table tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            data.records.forEach((record, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${record.user_name}</td>
                    <td>${record.location_name || '-'}</td>
                    <td>${record.qty}</td>
                    <td>${record.unit_no}</td>
                    <td>${record.issue_date}</td>
                    <td>${record.remarks || '-'}</td>
                `;
                tbody.appendChild(row);
            });
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('equipment-search');
    if (searchInput) {
        searchInput.addEventListener('input', (event) => {
            loadEquipmentList(event.target.value);
        });
        loadEquipmentList();
    }

    if (typeof window.EQUIPMENT_ID !== 'undefined') {
        initEquipmentPage('tab-stock-in');
    }
});
