<style>
    .simplebar-content .dropdown-btn {
        padding: 12px 26px;
        text-decoration: none;
        margin: -1px;
        color: #fff;
        display: block;
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
        outline: none;
    }

    .dropdown-container {
        display: none;
        padding: 0;
        color: #fff;

    }

    .dropdown-container li a {
        display: block;
        padding: 12px 30px;
        width: inherit;
        margin: 2px 0px;
    }

    ul.nav-link.dropdown-container li {
        padding-left: 15px;
    }

    ul.nav-link.dropdown-container li a {
        text-transform: none;
    }
</style>
<div class="nav nav-pills nav-sidebar" id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true" role="menu">
    <div class="brand-logo">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
            <h5 class="logo-text">Changa App Admin</h5>
        </a>
    </div>
    <ul class="sidebar-menu do-nicescrol ">
        <li>
            <a href="{{ route('dashboard') }}"
                class=" nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                <i class="zmdi zmdi-view-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('users') }}" class=" nav-link {{ Request::segment(1) == 'users' ? 'active' : '' }}">
                <i class="zmdi zmdi-account-circle"></i> <span>Customers</span>
            </a>
        </li>

        <li>
            <a type="button"
                class="nav-link dropdown-btn {{ Request::segment(1) == 'mediator_category' || Request::segment(1) == 'mediators' ? 'active1' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Mediator <i
                        class="fa fa-chevron-down fa-color icon-rotates" aria-hidden="true"></i></span></a>
            <ul class="nav-link dropdown-container"
                style="padding:0; {{ Request::segment(1) == 'mediator_category' || Request::segment(1) == 'mediators' ? 'display : block' : '' }}">
                <li>
                    <a href="{{ route('mediator_category') }}"
                        class="nav-link {{ Request::segment(1) == 'mediator_category' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Mediator Category</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mediators') }}"
                        class=" nav-link {{ Request::segment(1) == 'mediators' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Mediators</span>
                    </a>
                </li>
            </ul>
        </li>



        <li>
            <a type="button"
                class="nav-link dropdown-btn {{ Request::segment(1) == 'mediate_tags' || Request::segment(1) == 'mediates' ? 'active1' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Mediates <i
                        class="fa fa-chevron-down fa-color icon-rotates" aria-hidden="true"></i></span></a>
            <ul class="nav-link dropdown-container"
                style="padding:0; {{ Request::segment(1) == 'mediate_tags' || Request::segment(1) == 'mediates' ? 'display : block' : '' }}">
                <li>
                    <a href="{{ route('mediate_tags') }}"
                        class="nav-link {{ Request::segment(1) == 'mediate_tags' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Tags</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mediates') }}"
                        class=" nav-link {{ Request::segment(1) == 'mediates' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Posts</span>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a type="button"
                class="nav-link dropdown-btn {{ Request::segment(1) == 'mediate_tags' || Request::segment(1) == 'learns' ? 'active1' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Learn <i class="fa fa-chevron-down fa-color icon-rotates"
                        aria-hidden="true"></i></span></a>
            <ul class="nav-link dropdown-container"
                style="padding:0; {{ Request::segment(1) == 'learn_tags' || Request::segment(1) == 'learns' ? 'display : block' : '' }}">
                <li>
                    <a href="{{ route('learn_tags') }}"
                        class="nav-link {{ Request::segment(1) == 'learn_tags' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Tags</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('learns') }}"
                        class=" nav-link {{ Request::segment(1) == 'learns' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Posts</span>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a type="button"
                class="nav-link dropdown-btn {{ Request::segment(1) == 'listen_tags' || Request::segment(1) == 'listens' ? 'active1' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Listen <i class="fa fa-chevron-down fa-color icon-rotates"
                        aria-hidden="true"></i></span></a>
            <ul class="nav-link dropdown-container"
                style="padding:0; {{ Request::segment(1) == 'listen_tags' || Request::segment(1) == 'listens' ? 'display : block' : '' }}">
                <li>
                    <a href="{{ route('listen_tags') }}"
                        class="nav-link {{ Request::segment(1) == 'listen_tags' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Tags</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('listens') }}"
                        class=" nav-link {{ Request::segment(1) == 'listens' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Posts</span>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a type="button"
                class="nav-link dropdown-btn {{ Request::segment(1) == 'therapy_tags' || Request::segment(1) == 'therapy' ? 'active1' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Therapy <i
                        class="fa fa-chevron-down fa-color icon-rotates" aria-hidden="true"></i></span></a>
            <ul class="nav-link dropdown-container"
                style="padding:0; {{ Request::segment(1) == 'therapy_tags' || Request::segment(1) == 'therapy' ? 'display : block' : '' }}">
                <li>
                    <a href="{{ route('therapy_tags') }}"
                        class="nav-link {{ Request::segment(1) == 'therapy_tags' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Tags</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('therapy') }}"
                        class=" nav-link {{ Request::segment(1) == 'therapy' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Posts</span>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{ route('group') }}"
                class=" nav-link {{ Request::segment(1) == 'group' ? 'active' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Group</span>
            </a>
        </li>

        

        <li>
            <a type="button"
                class="nav-link dropdown-btn {{ Request::segment(1) == 'guide_category' || Request::segment(1) == 'guide' ? 'active1' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Guide <i class="fa fa-chevron-down fa-color icon-rotates"
                        aria-hidden="true"></i></span></a>
            <ul class="nav-link dropdown-container"
                style="padding:0; {{ Request::segment(1) == 'guide_category' || Request::segment(1) == 'guide' ? 'display : block' : '' }}">
                <li>
                    <a href="{{ route('guide_category') }}"
                        class="nav-link {{ Request::segment(1) == 'guide_category' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Guide Category</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guide') }}"
                        class=" nav-link {{ Request::segment(1) == 'guide' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Guide</span>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a type="button"
                class="nav-link dropdown-btn {{ Request::segment(1) == 'fellings' || Request::segment(1) == 'intentions' ? 'active1' : '' }}">
                <i class="zmdi zmdi-account-circle"></i><span>Begin Trip <i
                        class="fa fa-chevron-down fa-color icon-rotates" aria-hidden="true"></i></span></a>
            <ul class="nav-link dropdown-container"
                style="padding:0; {{Request::segment(1) == 'audio' || Request::segment(1) == 'audio_tags' || Request::segment(1) == 'visual' || Request::segment(1) == 'fellings' || Request::segment(1) == 'trip_journal' || Request::segment(1) == 'intentions' ? 'display : block' : '' }}">
                <li>
                    <a href="{{ route('fellings') }}"
                        class="nav-link {{ Request::segment(1) == 'fellings' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Fellings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('intentions') }}"
                        class=" nav-link {{ Request::segment(1) == 'intentions' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Intentions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('visual') }}"
                        class=" nav-link {{ Request::segment(1) == 'visual' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Visual</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('trip_journal') }}"
                        class=" nav-link {{ Request::segment(1) == 'trip_journal' ? 'active' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Trip Journal</span>
                    </a>
                </li>
                <li>
                    <a type="button"
                        class="nav-link dropdown-btn {{ Request::segment(1) == 'audio_tags' || Request::segment(1) == 'intentions' ? 'active1' : '' }}">
                        <i class="zmdi zmdi-account-circle"></i><span>Audio <i
                                class="fa fa-chevron-down fa-color icon-rotates" aria-hidden="true"></i></span></a>
                    <ul class="nav-link dropdown-container"
                        style="padding:0; {{ Request::segment(1) == 'audio_tags' || Request::segment(1) == 'intentions' ? 'display : block' : '' }}">
                        <li>
                            <a href="{{ route('audio_tags') }}"
                                class="nav-link {{ Request::segment(1) == 'audio_tags' ? 'active' : '' }}">
                                <i class="zmdi zmdi-account-circle"></i><span>Tags</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('audio') }}"
                                class=" nav-link {{ Request::segment(1) == 'audio' ? 'active' : '' }}">
                                <i class="zmdi zmdi-account-circle"></i><span>Audio</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>

<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>
<!--End sidebar-wrapper-->
