# Installation

1. Start docker containers
   docker-compose up -d (when needed add flag --build to force rebuild of all containers)

2. Check all containers are running: docker ps (4 should be running)

3. Setup/change backend/.env file (already present in repo, needed only if custom changes are required)

4. Setup db & seed db with samples:

docker exec -it 3app-be bash
php artisan key:generate
php artisan migrate
php artisan db:seed

# Containers

1. Backend: Laravel v10 app
   Url: http://localhost
2. Frontend: Nuxt 2 app
   Url: http://localhost:3000
3. PHPMyAdmin
   Url: http://localhost:8080
   User: 3app
   password: appDb123

# Helpful commands

1. Connect to docker container with bash
   docker exec -it <container_name> bash

Example:
docker exec -it 3app-be bash

2. Run single command in container
   docker exec <container_name> <command>

Example:
docker exec 3app-be composer require <package_name>

3. Nuxt logs
   docker logs -f 3app-fe

4. Laravel logs
   docker exec -it 3app-be bash
   tail -f storage/logs/laravel.log

5. List running containers
   docker ps # add -a for all containers

6. Run DB migrations
   docker exec -it 3app-be bash
   php artisan migrate

7. Run DB migrations
   docker exec -it 3app-be bash
   php artisan migrate

to revert last migration:
php artisan migrate:rollback

8. Seed DB
   docker exec -it 3app-be bash
   php artisan db:seed

## Naloga 1

Predpostavi, da so v bazi shranjeni podatki, ki predstavljajo nakupe ali prodaje določenega izdelka skozi cas. Ti podatki so v bazi predstavljeni s transakcijami.
V app/Models/Transaction se nahaja podatovni model, ki predstavlja transakcijo:

- atribut date: pove datum in uro transakcije,
- atribut direction: pove ali gre za nakup ali prodajo,
- atribut product: pove za kater produkt gre,
- atribut quantity: pove količino,
- atribut price: pove ceno na enoto.

V laravelu dopolni komando src/backend/app/Console/CalculateStock, ki iz baze prebere vse transakcije in s pomočjo FIFO metode izračuna nabavno vrednost zaloge za posamezen produkt in kot celoto. Te podatke naj izpiše (s pomočjo dump() ali info funckije https://laravel.com/docs/10.x/artisan#writing-output). V primeru, da tekom izračuna zaloga rata negativna, mora program javiti napako, saj ne mora prodati nekaj kar nima.

Metoda FIFO predpostavlja, da podjetje proda najprej tisto zalogo produktov, ki je bila
najprej nabavljena. Torej pri vsaki prodaji (porabi) se poišče prvo razpoložljivo zalogo in po
tej ceni se ovrednoti poraba.

Primer 1:

1. Nakup 100 kosov produkta A po ceni 10,
2. Nakup 20 kosov produkta A po ceni 12 (stanje: 100enot po 10, 20enot po 12),
3. Prodaja 60 kosov produkta A po ceni 14 (stanje: 40enot po 10, 20enot po 12),
4. Prodaja 50 kosov produkta A po ceni 12 (stanje: 10enot po 12).

Rezultat: Zaloga produkta A je 10 in ima vrednost 120.

Primer 2:

1. nabava 300 kosov produkta B po ceni 20
2. nabava 80 kosov produkta B po ceni 25
3. prodaja 350 kosov produkta B po ceni 30
4. nabava 100 kosov produkta B po ceni 24
5. prodaja 80 kosov produkta B po ceni 10.

Rezultat: Zaloga produkta B je 50 kosov po ceni 24, vrednost zaloge pa 1200.

Primer 3:

1. Nakup 100 kosov produkta A po ceni 10,
2. Nakup 20 kosov produkta A po ceni 12,
3. Prodaja 200 kosov produkta A po ceni 14 (napak saj je samo 120 kosov na voljo),
4. Prodaja 50 kosov produkta A po ceni 12,

Rezultat: Napaka: zaloga ne mora biti negativna

Če komando CalculateStock izvedeš s podatki iz primera 1 in 2 bi moral izpis vsebovati (oblika zapisa ni pomembna):

- Zaloga produkta A je 10 kosov po ceni 10, vrednost zaloge produkta A je 120.
- Zaloga produkta B je 50 kosov po ceni 24, vrednost zaloge produkta B je 1200.
- Vrednost zaloge vseh produktov je 1320.

## Naloga 2

Pripravi naj se preprosto spletno aplikacijo, ki omogoča pregled, vnos in brisanje transakcij iz naloge 1. Za doseg tega je v projektu pripravljena Nuxt 2 aplikacija (koda je v src/frontend) z Buefy UI kitom (https://buefy.org/) in axios HTTP clientom. Podatke mora aplikacija pridobiti iz zalednega dela (Laravel). Izgled aplikacije ni pomemben, vazno je, da omogoča opisane funkcije, uporablja Nuxt-ov router in da je koda strukturirana. Sample kodo, ki je v aplikaciji še od instalacije naj se odstrani.

V Laravel delu naj se pripravi REST API za listanje, brisanje in ustvarjanje transakcij (glej:
https://laravel.com/docs/10.x/controllers#resource-controllers). Koda je v src/backend. API ne rabi biti kompleksen, vazno je da:

- omogoča create, delete in list metode,
- omogoča validacijo vhodnih podatkov,
- koda mora biti ustrezn ostrukturirana.
