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

item --gap --                    ------------- Return to main menu ------------
item --key b  back               Back to main menu
item --gap --                    ----------------------------------------------
item          ubuntu1604server   Install Ubuntu Server 16.04
item          debian860          Install Debian 8.6.0 Network
item          proxmox42          Install ProxMox Virtualization Platform 4.2 
item --gap --                    -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${serverpath}/boot/boot.php

:genericubuntu
kernel  ${base-url}/install/vmlinuz install=${base-url}
#kernel  ${base-url}/install/vmlinuz video=vesa:ywrap,mtrr vga=788 initrd=/install/initrd.gz -- quiet auto=yes
initrd  ${base-url}/install/netboot/ubuntu-installer/amd64/initrd.gz
boot

:genericdebian
kernel  ${base-url}/install${arch}/vmlinuz video=vesa:ywrap,mtrr vga=788 initrd=/install${arch}/gtk/initrd.gz -- quiet auto=yes
initrd  ${base-url}/install${arch}/initrd.gz
boot

:genericproxmox
kernel  ${base-url}/boot/linux26 video=vesa:ywrap,mtrr vga=788 initrd=/boot/initrd.img -- quiet auto=yes
initrd  ${base-url}/boot/initrd.img
boot

:debian860
set arch .amd
set base-url ${serverpath}/DEB-8.6.0
goto genericdebian

:ubuntu1604server
set base-url ${serverpath}/U-16.04-SRV
goto genericubuntu

:proxmox42
set base-url ${serverpath}/proxmox-4.2-x64
goto genericproxmox
