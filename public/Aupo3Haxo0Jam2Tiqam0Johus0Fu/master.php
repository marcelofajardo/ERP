<?php
$cmd = '/bin/sh ' . __dir__ . '/../../deployment_scripts/';
echo exec($cmd . 'erp/deploy_branch.sh master');