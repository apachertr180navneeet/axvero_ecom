@extends('frontend.layouts.app')
@section('content')
<style>
    .aiz-user-sidenav .aiz-side-nav-link.active,
    .aiz-user-sidenav .aiz-side-nav-link:hover {
        background-color: #e5e7eb !important;
    }
    .user-sidebar-toggle {
        display: none;
    }
    @media (max-width: 1199px) {
        .user-sidebar-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .aiz-user-sidenav-wrap {
            max-width: 100% !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            margin-bottom: 1rem;
            display: none !important;
            height: auto !important;
        }
        .aiz-user-sidenav-wrap.show {
            display: block !important;
        }
        .aiz-user-sidenav-wrap.show .aiz-user-sidenav {
            position: static !important;
            width: 100% !important;
            height: auto !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .aiz-user-sidenav-wrap.show .aiz-side-nav-link {
            display: block !important;
            text-align: center !important;
            width: 100% !important;
            padding: 10px 20px !important;
        }
        .aiz-user-sidenav-wrap.show .aiz-side-nav-link > svg,
        .aiz-user-sidenav-wrap.show .aiz-side-nav-link > span:not(.badge) {
            display: inline-block !important;
            vertical-align: middle !important;
        }
        .aiz-user-sidenav-wrap.show .aiz-side-nav-link > svg {
            display: none !important;
        }
        .aiz-user-sidenav-wrap.show .sidemnenu {
            text-align: center;
        }
        .aiz-user-sidenav-wrap.show .sidemnenu .modern-btn {
            justify-content: center;
        }
        .aiz-user-sidenav-wrap.show .sidemnenu .aiz-side-nav-text {
            margin-left: 0 !important;
        }
        .aiz-user-sidenav-wrap.show .aiz-side-nav-list {
            padding-left: 0 !important;
            list-style: none !important;
        }
        .aiz-user-sidenav-wrap.show .aiz-side-nav-item {
            width: 100% !important;
        }
        .aiz-user-sidenav-wrap.show .d-xl-none {
            text-align: right;
        }
        .aiz-user-sidenav-wrap.show .aiz-side-nav-list.level-2 {
            width: 100%;
        }
        .aiz-user-sidenav-wrap.show .sidemnenu {
            width: 100%;
        }
        .aiz-user-panel {
            padding-left: 0 !important;
        }
    }
</style>
<section class="py-5">
    <div class="container">
        <button class="btn btn-light border d-lg-none user-sidebar-toggle mb-3 w-100 text-left" onclick="document.querySelector('.aiz-user-sidenav-wrap').classList.toggle('show')">
            <i class="las la-bars fs-18"></i>
            <span class="fs-14 fw-600">{{ translate('Menu') }}</span>
            <i class="las la-angle-down ml-auto fs-14"></i>
        </button>
        <div class="d-flex flex-column flex-lg-row align-items-lg-start">
			@include('frontend.inc.user_side_nav')
			<div class="aiz-user-panel">
				@yield('panel_content')
            </div>
        </div>
    </div>
</section>
@endsection