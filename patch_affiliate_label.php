<?php
$files = glob("resources/views/header/header*.blade.php");
$files[] = "resources/views/frontend/inc/nav.blade.php";
$files[] = "resources/views/frontend/inc/footer.blade.php";

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    $parts = explode("route('affiliate.user.index')", $content);
    
    if (count($parts) > 1) {
        $updated = false;
        
        for ($i = 1; $i < count($parts); $i++) {
            $part = $parts[$i];
            
            // Only look in the next 500 characters
            $window = substr($part, 0, 500);
            
            $posDash = strpos($window, "translate('Dashboard')");
            $posAccount = strpos($window, "translate('My Account')");
            
            $pos = false;
            $targetStr = "";
            
            if ($posDash !== false && ($posAccount === false || $posDash < $posAccount)) {
                $pos = $posDash;
                $targetStr = "translate('Dashboard')";
            } elseif ($posAccount !== false) {
                $pos = $posAccount;
                $targetStr = "translate('My Account')";
            }
            
            if ($pos !== false) {
                $parts[$i] = substr_replace($part, "translate('Affiliated Dashboard')", $pos, strlen($targetStr));
                $updated = true;
            }
        }
        
        if ($updated) {
            $content = implode("route('affiliate.user.index')", $parts);
            file_put_contents($file, $content);
            echo "Updated $file\n";
        }
    }
}
