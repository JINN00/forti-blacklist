# -*- coding: utf-8 -*- 
i = [ ]
f = open("iplist", 'w')
for a in i:
    data = "edit \"%s\"\n    set type ipmask\n    set subnet %s\n    set allow-routing enable\nnext\n" % (a, a)
    f.write(data)
f.close()
