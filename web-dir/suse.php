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
item --key h  hypervisor     Boot Suse HyperVisor

item --gap --                -------------- SLE 12 -------------
item          sles12sp1      Install SUSE Enterprise 12 SP1 Legacy Boot
item          sles12sp1uefi  Install SUSE Enterprise 12 SP1 UEFI mode
item          sles12sp1r     Rescue SUSE Enterprise 12 SP1
item --gap --                -------------- SLE 11 -------------
item          sles11sp4r     Rescue SUSE Enterprise 11 SP 4
item          sles11sp4      Install SUSE Enterprise 11 SP 4
item --gap --                -------------- SLE 10 -------------
item          sles10sp4      Install SUSE Enterprise 10 SP 4
item --gap --                -------------- openSUSE -------------
item          opensuse1320   Install openSUSE 13.2
item          opensuseleap42.1   Install openSUSE Leap 42.1
item --gap --                -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${serverpath}/boot/boot.php

:genericsuse
kernel  ${base-url}/boot/${susearch}/loader/linux install=${base-url}
initrd  ${base-url}/boot/${susearch}/loader/initrd
boot

:genericsuser
kernel  ${base-url}/boot/${susearch}/loader/linux install=${base-url} rescue=1
initrd  ${base-url}/boot/${susearch}/loader/initrd
boot

:hypervisor
echo "Just Kidding for now"
sleep 4
goto back

:sles12sp1
set susearch x86_64
set base-url ${serverpath}/SLES12SP1-x86_64
goto genericsuse

:sles12sp1r
set susearch x86_64
set base-url ${serverpath}/SLES12SP1-x86_64
goto genericsuse

:sles11sp4
set susearch x86_64
set base-url ${serverpath}/SLES11SP4-x64
goto genericsuse

:suse11sp4r
set susearch x86_64
set base-url ${serverpath}/SLES11SP4-x64
goto genericsuser

:sles10sp4
set susearch x86_64
set base-url ${serverpath}/SLES10SP3-x64
goto genericsuse

:opensuse1320
set susearch x86_64
set base-url ${serverpath}/OS13.2-x64
goto genericsuse

:opensuseleap42.1
set susearch x86_64
set base-url ${serverpath}/OSLEAP-42.1
goto genericsuse
