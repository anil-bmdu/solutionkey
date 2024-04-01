<style>
    .has-arrow + ul {
        display: none;
    }

    .show {
        display: block;
    }
</style>

<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">
    <nav class="sidebar">
        <div class="d-flex justify-content-between" style="width: 200px;margin:10px;">
            <div class="space">
                <a href="#"><img src="{{ asset('img/logo.jpg') }}" class="img-fluid"></a>
            </div>
            <div class="sidebar_close_icon d-lg-none">
                <i class="ti-close"></i>
            </div>
        </div>
        <ul id="sidebar_menu">
            <li class="mm-active">
                <a href="{{route('dashboard')}}" style="text-decoration:none;">
                    <div class="icon">
                        <i class="fa fa-home" style="color:#033496;"></i>
                    </div>
                    <span style="font-size:14px;color:#033496;font-weight:700;">Dashboard</span>
                </a>
            </li>
            @if(auth()->check() && auth()->user()->hasPermission('usermanagement'))
            <li class>
                <a class="has-arrow" href="#" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-address-card" style=" color:#033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700;">User Management</span>
                </a>
                <ul>
                    <li><a href="{{ route('add-users') }} " style="color: #0d9603;text-decoration:none;font-weight:700;"><span >Add Accounts</span></a></li>
                    <li><a href="{{ route('all-users') }} " style="color: #0d9603;text-decoration:none;font-weight:700;"><span>All Accounts</span></a></li>
                </ul>
            </li>
                
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('servicemanagement'))
           
            {{--  <li>
                <a href="{{ route('service') }}" style="text-decoration:none;">
                    <i class="fa fa-exclamation" style="color: #033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700;">Service Management</span>
                </a>
            </li>  --}}

            <li class>
                <a class="has-arrow" href="#" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-folder-open" style="color: #033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700">Service Management</span>
                </a>
                <ul>
                    <li><a href="{{route('service')}}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>All Services</span></a></li>
                    <li><a href="{{route('service-create')}}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Add Services</span></a></li>
                </ul>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('professionalmanagement'))
            <li class>
                <a class="has-arrow" href="#" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-folder-open" style="color: #033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700">Professional Management</span>
                </a>
                <ul>
                    <li><a href="{{route('vendor-show')}}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Professionals</span></a></li>
                </ul>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('customermanagement'))
            <li class>
                <a class="has-arrow" href="#" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-users" style="color: #033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700">Customer Management</span>
                </a>
                <ul>
                    <li><a href="{{ route('customer-show') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Customer Details</span></a></li>
                    <li><a href="{{ route('customer-document') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Customer Documents</span></a></li>
                    <li><a href="{{ route('customer-family') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Customer Family Details</span></a></li>
                </ul>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('booking'))
            <li class>
                <a class="has-arrow" href="#" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-user-circle" style="color: #033496;"></i>
                    <span style="font-size: 14px;color: #033496;font-weight:700">Booking & Scheduling:</span>
                </a>
                <ul>
                    <li><a href="{{ route('online-booking') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Online Bookings</span></a>
                    <li><a href="{{ route('physical-booking') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Physical Bookings</span></a>
                    </li>
                </ul>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('payment'))
            <li class="mm-active">
                <a class="has-arrow" href="#" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-google-wallet" style="color: #033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700;">Payment & Invoicing</span>
                </a>
                <ul>
                    <li><a href="#" style="color: #0d9603;text-decoration:none;font-weight:700;"><span> Payments</span></a></li>
                </ul>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('blogmanagement'))
            <li class="mm-active">
                <a class="has-arrow" href="#" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-google-wallet" style="color: #033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700;">Blog Management</span>
                </a>
                <ul>
                    <li><a href="{{ route('blog-pending') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Blog Pending</span></a></li>
                    <li><a href="{{ route('blog-approved') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Blog Approved</span></a></li>
                </ul>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('notifications'))
            <li class="mm-active">
                <a class="has-arrow" href="{{ route('notification') }}" aria-expanded="false" style="text-decoration:none;">
                    <i class="fa fa-google-wallet" style="color: #033496;"></i>
                    <span style="font-size: 14px; color: #033496;font-weight:700;">Notification</span>
                </a>
                <ul>
                    <li><a href="{{ route('notification-create') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>Add Notification</span></a></li>
                    <li><a href="{{ route('notification') }}" style="color: #0d9603;text-decoration:none;font-weight:700;"><span>All Notification</span></a></li>
                </ul>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('feedback'))
            <li>
                <a href="{{ route('feedback') }}" style="text-decoration:none;">
                    <i class="fa fa-exclamation" style="color: #033496;"></i>
                    <span style="font-size: 14px;color: #033496;font-weight:700;">Feedbacks</span>
                </a>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('review'))
            <li>
                <a href="{{ route('reviews-rating') }}" style="text-decoration: none;">
                    <i class="fa fa-exclamation" style="color: #033496;"></i>
                    <span style="font-size: 14px;color: #033496;font-weight:700;">Reviews & Ratings</span>
                </a>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('referral'))
            <li>
                <a href="{{ route('referral-earning') }}" style="text-decoration: none;">
                    <i class="fa fa-exclamation" style="color: #033496;"></i>
                    <span style="font-size: 14px;color: #033496;font-weight:700;">Referral & Earning</span>
                </a>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('complaint'))
            <li>
                <a href="{{ route('complaint') }}" style="text-decoration: none;">
                    <i class="fa fa-exclamation" style="color: #033496;"></i>
                    <span style="font-size: 14px;color: #033496;font-weight:700;">Complaints</span>
                </a>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('reward'))
            <li>
                <a href="{{ route('reward-commission') }}" style="text-decoration: none;">
                    <i class="fa fa-exclamation" style="color: #033496;"></i>
                    <span style="font-size: 14px;color: #033496;font-weight:700;">Rewards & Commissions</span>
                </a>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->hasPermission('analytic'))
            <li>
                <a href="#" style="text-decoration: none;">
                    <i class="fa fa-arrows" style="color: #033496;"></i>
                    <span style="font-size: 14px;color: #033496;font-weight:700;">Analytics & Reporting</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
</ul>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var hasArrowLinks = document.querySelectorAll('.has-arrow');

        hasArrowLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior

                // Toggle the visibility of the submenu
                var submenu = link.nextElementSibling;
                submenu.classList.toggle('show');
            });
        });
    });
</script>





