# -*- coding: utf-8 -*- 
i = [ ]
f = open("deletelist", 'w')
for a in i:
    data = "delete %s\n" % a
    f.write(data)
f.close()
