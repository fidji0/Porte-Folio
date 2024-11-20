<!-- Bouton de toggle pour petits écrans -->
<button class="btn btn-primary d-md-none" id="sidebarToggle" type="button">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
    <div class="position-sticky">
        <!-- Sidebar - Brand -->
        <div class="sidebar-header p-3">
            <h2 class="fs-4 text-center text-white mb-0">Live Planning</h2>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/planning">
                    <i class="fas fa-home"></i>
                    <span><?= $bout['boutiqueName'] ?></span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-uppercase">
                    <span>Menu principal</span>
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/planning">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Planning</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/personnel">
                    <i class="fas fa-users"></i>
                    <span>Personnel</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/demande">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Demande</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/stats">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Statistiques</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-uppercase">
                    <span>Paramètres</span>
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/abonnement">
                    <i class="fas fa-star"></i>
                    <span>Mon abonnement</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/assistance">
                    <i class="fas fa-question-circle"></i>
                    <span>Assistance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/disconnected">
                    <i class="fas fa-question-circle"></i>
                    <span>Se Déconnecter</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
    :root {
        --sidebar-bg: linear-gradient(135deg, #3498db, #2c3e50);
        --sidebar-color: #ffffff;
        --sidebar-hover: rgba(255, 255, 255, 0.1);
        --sidebar-active: rgba(255, 255, 255, 0.2);
    }

    #sidebar {
        min-height: 100vh;
        background: var(--sidebar-bg);
        transition: all 0.3s;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    #sidebar .sidebar-header {
        padding: 20px;
        background: rgba(0, 0, 0, 0.1);
    }

    #sidebar .nav-link {
        padding: 10px 20px;
        font-size: 1rem;
        color: var(--sidebar-color);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        border-radius: 5px;
        margin: 2px 10px;
    }

    #sidebar .nav-link:hover, #sidebar .nav-link.active {
        background-color: var(--sidebar-hover);
        transform: translateX(5px);
    }

    #sidebar .nav-link.active {
        background-color: var(--sidebar-active);
        font-weight: bold;
    }

    #sidebar .nav-link i {
        width: 20px;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .sidebar-heading {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1rem;
        color: rgba(255, 255, 255, 0.4);
    }

    @media (max-width: 767.98px) {
        #sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            height: 100vh;
            z-index: 999;
            width: 250px;
        }

        #sidebar.show {
            left: 0;
        }

        #sidebarToggle {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background-color: #667eea;
            border: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebarToggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    var content = document.querySelector('.content');
    
    function toggleSidebar() {
        sidebar.classList.toggle('show');
        if (window.innerWidth >= 768) {
            if (content) content.style.marginLeft = sidebar.classList.contains('show') ? '250px' : '0';
        }
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        var isClickInside = sidebar.contains(event.target) || sidebarToggle.contains(event.target);
        if (!isClickInside && sidebar.classList.contains('show') && window.innerWidth < 768) {
            toggleSidebar();
        }
    });

    // Adjust on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.add('show');
            if (content) content.style.marginLeft = '250px';
        } else {
            sidebar.classList.remove('show');
            if (content) content.style.marginLeft = '0';
        }
    });
});
</script>