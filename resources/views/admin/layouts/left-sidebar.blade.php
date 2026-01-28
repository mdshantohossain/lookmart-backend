<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>
                {{-- dashboard --}}
                @can('dashboard show')
                   <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                @endcan

                {{--product-page module--}}
                @can('catalog module')
                   <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span key="t-tables">Catalog Module</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @canany(['category create', 'category edit', 'category destroy'])
                                 <li><a href="{{ url('/categories') }}" key="t-basic-tables">Category Manage</a></li>
                            @endcanany

                            @canany(['sub-category create', 'sub-category edit', 'sub-category destroy'])
                                 <li><a href="{{ url('/sub-categories') }}" key="t-data-tables">Sub Category Manage</a></li>
                            @endcanany

                            @canany(['product create', 'product edit', 'product destroy'])
                                <li><a href="{{ route('products.index') }}" key="t-responsive-table">Product Manage</a></li>
                            @endcanany

                            @canany(['review create', 'review edit', 'review destroy'])
                                <li><a href="{{ route('reviews.index') }}" key="t-responsive-table">Product Review</a></li>
                            @endcanany

                            @canany(['product policy create', 'product policy edit', 'product policy destroy'])
                                <li><a href="{{ route('product-policies.index') }}" key="t-responsive-table">Product Policy</a></li>
                            @endcanany
                        </ul>
                    </li>
                @endcan

                {{--order module--}}
                @can('order module')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">Order Module</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('orders.index') }}" key="t-kanban-board">All Order</a></li>
                        <li><a href="tasks-list.html" key="t-task-list">Order manage</a></li>
                        <li><a href="tasks-create.html" key="t-create-task">Create Task</a></li>
                    </ul>
                </li>
                @endcan

                {{--  mail module  --}}
                @can('email module')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-envelope"></i>
                        <span key="t-email">Email</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        @can('single email module')
                            <li><a href="email-inbox.html" key="t-inbox">Single Mail Send</a></li>
                        @endcan

                        @can('multiple email module')
                         <li><a href="email-read.html" key="t-read-email">Multiple Mail Send</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                {{--  access control system--}}
                @can('acs module')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-ui-elements">ACS</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
{{--                        @can('permission module')--}}
{{--                           <li><a href="{{ route('permissions.index') }}" key="t-buttons">Permission Manage</a></li>--}}
{{--                        @endcan--}}
                        @canany(['role create', 'role edit', 'role destroy'])
                           <li><a href="{{ route('roles.index') }}" key="t-alerts">Role Manage</a></li>
                        @endcanany

                        @canany(['user create', 'user edit', 'user destroy'])
                           <li><a href="{{ route('users.index') }}" key="t-buttons">User Manage</a></li>
                        @endcanany

                    </ul>
                </li>
                @endcan

                {{--  app management --}}
                @can('app-management module')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-add-to-queue"></i>
                        <span key="t-ui-elements">Application Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        @can('app-management module')
                             <li><a href="{{ route('app.manage') }}" key="t-alerts">App Manage</a></li>
                        @endcan

                        @can('app-credential module')
                             <li><a href="{{ route('app.credential') }}" key="t-alerts">App credential Manage</a></li>
                       @endcan

                        @can('shipping management module')
                        <li><a href="{{ route('shipping.index') }}" key="t-buttons">Shipping Manage</a></li>
                        @endcan

                    </ul>
                </li>
                @endcan

                {{-- app cache  --}}
                @can('app cache clear')
                    <li>
                        <a href="{{ route('app-cache.clear') }}" class=" waves-effect">
                            <i class="fa fa-rocket"></i>
                            <span key="t-ui-elements">App Cache clear</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
