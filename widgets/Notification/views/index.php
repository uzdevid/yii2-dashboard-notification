<li class="nav-item dropdown">
    <a class="nav-link nav-icon btn btn-outline-light" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span id="notifications-badge" class="badge bg-primary badge-number" style="<?php echo count($unread_notifications) == 0 ? 'display:none;' : ''; ?>"><?php echo count($unread_notifications); ?></span>
    </a>
    <ul id="notifications-mini-list" class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications"></ul>
</li>