# ELDESCO API - cPanel (shared hosting) տեղադրում, https://api.eldesco.am

Պատրաստի zip-ը (`eldesco-api-cpanel.zip`) արդեն պարունակում է Composer-ի փաթեթները (`vendor/`), ուստի Composer պետք չէ։
Տվյալների բազա՝ SQLite (ֆայլ), ուստի MySQL ստեղծել պետք չէ։

## 1. Ֆայլերը
File Manager -> **տնային պանակ** (`/home/<օգտանուն>`, ոչ `public_html`) -> Загрузить `eldesco-api-cpanel.zip` -> Извлечь.
Կստեղծվի `eldesco-api` պանակը։

## 2. Ենթադոմեյն
cPanel -> **Домены / Subdomains** -> Create:
- Subdomain՝ `api`, domain՝ `eldesco.am`
- **Document Root՝ `eldesco-api/public`** (կարևոր է. հակառակ դեպքում `.env`-ը հասանելի կլինի դրսից)

Հետո cPanel -> **MultiPHP Manager** -> `api.eldesco.am` -> PHP **8.2 կամ 8.3** (ոչ ավելի հին)։
(MultiPHP INI Editor-ում `upload_max_filesize` = 12M, `post_max_size` = 13M՝ նկարների համար)

## 3. Կարգավորումներ (.env)
`eldesco-api/ENV-TEMPLATE.txt` ֆայլը վերանվանեք `.env` (Rename), բացեք Edit-ով և **փոխեք
`ELDESCO_ADMIN_PASSWORD`-ը** երկար, եզակի գաղտնաբառով (ադմինի մուտքի համար)։ Մնացածը արդեն ճիշտ է։

## 4. Բազայի ստեղծում (մեկ անգամ)
**Տարբերակ A - cPanel Terminal** (եթե կա):
```bash
cd ~/eldesco-api
ea-php83 artisan key:generate --force
ea-php83 artisan migrate --seed --force
ea-php83 artisan config:cache
```
(եթե `ea-php83` չկա՝ փորձեք `ea-php82` կամ `php`, եթե նրա տարբերակը 8.2+ է)

**Տարբերակ B - առանց Terminal** - cPanel -> **Cron Jobs** -> ավելացրեք մեկ առաջադրանք (ամեն րոպե),
Command-ում մեկ տողով՝
```
/usr/local/bin/ea-php83 /home/<օգտանուն>/eldesco-api/artisan key:generate --force && /usr/local/bin/ea-php83 /home/<օգտանուն>/eldesco-api/artisan migrate --seed --force && /usr/local/bin/ea-php83 /home/<օգտանուն>/eldesco-api/artisan config:cache
```
Սպասեք 2 րոպե, հետո **ջնջեք այդ Cron առաջադրանքը** (որ նորից չաշխատի)։ Ելքը կարող եք ուղարկել ֆայլ՝
կոմանդի վերջում ավելացնելով ` > /home/<օգտանուն>/seed.log 2>&1` և նայել `seed.log`-ը։

## 5. Ստուգում
- https://api.eldesco.am/up - պետք է պատասխանի 200
- https://api.eldesco.am/api/site?lang=hy - JSON՝ ընկերության տվյալներով
- Հետո https://eldesco.am/admin -> email՝ `.env`-ի `ELDESCO_ADMIN_EMAIL`, գաղտնաբառ՝ `ELDESCO_ADMIN_PASSWORD`

## Խնդիրներ
| Ինչ է երևում | Պատճառ |
|---|---|
| 500 Server Error | PHP < 8.2, կամ `storage/` և `bootstrap/cache/` գրելի չեն (իրավունքներ 755), կամ `.env`-ը բացակայում է. տես `storage/logs/laravel.log` |
| `/api/...` -> 404 | ենթադոմեյնի Document Root-ը `eldesco-api/public` չէ, կամ `public/.htaccess` ֆայլը չկա |
| Ադմինում CORS սխալ | `.env`-ում `CORS_ALLOWED_ORIGINS`-ը պետք է պարունակի `https://eldesco.am`. հետո `config:cache` |
| Ադմինի մուտք չկա | Seed-ը չի աշխատել, կամ գաղտնաբառը փոխվել է seed-ից հետո (գաղտնաբառը վերցվում է միայն seed-ի պահին) |
| Նկարները չեն վերբեռնվում | PHP-ի `upload_max_filesize` փոքր է (քայլ 2) |

Բաժնի կրկնօրինակում՝ պարբերաբար պատճենեք `eldesco-api/database/database.sqlite` և `eldesco-api/public/storage/` (նկարները)։
