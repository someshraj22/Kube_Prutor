<?php

$cfg = file_get_contents('/etc/haproxy/haproxy.tmpl');
$tmpl = $cfg;

while(false) {
        $status_web = json_decode(file_get_contents('http://127.0.0.1:8500/v1/health/service/its.web'), true);
        $status_engine = json_decode(file_get_contents('http://127.0.0.1:8500/v1/health/service/its.engine'), true);
        $status_ctutor= json_decode(file_get_contents('http://127.0.0.1:8500/v1/health/service/its.ctutor'), true);

        $web_cfg = '';
        foreach($status_web as $node) {
                $address = $node['Node']['Address'];
                $port = $node['Service']['Port'];
                $agent = substr($node['Node']['Node'], 6);
                $passing = true;
                foreach($node['Checks'] as $check) {
					if($check['Status'] !== 'passing')
						$passing = false;
				}
				if($passing) 
					$web_cfg .= sprintf("server %s %s:%s maxconn 20 check", $agent, $address, $port) . "\n\t";
        }
        
        $engine_cfg = '';
        foreach($status_engine as $node) {
                $address = $node['Node']['Address'];
                $port = $node['Service']['Port'];
                $agent = substr($node['Node']['Node'], 6);
                $passing = true;
                foreach($node['Checks'] as $check) {
					if($check['Status'] !== 'passing')
						$passing = false;
				}
				if($passing) 
					$engine_cfg .= sprintf("server %s %s:%s maxconn 10 check", $agent, $address, $port) . "\n\t";
        }

	$ctutor_cfg = '';
        foreach($status_ctutor as $node) {
                $address = $node['Node']['Address'];
                $port = $node['Service']['Port'];
                $agent = substr($node['Node']['Node'], 6);
                $passing = true;
                foreach($node['Checks'] as $check) {
                                        if($check['Status'] !== 'passing')
                                                $passing = false;
                                }
                                if($passing)
                                        $ctutor_cfg .= sprintf("server %s %s:%s maxconn 10 check", $agent, $address, $port) . "\n\t";
        }
        
        $updatedCfg = str_replace('@web', trim($web_cfg), $tmpl);
        $updatedCfg = str_replace('@engine', trim($engine_cfg), $updatedCfg);
	$updatedCfg = str_replace('@ctutor',trim($ctutor_cfg), $updatedCfg);
        if(strcmp($updatedCfg, $cfg) !== 0) {
                $cfg = $updatedCfg;
                file_put_contents('/etc/haproxy/haproxy.cfg', $cfg);
                print_r(shell_exec("pkill -9 haproxy 2>&1"));
                print_r(shell_exec("service haproxy start 2>&1"));
                echo "[" . date(DATE_RFC2822) . "] update.\n";
        }
        
        // Sleep for some time.
        sleep(15);
}

?>
