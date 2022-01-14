# forti-tool
Fortigate Management Tools made by me

# blacklist
Php web that management fortigate's blacklist addrgrp
specify machinename, IP, API key, vdom at machine-list.json

# pfsense-forti
Python scripts for migrating from pfsense to fortigate
- dhcp static ip list
- alias

example pfsense backup files: dhcplist.xml, aliases.xml

<pre>
pf-forti.py usage
    pf-forti.py dhcp [file.xml]         pfsense dhcp static ip -> fortigate reserved ip
    pf-forti.py alias [file.xml]        pfsense aliases -> fortigate addrgrp
</pre>
