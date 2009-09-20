<?php

require_once 'inc/html.inc.php';
require_once 'inc/collectd.inc.php';

$host = $_GET['h'];
$splugin = $_GET['p'];

html_start();

printf('<h1><a href="%s">&laquo;</a> %s</h1>'."\n", $CONFIG['weburl'], $host);

$plugins = collectd_plugins($host);

if(!$plugins) {
	echo "Unknown host\n";
	return false;
}

# first the ones defined in overview
foreach($CONFIG['overview'] as $plugin) {
	if (in_array($plugin, $plugins)) {
		printf("<h2>[-] %s</h2>\n", $plugin);
		graphs_from_plugin($host, $plugin);
	}
}

# other plugins
foreach($plugins as $plugin) {
	if (!in_array($plugin, $CONFIG['overview'])) {
		$url = sprintf('<a href="%s/host.php?h=%s&p=%s">%s</a>'."\n", $CONFIG['weburl'], $host, $plugin, $plugin);
		if ($splugin == $plugin) {
			printf("<h2>[-] %s</h2>\n", $url);
			graphs_from_plugin($host, $plugin);
		} else {
			printf("<h2>[+] %s</h2>\n", $url);
		}
	}
}

html_end();

?>
