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

item --gap --                -------------- SLE 12 SP1 -------------
item          sles12sp1      Install SUSE Enterprise 12 SP1 Legacy Boot
item          sles12sp1uefi  Install SUSE Enterprise 12 SP1 UEFI mode
item          sles12sp1r     Rescue SUSE Enterprise 12 SP1
item --gap --                -------------- SLE 12 -------------
item          sles12         Install SUSE Enterprise 12 Legacy Boot
item          sles12uefi     Install SUSE Enterprise 12 UEFI mode
item          sles12r        Rescue SUSE Enterprise 12
item --gap --                -------------- SLE 11 -------------
item          sles11sp4      Install SUSE Enterprise 11 SP 4
item          rescue         Rescue SUSE Enterprise 11 SP 4
item          sles11sp3      Install SUSE Enterprise 11 SP 3
item          sles11sp3wm    Install SUSE Enterprise 11 SP 3 for VMware
item          sles11sp3a     Auto Install SUSE Enterprise 11 SP 3
item          sles11sp2      Install SUSE Enterprise 11 SP 2
item          sles10sp3      Install SUSE Enterprise 10 SP 3

item --gap --                -------------- openSUSE -------------
item          opensuseleap42.2   Install openSUSE Leap 42.2
item          opensuseleap42.1   Install openSUSE Leap 42.1
item          opensuse1310   Install openSUSE 13.2
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

:genericsusea
kernel  ${base-url}/boot/${susearch}/loader/linux install=${base-url} autoyast=${serverpath}/boot/autoinst.xml
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

:sles12
set susearch x86_64
set base-url ${serverpath}/SLES12-x86_64
goto genericsuse

:sles12r
set susearch x86_64
set base-url ${serverpath}/SLES12-x64
goto genericsuse

:sles11sp3
set susearch x86_64
set base-url ${serverpath}/SLES11SP3-x64
goto genericsuse

:sles11sp4
set susearch x86_64
set base-url ${serverpath}/SLES11SP4-x64
goto genericsuse

:sles11sp3a
set susearch x86_64
set base-url ${serverpath}/SLES11SP3-x64
goto genericsusea

:rescue
set susearch x86_64
set base-url ${serverpath}/SLES11SP4-x64
goto genericsuser

:sles11sp2
set susearch x86_64
set base-url ${serverpath}/SLES11SP2-x64
goto genericsuse

:sles11sp3wm
set susearch x86_64
set base-url ${serverpath}/SLES-11-SP3-for-VMware-DVD-x86_64
goto genericsuse

:sles10sp3
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

:opensuseleap42.2
set susearch x86_64
set base-url ${serverpath}/OSLEAP-42.2
goto genericsuse

