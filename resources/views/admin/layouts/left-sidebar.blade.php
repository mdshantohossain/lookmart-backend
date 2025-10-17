<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboards</span>
                    </a>
                </li>

                {{--product-page module--}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-tables">Products Module</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ url('/categories') }}" key="t-basic-tables">Category Manage</a></li>
                        <li><a href="{{ url('/sub-categories') }}" key="t-data-tables">Sub Category Manage</a></li>
                        <li><a href="{{ route('products.index') }}" key="t-responsive-table">Product Manage</a></li>
                        <li><a href="{{ route('product-policies.index') }}" key="t-responsive-table">Product Policy</a></li>
                    </ul>
                </li>

                {{--order module--}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">Order Module</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('order.index') }}" key="t-kanban-board">All Order</a></li>
                        <li><a href="tasks-list.html" key="t-task-list">Order manage</a></li>
                        <li><a href="tasks-create.html" key="t-create-task">Create Task</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-contacts">Contacts</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="contacts-grid.html" key="t-user-grid">Users Grid</a></li>
                        <li><a href="contacts-list.html" key="t-user-list">Users List</a></li>
                        <li><a href="contacts-profile.html" key="t-profile">Profile</a></li>
                    </ul>
                </li>

                {{--  mail module  --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-envelope"></i>
                        <span key="t-email">Email</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="email-inbox.html" key="t-inbox">Single Mail Send</a></li>
                        <li><a href="email-read.html" key="t-read-email">Multiple Mail Send</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-authentication">User Module</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-tone"></i>
                        <span key="t-ui-elements">ACS</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ui-alerts.html" key="t-alerts">Role Manage</a></li>
                        <li><a href="ui-buttons.html" key="t-buttons">Permission Manage</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-add-to-queue"></i>
                        <span key="t-ui-elements">Application Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('app.manage') }}" key="t-alerts">App Manage</a></li>
                        <li><a href="{{ route('app.credential') }}" key="t-alerts">Credential Manage</a></li>
                        <li><a href="{{ route('shipping.index') }}" key="t-buttons">Shipping Manage</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('app-cache.clear') }}" class=" waves-effect">
                        <i class="fa fa-rocket"></i>
                        <span key="t-ui-elements">App Cache clear</span>
                    </a>

                </li>



            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
