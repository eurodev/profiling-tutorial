<?php

define('XHPROF_PATH', '/usr/share/php/');
define('XHPROF_URL', 'http://xhprof.betabeers.org');
define('XHPROF_NAMESPACE', 'Betabeers php session test 5');

function xhprof_init() {
  if (xhprof_extension_is_enabled()) {
    include_once XHPROF_PATH . '/xhprof_lib/utils/xhprof_lib.php';
    include_once XHPROF_PATH . '/xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
  }
}

function xhprof_extension_is_enabled() {
  return extension_loaded('xhprof');
}

function xhprof_execute() {
  $xhprof_data = xhprof_disable();
  $xhprof_runs = new XHProfRuns_Default();
  return $xhprof_runs->save_run($xhprof_data, XHPROF_NAMESPACE);
}

function xhprof_link($run_id) {
  $url  = XHPROF_URL . "/index.php?run=$run_id&source=". XHPROF_NAMESPACE;
  return "<a href=\"$url\">xhprof output</a>";
}

function main() {
  xhprof_init();

  test5();

  $run_id = xhprof_execute();
  $link   = xhprof_link($run_id);

  $output  = head();
  $output .= body($link);
  return $output;
}

function head() {
  $output = "<html><head><title>" . XHPROF_NAMESPACE . "</title></head>";
  return $output;
}

function body($content = NULL) {
  $footer = "<footer></footer>";
  $output = "<body><section>$content</section>$footer</body></html>";
  return $output;
}

// Unsafe external request
function test5() {
  // You should read or run 'scripts/iptables.sh' to disable the access to this url
  $output = file("http://127.0.0.3/index.html");
}

$output = main();
print $output;
