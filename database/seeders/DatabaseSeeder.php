<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();

        $services = $this->services();
        $this->seedServices($services);
        $this->seedProjects();
        $this->seedTeam();
        $this->seedSiteSettings();
        $this->seedPages($services);
        $this->seedNavigation();
    }

    private function seedAdmin(): void
    {
        $email = env('ELDESCO_ADMIN_EMAIL', 'admin@eldesco.am');
        $password = env('ELDESCO_ADMIN_PASSWORD', 'change-me-before-production');

        if (app()->environment('production') && $password === 'change-me-before-production') {
            $this->command?->warn('ELDESCO admin was not seeded: set ELDESCO_ADMIN_PASSWORD in production.');
            return;
        }

        if (app()->environment('production')) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'ELDESCO Admin',
                    'password_hash' => Hash::make($password),
                    'role' => 'admin',
                ]
            );
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'ELDESCO Admin',
                'password_hash' => Hash::make($password),
                'role' => 'admin',
            ]
        );
    }

    private function seedServices(array $services): void
    {
        if (Service::query()->exists()) {
            return;
        }

        foreach ($services as $service) {
            Service::create($service);
        }
    }

    private function seedProjects(): void
    {
        if (Project::query()->exists()) {
            return;
        }

        $projects = [
            [
                'title_hy' => 'Միջին լարման ենթակայաններ՝ 3200 կՎտ հզորությամբ',
                'title_en' => 'Medium-Voltage Substations - 3200 kW',
                'title_ru' => 'Подстанции среднего напряжения - 3200 кВт',
                'description_hy' => 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքների ուղղություն։',
                'description_en' => 'Low- and medium-voltage power infrastructure.',
                'description_ru' => 'Энергетическая инфраструктура низкого и среднего напряжения.',
                'category' => 'Power Infrastructure',
                'featured' => true,
                'order_index' => 1,
            ],
            [
                'title_hy' => 'Լուսադիոդային էկրաններ',
                'title_en' => 'LED Display Systems',
                'title_ru' => 'Светодиодные экраны',
                'category' => 'LED',
                'featured' => true,
                'order_index' => 4,
            ],
            [
                'title_hy' => 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ',
                'title_en' => 'Full-Cycle Sheet Metal Manufacturing',
                'title_ru' => 'Полный цикл обработки листового металла',
                'category' => 'Metalworks',
                'featured' => true,
                'order_index' => 5,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }

    private function seedTeam(): void
    {
        if (TeamMember::query()->exists()) {
            return;
        }

        TeamMember::create([
            'name_hy' => 'Վահե Պարսամյան',
            'name_en' => 'Vahe Parsamyan',
            'name_ru' => 'Ваге Парсамян',
            'position_hy' => 'Տնօրեն',
            'position_en' => 'Director',
            'position_ru' => 'Директор',
            'order_index' => 1,
        ]);

        TeamMember::create([
            'name_hy' => 'Վահրամ Կիկոյան',
            'name_en' => 'Vahram Kikoyan',
            'name_ru' => 'Ваграм Кикоян',
            'position_hy' => 'Գլխավոր հաշվապահ',
            'position_en' => 'Chief Accountant',
            'position_ru' => 'Главный бухгалтер',
            'order_index' => 2,
        ]);
    }

    private function seedSiteSettings(): void
    {
        $settings = [
            ['group' => 'company', 'key' => 'company.name', 'value' => ['hy' => 'ԷԼԴԵՍՔՈ ՍՊԸ', 'en' => 'ELDESCO LLC', 'ru' => 'ELDESCO LLC']],
            ['group' => 'company', 'key' => 'company.established_year', 'value' => 2011, 'type' => 'number'],
            ['group' => 'company', 'key' => 'company.tax_id', 'value' => '01015118'],
            ['group' => 'company', 'key' => 'company.registration', 'value' => '282.110.47988 04/10/2011'],
            ['group' => 'company', 'key' => 'company.director', 'value' => ['hy' => 'Վահե Պարսամյան', 'en' => 'Vahe Parsamyan', 'ru' => 'Ваге Парсамян']],
            ['group' => 'company', 'key' => 'company.chief_accountant', 'value' => ['hy' => 'Վահրամ Կիկոյան', 'en' => 'Vahram Kikoyan', 'ru' => 'Ваграм Кикоян']],
            ['group' => 'contact', 'key' => 'contact.business_address', 'value' => ['hy' => 'ՀՀ, ք․ Երևան, Թբիլիսյան 35/9', 'en' => '35/9 Tbilisyan Hwy, Yerevan, Armenia', 'ru' => 'Армения, Ереван, Тбилисское шоссе 35/9']],
            ['group' => 'contact', 'key' => 'contact.legal_address', 'value' => ['hy' => 'ՀՀ, ք․ Երևան, Թբիլիսյան 35/9', 'en' => '35/9 Tbilisyan Hwy, Yerevan, Armenia', 'ru' => 'Армения, Ереван, Тбилисское шоссе 35/9']],
            ['group' => 'contact', 'key' => 'contact.phone', 'value' => '+37499694569'],
            ['group' => 'contact', 'key' => 'contact.email', 'value' => 'eldesco@eldesco.am'],
            ['group' => 'bank', 'key' => 'bank.name', 'value' => ['hy' => 'ԱՄԵՐԻԱԲԱՆԿ ՓԲԸ', 'en' => 'AMERIABANK CJSC', 'ru' => 'AMERIABANK CJSC']],
            ['group' => 'bank', 'key' => 'bank.account_amd', 'value' => '1570012878510100 AMD'],
            ['group' => 'bank', 'key' => 'bank.account_usd', 'value' => '1570012878510201 USD'],
            ['group' => 'bank', 'key' => 'bank.account_eur', 'value' => '1570012878510246 EUR'],
        ];

        foreach ($settings as $setting) {
            if (DB::table('site_settings')->where('key', $setting['key'])->exists()) {
                continue;
            }

            DB::table('site_settings')->insert([
                'group' => $setting['group'],
                'key' => $setting['key'],
                'value' => $this->json($setting['value']),
                'type' => $setting['type'] ?? 'text',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedPages(array $services): void
    {
        $serviceItems = array_map(fn (array $service) => [
            'title' => ['hy' => $service['title_hy'], 'en' => $service['title_en'], 'ru' => $service['title_ru']],
            'description' => [
                'hy' => $service['description_hy'],
                'en' => $service['description_en'],
                'ru' => $service['description_ru'],
            ],
        ], $services);

        $customers = [
            'APACHE', 'Dalma Garden Mall', 'Solar City', 'Teghout Mining', 'VivaCell-MTS',
            'Aquatus', 'VEON', 'EcoVille', "Gold's Gym", 'TERYAN Residential Building',
            'Armada', 'Team Telecom Armenia', 'Armenia Wine', 'ART Group', 'Aqua Systems',
            'Vallex Group', 'IU Networks', 'Astra Crystals', 'Dilijan Beer', 'Synopsys',
            'SAGAMAR CJSC', 'Synergy International Systems', 'Cigarone', 'Ohanyan Brandy Company',
        ];

        $this->seedPage(
            'home',
            ['hy' => 'Գլխավոր', 'en' => 'Home', 'ru' => 'Главная'],
            ['hy' => 'ELDESCO - Էներգետիկ և ինժեներական համակարգեր', 'en' => 'ELDESCO - Energy & Engineering Systems', 'ru' => 'ELDESCO - Энергетические и инженерные системы'],
            ['hy' => 'Էներգետիկ ենթակառուցվածքների և ինժեներական համակարգերի նախագծում և պատրաստում։', 'en' => 'Design and production of energy infrastructure and engineering systems.', 'ru' => 'Проектирование и производство энергетической инфраструктуры и инженерных систем.'],
            0,
            [
                [
                    'key' => 'hero',
                    'type' => 'hero',
                    'content' => [
                        'eyebrow' => ['hy' => 'ԷԼԴԵՍՔՈ ՍՊԸ', 'en' => 'ELDESCO LLC', 'ru' => 'ELDESCO LLC'],
                        'title' => [
                            'hy' => 'Էներգետիկ ենթակառուցվածքների և ինժեներական համակարգերի նախագծում և պատրաստում',
                            'en' => 'Design and Production of Energy Infrastructure and Engineering Systems',
                            'ru' => 'Проектирование и производство энергетической инфраструктуры и инженерных систем',
                        ],
                        'cta_label' => ['hy' => 'Գործունեության ոլորտները', 'en' => 'Areas of activity', 'ru' => 'Направления деятельности'],
                        'cta_url' => '/services',
                    ],
                ],
                [
                    'key' => 'intro',
                    'type' => 'intro',
                    'content' => [
                        'title' => ['hy' => 'ԷլԴեսՔո-ի մասին', 'en' => 'About ELDESCO', 'ru' => 'О ELDESCO'],
                        'paragraphs' => [
                            [
                                'hy' => 'Էլդեսքո ՍՊԸ-ն հիմնադրվել է 2011թ-ին։ Գործունեության ծավալման ընթացքում ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին և խոշոր տնտեսավարողների հետ, և համագործակցությունը դարձել է շարունակական՝ մատուցված ծառայության բարձր որակի շնորհիվ։',
                                'en' => 'ELDESCO LLC was established in 2011. Throughout its operational growth, the company has partnered with hundreds of small, medium, and large enterprises, building long-term collaborations driven by the consistently high quality of its services.',
                                'ru' => 'ELDESCO LLC основана в 2011 году. За время развития компания сотрудничала с сотнями малых, средних и крупных предприятий, выстраивая долгосрочные отношения благодаря стабильно высокому качеству услуг.',
                            ],
                            [
                                'hy' => '2023 թվականին արտադրական հնարավորությունների ընդլայնման նպատակով ընկերությունը հիմնեց մետաղամշակման առանձին ուղղություն՝ «Մեթալորքս» ՍՊԸ-ն։',
                                'en' => 'In 2023, with the aim of expanding its production capabilities, the company established a dedicated metal processing division, Metalworks LLC.',
                                'ru' => 'В 2023 году для расширения производственных возможностей компания создала отдельное направление металлообработки - Metalworks LLC.',
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'services',
                    'type' => 'services',
                    'content' => [
                        'eyebrow' => ['hy' => 'Գործունեության ոլորտները', 'en' => 'Areas of activity', 'ru' => 'Направления деятельности'],
                        'title' => ['hy' => 'Հինգ հիմնական ուղղություն', 'en' => 'Five core directions', 'ru' => 'Пять основных направлений'],
                        'items' => $serviceItems,
                    ],
                ],
                [
                    'key' => 'stats',
                    'type' => 'stats',
                    'content' => [
                        'items' => [
                            ['value' => '2011', 'label' => ['hy' => 'Հիմնադրման տարի', 'en' => 'Established', 'ru' => 'Год основания']],
                            ['value' => '5', 'label' => ['hy' => 'Գործունեության հիմնական ոլորտ', 'en' => 'Core activity areas', 'ru' => 'Основных направлений']],
                            ['value' => '2023', 'label' => ['hy' => 'Metalworks-ի հիմնադրում', 'en' => 'Metalworks established', 'ru' => 'Создание Metalworks']],
                            ['value' => ['hy' => 'Հարյուրավոր', 'en' => 'Hundreds', 'ru' => 'Сотни'], 'label' => ['hy' => 'Համագործակցած տնտեսավարողներ', 'en' => 'Partner enterprises', 'ru' => 'Компаний-партнеров']],
                        ],
                    ],
                ],
                [
                    'key' => 'customers',
                    'type' => 'customers',
                    'content' => [
                        'title' => ['hy' => 'Մեր պատվիրատուները', 'en' => 'Our customers', 'ru' => 'Наши заказчики'],
                        'items' => array_map(fn (string $name) => ['name' => $name], $customers),
                    ],
                ],
                [
                    'key' => 'contact',
                    'type' => 'contact',
                    'content' => [
                        'title' => ['hy' => 'Կապ մեզ հետ', 'en' => 'Contact us', 'ru' => 'Связаться с нами'],
                        'phone' => '+37499694569',
                        'email' => 'eldesco@eldesco.am',
                        'address' => ['hy' => 'ՀՀ, ք․ Երևան, Թբիլիսյան 35/9', 'en' => '35/9 Tbilisyan Hwy, Yerevan, Armenia', 'ru' => 'Армения, Ереван, Тбилисское шоссе 35/9'],
                    ],
                ],
            ]
        );

        $this->seedPage(
            'about',
            ['hy' => 'Մեր մասին', 'en' => 'About us', 'ru' => 'О нас'],
            ['hy' => 'ELDESCO-ի մասին', 'en' => 'About ELDESCO', 'ru' => 'О ELDESCO'],
            null,
            1,
            [[
                'key' => 'company-story',
                'type' => 'rich_text',
                'content' => [
                    'title' => ['hy' => 'ԷլԴեսՔո-ի մասին', 'en' => 'About ELDESCO', 'ru' => 'О ELDESCO'],
                    'paragraphs' => [
                        [
                            'hy' => 'Էլդեսքո ՍՊԸ-ն հիմնադրվել է 2011թ-ին։ Գործունեության ծավալման ընթացքում ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին և խոշոր տնտեսավարողների հետ, ինչը դարձել է շարունակական՝ մատուցված ծառայության բարձր որակի շնորհիվ։',
                            'en' => 'ELDESCO LLC was established in 2011. Throughout its operational growth, the company has partnered with hundreds of small, medium, and large enterprises, building long-term collaborations driven by the consistently high quality of its services.',
                            'ru' => 'ELDESCO LLC основана в 2011 году. Компания сотрудничала с сотнями малых, средних и крупных предприятий, формируя долгосрочные отношения благодаря высокому качеству услуг.',
                        ],
                        [
                            'hy' => '2023 թվականին ընկերությունը հիմնեց մետաղամշակման առանձին ուղղություն՝ «Մեթալորքս» ՍՊԸ-ն։ Կարճ ժամանակահատվածում այն դիրքավորվեց որպես ոլորտի առաջատարներից մեկը ՀՀ-ում։ Արտասահմանյան գործընկերների հետ համագործակցության արդյունքում ընկերությունը համալրվել է մետաղի մշակման ժամանակակից, բարձր ճշգրտության սարքավորումներով, որոնց մի մասը եզակի է ՀՀ-ում։',
                            'en' => 'In 2023, the company established a dedicated metal processing division, Metalworks LLC. Within a short period, it positioned itself among the industry leaders in Armenia. Through cooperation with international partners, the company has been equipped with advanced, high-precision metal processing machinery, some of which is unique within Armenia.',
                            'ru' => 'В 2023 году компания создала отдельное направление металлообработки - Metalworks LLC. За короткое время оно вошло в число лидеров отрасли в Армении. Благодаря сотрудничеству с зарубежными партнерами производство оснащено современным высокоточным оборудованием, часть которого уникальна для Армении.',
                        ],
                        [
                            'hy' => 'Էլդեսքո ՍՊԸ-ի առանձնակի ուղղություններից մեկն է գիտահետազոտական ուղղությունը, որը առաջարկում է մեծ չափերի լազերային բյուրեղների և արհեստական թանկարժեք քարերի արտադրություն։',
                            'en' => 'One of ELDESCO LLC’s distinctive directions is its research and development division, specializing in the production of large-size laser crystals and synthetic precious stones.',
                            'ru' => 'Одно из особых направлений ELDESCO LLC - научно-исследовательское направление, специализирующееся на производстве крупногабаритных лазерных кристаллов и синтетических драгоценных камней.',
                        ],
                    ],
                ],
            ]]
        );

        $this->seedPage(
            'services',
            ['hy' => 'Ծառայություններ', 'en' => 'Services', 'ru' => 'Услуги'],
            ['hy' => 'Գործունեության ոլորտները', 'en' => 'Areas of activity', 'ru' => 'Направления деятельности'],
            null,
            2,
            [[
                'key' => 'services-overview',
                'type' => 'services',
                'content' => [
                    'title' => ['hy' => 'Գործունեության ոլորտները', 'en' => 'Areas of activity', 'ru' => 'Направления деятельности'],
                    'items' => $serviceItems,
                ],
            ]]
        );

        $this->seedPage('power-infrastructure', ['hy' => 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքներ', 'en' => 'Low- and Medium-Voltage Power Infrastructure', 'ru' => 'Энергетическая инфраструктура низкого и среднего напряжения'], null, null, 3, [[
            'key' => 'details', 'type' => 'bullets', 'content' => [
                'title' => ['hy' => 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքներ', 'en' => 'Low- and Medium-Voltage Power Infrastructure', 'ru' => 'Энергетическая инфраструктура низкого и среднего напряжения'],
                'items' => [
                    ['hy' => 'Միջին լարման ենթակայաններ', 'en' => 'Medium-voltage substations', 'ru' => 'Подстанции среднего напряжения'],
                    ['hy' => 'Ցածր և միջին լարման բաշխիչ վահանակներ', 'en' => 'Low- and medium-voltage distribution panels', 'ru' => 'Распределительные щиты низкого и среднего напряжения'],
                    ['hy' => 'Ցածր լարման էներգիայի տեղափոխում՝ հաղորդադողեր, մալուխներ', 'en' => 'Low-voltage power transmission systems - busbars and cable networks', 'ru' => 'Передача электроэнергии низкого напряжения - шинопроводы и кабельные сети'],
                    ['hy' => 'Ցածր և միջին լարման հաճախային փոխակերպիչներ, շարժիչի փափուկ գործարկման համակարգեր՝ մոնտաժ', 'en' => 'Installation of low- and medium-voltage frequency converters and motor soft starter systems', 'ru' => 'Монтаж частотных преобразователей низкого и среднего напряжения и систем плавного пуска двигателей'],
                ],
            ],
        ]]);

        $this->seedPage('industrial-infrastructure', ['hy' => 'Արտադրական ենթակառուցվածքներ', 'en' => 'Industrial Production Infrastructure', 'ru' => 'Производственная инфраструктура'], null, null, 4, [[
            'key' => 'details', 'type' => 'bullets', 'content' => [
                'title' => ['hy' => 'Արտադրական ենթակառուցվածքներ', 'en' => 'Industrial Production Infrastructure Solutions', 'ru' => 'Производственная инфраструктура'],
                'items' => [
                    ['hy' => 'Սեղմած օդի մատակարարման կայաններ, խողովակաշարեր', 'en' => 'Compressed air supply stations and pipeline systems', 'ru' => 'Станции подачи сжатого воздуха и трубопроводные системы'],
                    ['hy' => 'Պոմպակայաններ, չժանգոտվող պողպատից խողովակաշար', 'en' => 'Pumping stations and stainless steel pipeline systems', 'ru' => 'Насосные станции и трубопроводы из нержавеющей стали'],
                    ['hy' => 'SCADA հեռավար վերահսկման և տվյալների հավաքագրման ավտոմատացված համակարգեր՝ Siemens STEP 7-ի հիման վրա', 'en' => 'SCADA automated systems based on the Siemens STEP 7 platform', 'ru' => 'Автоматизированные системы SCADA на базе Siemens STEP 7'],
                ],
            ],
        ]]);

        $this->seedPage('led-displays', ['hy' => 'Լուսադիոդային էկրաններ', 'en' => 'LED Display Systems', 'ru' => 'Светодиодные экраны'], null, null, 5, [[
            'key' => 'title', 'type' => 'rich_text', 'content' => ['title' => ['hy' => 'Լուսադիոդային էկրաններ', 'en' => 'LED Display Systems', 'ru' => 'Светодиодные экраны']],
        ]]);

        $this->seedPage('refrigeration', ['hy' => 'Սառնարանային սարքավորումներ', 'en' => 'Refrigeration and Cooling Equipment', 'ru' => 'Холодильное и охлаждающее оборудование'], null, null, 6, [[
            'key' => 'title', 'type' => 'rich_text', 'content' => ['title' => ['hy' => 'Սառնարանային սարքավորումներ', 'en' => 'Refrigeration and Cooling Equipment', 'ru' => 'Холодильное и охлаждающее оборудование']],
        ]]);

        $this->seedPage('sheet-metal-processing', ['hy' => 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ', 'en' => 'Full-Cycle Sheet Metal Manufacturing', 'ru' => 'Полный цикл обработки листового металла'], null, null, 7, [[
            'key' => 'title', 'type' => 'rich_text', 'content' => ['title' => ['hy' => 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ', 'en' => 'Full-Cycle Sheet Metal Manufacturing', 'ru' => 'Полный цикл обработки листового металла']],
        ]]);

        $this->seedPage('customers', ['hy' => 'Մեր պատվիրատուները', 'en' => 'Our customers', 'ru' => 'Наши заказчики'], null, null, 8, [[
            'key' => 'customers', 'type' => 'customers', 'content' => [
                'title' => ['hy' => 'Մեր պատվիրատուները', 'en' => 'Our customers', 'ru' => 'Наши заказчики'],
                'items' => array_map(fn (string $name) => ['name' => $name], $customers),
            ],
        ]]);

        $this->seedPage('contact', ['hy' => 'Կապ', 'en' => 'Contact', 'ru' => 'Контакты'], null, null, 9, [[
            'key' => 'company-details', 'type' => 'company_details', 'content' => [
                'company' => ['hy' => 'ԷԼԴԵՍՔՈ ՍՊԸ', 'en' => 'ELDESCO LLC', 'ru' => 'ELDESCO LLC'],
                'bank' => ['hy' => 'ԱՄԵՐԻԱԲԱՆԿ ՓԲԸ', 'en' => 'AMERIABANK CJSC', 'ru' => 'AMERIABANK CJSC'],
                'accounts' => ['1570012878510100 AMD', '1570012878510201 USD', '1570012878510246 EUR'],
                'business_address' => ['hy' => 'ՀՀ, ք․ Երևան, Թբիլիսյան 35/9', 'en' => '35/9 Tbilisyan Hwy, Yerevan, Armenia', 'ru' => 'Армения, Ереван, Тбилисское шоссе 35/9'],
                'legal_address' => ['hy' => 'ՀՀ, ք․ Երևան, Թբիլիսյան 35/9', 'en' => '35/9 Tbilisyan Hwy, Yerevan, Armenia', 'ru' => 'Армения, Ереван, Тбилисское шоссе 35/9'],
                'phone' => '+37499694569',
                'email' => 'eldesco@eldesco.am',
                'tax_id' => '01015118',
                'registration' => '282.110.47988 04/10/2011',
                'director' => ['hy' => 'Վահե Պարսամյան', 'en' => 'Vahe Parsamyan', 'ru' => 'Ваге Парсамян'],
                'chief_accountant' => ['hy' => 'Վահրամ Կիկոյան', 'en' => 'Vahram Kikoyan', 'ru' => 'Ваграм Кикоян'],
            ],
        ]]);
    }

    private function seedPage(string $slug, array $title, ?array $seoTitle, ?array $seoDescription, int $sortOrder, array $sections): void
    {
        $page = Page::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'is_published' => true,
                'sort_order' => $sortOrder,
            ]
        );

        foreach ($sections as $index => $section) {
            $page->sections()->firstOrCreate(
                ['key' => $section['key']],
                [
                    'type' => $section['type'],
                    'content' => $section['content'],
                    'settings' => [],
                    'is_enabled' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function seedNavigation(): void
    {
        $items = [
            ['page_slug' => 'home', 'label' => ['hy' => 'Գլխավոր', 'en' => 'Home', 'ru' => 'Главная']],
            ['page_slug' => 'about', 'label' => ['hy' => 'Մեր մասին', 'en' => 'About us', 'ru' => 'О нас']],
            ['page_slug' => 'services', 'label' => ['hy' => 'Ծառայություններ', 'en' => 'Services', 'ru' => 'Услуги']],
            ['page_slug' => 'customers', 'label' => ['hy' => 'Պատվիրատուներ', 'en' => 'Customers', 'ru' => 'Заказчики']],
            ['page_slug' => 'contact', 'label' => ['hy' => 'Կապ', 'en' => 'Contact', 'ru' => 'Контакты']],
        ];

        foreach ($items as $index => $item) {
            if (DB::table('navigation_items')->where('menu', 'header')->where('page_slug', $item['page_slug'])->exists()) {
                continue;
            }

            DB::table('navigation_items')->insert([
                'menu' => 'header',
                'parent_id' => null,
                'label' => $this->json($item['label']),
                'url' => null,
                'page_slug' => $item['page_slug'],
                'target' => '_self',
                'is_enabled' => true,
                'sort_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function services(): array
    {
        return [
            [
                'title_hy' => 'Ցածր և միջին լարման Էներգետիկ ենթակառուցվածքներ',
                'title_en' => 'Low- and Medium-Voltage Power Infrastructure Solutions',
                'title_ru' => 'Энергетическая инфраструктура низкого и среднего напряжения',
                'description_hy' => 'Միջին լարման ենթակայաններ, ցածր և միջին լարման բաշխիչ վահանակներ, հաղորդադողեր և մալուխներ, հաճախային փոխակերպիչներ և շարժիչի փափուկ գործարկման համակարգեր։',
                'description_en' => 'Medium-voltage substations, low- and medium-voltage distribution panels, busbars and cable networks, frequency converters and motor soft starter systems.',
                'description_ru' => 'Подстанции среднего напряжения, распределительные щиты, шинопроводы и кабельные сети, частотные преобразователи и системы плавного пуска двигателей.',
                'icon' => '⚡',
                'order_index' => 1,
            ],
            [
                'title_hy' => 'Արտադրական ենթակառուցվածքներ',
                'title_en' => 'Industrial Production Infrastructure',
                'title_ru' => 'Производственная инфраструктура',
                'description_hy' => 'Սեղմած օդի մատակարարման կայաններ և խողովակաշարեր, պոմպակայաններ և չժանգոտվող պողպատից խողովակաշարեր, SCADA համակարգեր Siemens STEP 7-ի հիման վրա։',
                'description_en' => 'Compressed air supply stations and pipelines, pumping stations and stainless steel pipelines, and SCADA systems based on Siemens STEP 7.',
                'description_ru' => 'Станции подачи сжатого воздуха и трубопроводы, насосные станции и трубопроводы из нержавеющей стали, системы SCADA на базе Siemens STEP 7.',
                'icon' => '🏭',
                'order_index' => 2,
            ],
            [
                'title_hy' => 'Լուսադիոդային էկրաններ',
                'title_en' => 'LED Display Systems',
                'title_ru' => 'Светодиодные экраны',
                'description_hy' => null,
                'description_en' => null,
                'description_ru' => null,
                'icon' => '▦',
                'order_index' => 3,
            ],
            [
                'title_hy' => 'Սառնարանային սարքավորումներ',
                'title_en' => 'Refrigeration and Cooling Equipment',
                'title_ru' => 'Холодильное и охлаждающее оборудование',
                'description_hy' => null,
                'description_en' => null,
                'description_ru' => null,
                'icon' => '❄',
                'order_index' => 4,
            ],
            [
                'title_hy' => 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ',
                'title_en' => 'Full-Cycle Sheet Metal Processing',
                'title_ru' => 'Полный цикл обработки листового металла',
                'description_hy' => null,
                'description_en' => null,
                'description_ru' => null,
                'icon' => '◇',
                'order_index' => 5,
            ],
        ];
    }

    private function json(mixed $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
}
