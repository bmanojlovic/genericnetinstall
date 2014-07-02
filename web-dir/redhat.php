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
item          rhel6.4        Install Redhat Enterprise Linux 6.4
item          ol65           Install Oracle (Redhat) Enterprise Linux 6.5
item          rhel6.3        Install Redhat Enterprise Linux 6.3
item          rhel6.2        Install Redhat Enterprise Linux 6.2
item          rhel5.6        Install Redhat Enterprise Linux 5.6
item          rhel5.5        Install Redhat Enterprise Linux 5.5
item          fedora17       Install Fedora 17 64bit
item          fedora18       Install Fedora 18 64bit
item          centos6.5      Install CentOS 6.5
item          centos6.5text  Install CentOS 6.5 text mode
item          centos6.4      Install CentOS 6.4
item          centos6.3      Install CentOS 6.3
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

:genericredhattext
kernel  ${base-url}/images/pxeboot/vmlinuz ks=${base-url}/ks/generic_server.ks ksdevice=eth0 repo=${base-url} method=${base-url} text
initrd  ${base-url}/images/pxeboot/initrd.img
boot

:genericfedora
kernel  ${base-url}/images/pxeboot/vmlinuz ksdevice=eth0 repo=${base-url} method=${base-url} lowres
initrd  ${base-url}/images/pxeboot/initrd.img
boot

:hypervisor
:rhel6.4
set base-url ${serverpath}/RHEL-6.4-x64
goto genericredhat

:ol65
set base-url ${serverpath}/OL6.5-x86_64
goto genericredhat

:rhel6.3
set base-url ${serverpath}/RHEL-6.3-x64
goto genericredhat

:rhel6.4
set base-url ${serverpath}/RHEL-6.4-x64
goto genericredhat

:rhel6.2
set base-url ${serverpath}/RHEL-6.2-x64
goto genericredhat

:rhel5.6
set base-url ${serverpath}/RHEL-5.6-x64
goto genericredhat

:rhel5.5
set base-url ${serverpath}/RHEL-5.5-x64
goto genericredhat

:centos6.5
set base-url ${serverpath}/CentOS-6.5-x86_64
goto genericredhat

:centos6.5text
set base-url ${serverpath}/CentOS-6.5-x86_64
goto genericredhattext

:centos6.4
set base-url ${serverpath}/CentOS-6.4-x86_64
goto genericredhat

:centos6.3
set base-url ${serverpath}/CentOS-6.3-x86_64
goto genericredhat

:centos5.4
set base-url ${serverpath}/CentOS-5.4-x64
goto genericredhat


:fedora17
set base-url ${serverpath}/Fedora-17-x86_64
goto genericfedora

:fedora18
set base-url ${serverpath}/Fedora-18-x86_64
goto genericfedora
