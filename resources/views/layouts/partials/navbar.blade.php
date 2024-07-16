<header>
    <!-- Header Start -->
   <div class="header-area header-transparrent">
       <div class="headder-top header-sticky justify-between">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-3 col-md-2">
                        <!-- Logo -->
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('frontend-new/img/viscus.png') }}"
                                    style="width: 200px;" alt=""></a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9">
                        <!-- Main-menu -->
                        <div class="main-menu f-right d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li>
                                    <li class="{{ Request::is('job_listing') ? 'active' : '' }}"><a href="{{ url('job-listing') }}">Find a Jobs</a></li>
                                    <li class=""><a target="_blank" href="https://viscusmedia.com">About Viscus</a></li>
                                    {{-- <li><a href="#">Page</a>
                                        <ul class="submenu">
                                            <li><a href="blog.html">Blog</a></li>
                                            <li><a href="single-blog.html">Blog Details</a></li>
                                            <li><a href="elements.html">Elements</a></li>
                                            <li><a href="job_details.html">job Details</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="contact.html">Contact</a></li> --}}
                                </ul>
                            </nav>
                        </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
       </div>
   </div>
    <!-- Header End -->
</header>
