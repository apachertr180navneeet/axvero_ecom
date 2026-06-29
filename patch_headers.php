<?php
$files = glob("resources/views/header/header*.blade.php");
foreach ($files as $file) {
    if (strpos($file, "header2.blade.php") !== false) continue;
    $content = file_get_contents($file);
    
    // Check if already patched
    if (strpos($content, "Auth::user()->user_type == 'affiliate'") !== false) continue;
    
    $search = '<a href="{{ route(\'dashboard\') }}"';
    
    // Find the @else just before this search
    $pos = strpos($content, $search);
    if ($pos !== false) {
        $before = substr($content, 0, $pos);
        $elsePos = strrpos($before, "@else");
        if ($elsePos !== false) {
            $replacement = "@elseif(Auth::user()->user_type == 'affiliate')
                                <li class=\"user-top-nav-element border border-top-0\" data-id=\"1\">
                                    <a href=\"{{ route('affiliate.user.index') }}\"
                                        class=\"text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1\">
                                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 16 16\">
                                            <path id=\"Path_2916\" data-name=\"Path 2916\"
                                                d=\"M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z\"
                                                fill=\"#b5b5c0\" />
                                        </svg>
                                        <span
                                            class=\"user-top-menu-name has-transition ml-3\">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @else";
            
            $content = substr_replace($content, $replacement, $elsePos, 5);
            file_put_contents($file, $content);
            echo "Patched $file\n";
        }
    }
}
