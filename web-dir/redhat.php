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
#item --key h  hypervisor     Boot Redhat HiperVisor live image
item          rhel72         Install Redhat Enterprise Linux 7.2
item          rhel68         Install Redhat Enterprise Linux 6.8
item          ol71           Install Oracle (Redhat) Enterprise Linux 7.1
item          ol68           Install Oracle (Redhat) Enterprise Linux 6.8
item          fedora24       Install Fedora 24
item          centos71       Install CentOS 7.1
item          centos68       Install CentOS 6.8
item          centos68text   Install CentOS 6.8 text mode
item --gap --                -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${S}/boot/boot.php

:genericredhat
kernel  ${base-url}/images/pxeboot/vmlinuz repo=${base-url} method=${base-url}
initrd  ${base-url}/images/pxeboot/initrd.img
boot


:genericredhattext
kernel  ${base-url}/images/pxeboot/vmlinuz repo=${base-url} method=${base-url} text
initrd  ${base-url}/images/pxeboot/initrd.img
boot

:genericredhat6
kernel  ${base-url}/images/pxeboot/vmlinuz ks=${base-url}/ks/generic_server.ks ksdevice=eth0 repo=${base-url} method=${base-url}
initrd  ${base-url}/images/pxeboot/initrd.img
boot

:genericredhattext6
kernel  ${base-url}/images/pxeboot/vmlinuz ks=${base-url}/ks/generic_server.ks ksdevice=eth0 repo=${base-url} method=${base-url} text
initrd  ${base-url}/images/pxeboot/initrd.img
boot

:genericfedora
kernel  ${base-url}/images/pxeboot/vmlinuz ksdevice=eth0 repo=${base-url} method=${base-url} lowres
initrd  ${base-url}/images/pxeboot/initrd.img
boot

:hypervisor
:rhel68
set base-url ${serverpath}/RHEL-6.8-x64
goto genericredhat6

:rhel72
set base-url ${serverpath}/RHEL-7.2-x64
goto genericredhat

:ol71
set base-url ${serverpath}/OL7.1-x86_64
goto genericredhat

:ol68
set base-url ${serverpath}/OL6.8-x86_64
goto genericredhat6

:rhel68
set base-url ${serverpath}/RHEL-6.8-x64
goto genericredhat6

:centos68text
set base-url ${serverpath}/CentOS-6.8-x86_64
goto genericredhattext6

:centos68
set base-url ${serverpath}/CentOS-6.8-x86_64
goto genericredhat6

:fedora24
set base-url ${serverpath}/Fedora-24-x86_64
goto genericfedora

