<?php

// Constants
$GITHUB_IPS = array('204.232.175.64/27', '192.30.252.0/22');

// Source: http://php.net/manual/fr/ref.network.php
function ipCIDRCheck ($IP, $CIDR) {
    list ($net, $mask) = split ('/', $CIDR);
    
    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);
    $ip_ip = ip2long ($IP);
    $ip_ip_net = $ip_ip & $ip_mask;

    return ($ip_ip_net == $ip_net);
}

// Does a given IP match GitHub's IPs?
function matchesGitHubIps($ip) {
    foreach ($GITHUB_IPS as &$gh_ip_range) {
        if (ipCIDRCheck($ip, $gh_ip_range)) {
            return true;
        }
    }
    return false;
}

// Does our client match GitHub's IPs?
function clientMatchesGitHubIp() {
    return matchesGitHubIps($_SERVER['REMOTE_ADDR']);
}

?>
