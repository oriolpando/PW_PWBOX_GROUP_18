# Homestead.yaml

```yaml

ip: "192.168.10.10"
memory: 2048
cpus: 1
provider: virtualbox


folders:
    - map: D:/Uni/4t Any/Projectes Web 2
      to: /home/vagrant/projects


sites:
    - map: homestead.test
      to: /home/vagrant/projects
    - map: mvc-intro.test
      to: /home/vagrant/projects/mvc-intro
    - map: pwBox.test
      to: /home/vagrant/projects/PwBox/public

databases:
    - homestead

```

Els scripts de la base de dades estàn a "assets/resources/script".

Al dashboard tenim dos parts de fitxers i carpetes dividits entre els que s'han compartit amb tu i els que has creat tu, de manera que no tenim dos .twig diferents.

Els emails en princpi s'envien tots correctament, però hi ha algún de les notificacions que no hem acabat de comprovar degut a que el wifi de la universitat feia que es penjés la pàgina en certes ocasions.

A part d'aixó, també ens hem estalviat diferents .twig al home ja que el registre i el login també estàn alla i els controlem mitjançant hidden.


Hores invertides:

Miquel Aprox 55 hores 
Carla Aprox 45 hores
Oriol  Aprox 60 hores 
