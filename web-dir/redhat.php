<?php
Header('Content-type: text/plain');
echo "#!ipxe\n";
$IP =  $_SERVER['SERVER_ADDR'];
$S  = 'http://' . $IP . ':' . $_SERVER['SERVER_PORT'];
$D  = ucfirst(basename(substr(__FILE__,0,-4)));
echo "set serverpath $S\n";
echo "set serverip $IP\n";
echo "set distrotype $D\n";
?>
# Ensure we have menu-default set to something
isset ${menu-default} || set menu-default back

set menu-timeout 5000

:startmenu
menu Install selection for ${distrotype} based distros

item --gap --                ------------- Return to main menu ------------
item --key b  back           Back to main menu
item --gap --                ----------------------------------------------
item --key h  hypervisor     Boot Redhat HiperVisor live image
item          rhel6.2        Install Redhat Enterprise Linux 6.2
item          rhel5.6        Install Redhat Enterprise Linux 5.6
item          rhel5.5        Install Redhat Enterprise Linux 5.5
item          centos5.4      Install CentOS 5.4
item --gap --                -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${S}/boot/boot.php

:genericredhat
kernel  ${base-url}/images/pxeboot/vmlinuz ks=${base-url}/ks/generic_server.ks ksdevice=eth0 repo=${base-url} method=${base-url}
initrd  ${base-url}/images/pxeboot/initrd.img
boot

:hypervisor
:rhel6.2
set base-url ${serverpath}/RHEL-6.2-x64
goto genericredhat

:rhel5.6
set base-url ${serverpath}/RHEL-5.6-x64
goto genericredhat

:rhel5.5
set base-url ${serverpath}/RHEL-5.5-x64
goto genericredhat

:centos5.4
set base-url ${serverpath}/CentOS-5.4-x64
goto genericredhat
