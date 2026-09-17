<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ELDESCO_ADMIN_EMAIL', 'admin@eldesco.am');
        $adminPassword = env('ELDESCO_ADMIN_PASSWORD', 'change-me-before-production');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'ELDESCO Admin',
                'password_hash' => Hash::make($adminPassword),
                'role' => 'admin',
            ]
        );

        $services = [
            [
                'title_hy' => 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքներ',
                'title_en' => 'Low- and Medium-Voltage Power Infrastructure',
                'title_ru' => 'Энергетическая инфраструктура низкого и среднего напряжения',
                'description_hy' => 'Միջին լարման ենթակայաններ, բաշխիչ վահանակներ, հոսանատարներ և մալուխային ցանցեր, հաճախականության փոխակերպիչներ և փափուկ մեկնարկի համակարգեր։',
                'description_en' => 'Medium-voltage substations, distribution panels, busbars and cable networks, frequency converters and motor soft-starter systems.',
                'description_ru' => 'Подстанции среднего напряжения, распределительные щиты, шинопроводы и кабельные сети, частотные преобразователи и системы плавного пуска.',
                'icon' => '⚡',
                'order_index' => 1,
            ],
            [
                'title_hy' => 'Արտադրական ենթակառուցվածքներ',
                'title_en' => 'Industrial Production Infrastructure',
                'title_ru' => 'Производственная инфраструктура',
                'description_hy' => 'Սեղմած օդի մատակարարման կայաններ, պոմպակայաններ, չժանգոտվող պողպատից խողովակաշարեր և Siemens STEP 7-ի վրա հիմնված SCADA համակարգեր։',
                'description_en' => 'Compressed-air supply stations, pumping stations, stainless-steel pipelines and SCADA automation based on Siemens STEP 7.',
                'description_ru' => 'Станции сжатого воздуха, насосные станции, трубопроводы из нержавеющей стали и SCADA на базе Siemens STEP 7.',
                'icon' => '🏭',
                'order_index' => 2,
            ],
            [
                'title_hy' => 'Լուսադիոդային էկրաններ',
                'title_en' => 'LED Display Systems',
                'title_ru' => 'Светодиодные экраны',
                'description_hy' => 'LED ցուցադրման համակարգերի նախագծում, պատրաստում և տեղադրում։',
                'description_en' => 'Design, production and installation of LED display systems.',
                'description_ru' => 'Проектирование, производство и монтаж светодиодных экранных систем.',
                'icon' => '▦',
                'order_index' => 3,
            ],
            [
                'title_hy' => 'Սառնարանային սարքավորումներ',
                'title_en' => 'Refrigeration and Cooling Equipment',
                'title_ru' => 'Холодильное и охлаждающее оборудование',
                'description_hy' => 'Արդյունաբերական սառնարանային և հովացման սարքավորումներ ու համակարգեր։',
                'description_en' => 'Industrial refrigeration and cooling equipment and systems.',
                'description_ru' => 'Промышленное холодильное и охлаждающее оборудование и системы.',
                'icon' => '❄',
                'order_index' => 4,
            ],
            [
                'title_hy' => 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ',
                'title_en' => 'Full-Cycle Sheet Metal Processing',
                'title_ru' => 'Полный цикл обработки листового металла',
                'description_hy' => 'Բարձր ճշգրտության սարքավորումներով թիթեղային մետաղի լազերային կտրում, մշակում և արտադրություն։',
                'description_en' => 'Laser cutting, processing and manufacturing of sheet metal using high-precision equipment.',
                'description_ru' => 'Лазерная резка, обработка и производство изделий из листового металла на высокоточном оборудовании.',
                'icon' => '◇',
                'order_index' => 5,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['order_index' => $service['order_index']], $service);
        }

        $projects = [
            [
                'title_hy' => 'Միջին լարման ենթակայաններ՝ 3200 կՎտ հզորությամբ',
                'title_en' => 'Medium-Voltage Substations — 3200 kW',
                'title_ru' => 'Подстанции среднего напряжения — 3200 кВт',
                'description_hy' => 'ELDESCO-ի էներգետիկ ենթակառուցվածքների ուղղության ներկայացված լուծում։',
                'description_en' => 'A presented solution from ELDESCO’s power-infrastructure portfolio.',
                'description_ru' => 'Представленное решение из направления энергетической инфраструктуры ELDESCO.',
                'category' => 'Power Infrastructure',
                'featured' => true,
                'order_index' => 1,
            ],
            [
                'title_hy' => 'Սեղմած օդի մատակարարման համակարգեր',
                'title_en' => 'Compressed-Air Supply Systems',
                'title_ru' => 'Системы подачи сжатого воздуха',
                'description_hy' => 'Սեղմած օդի կայաններ և խողովակաշարային համակարգեր։',
                'description_en' => 'Compressed-air stations and pipeline systems.',
                'description_ru' => 'Станции сжатого воздуха и трубопроводные системы.',
                'category' => 'Industrial Infrastructure',
                'featured' => true,
                'order_index' => 2,
            ],
            [
                'title_hy' => 'SCADA ավտոմատացված համակարգեր',
                'title_en' => 'SCADA Automation Systems',
                'title_ru' => 'Автоматизированные системы SCADA',
                'description_hy' => 'Siemens STEP 7-ի վրա հիմնված ավտոմատացված կառավարման համակարգեր։',
                'description_en' => 'Automated control systems based on Siemens STEP 7.',
                'description_ru' => 'Автоматизированные системы управления на базе Siemens STEP 7.',
                'category' => 'Automation',
                'featured' => true,
                'order_index' => 3,
            ],
            [
                'title_hy' => 'LED ցուցադրման համակարգեր',
                'title_en' => 'LED Display Systems',
                'title_ru' => 'Светодиодные экранные системы',
                'description_hy' => 'Ֆասադային և ինտերիերային LED էկրանների լուծումներ։',
                'description_en' => 'Facade and indoor LED display solutions.',
                'description_ru' => 'Решения для фасадных и интерьерных LED-экранов.',
                'category' => 'LED',
                'featured' => true,
                'order_index' => 4,
            ],
            [
                'title_hy' => 'Թիթեղային մետաղի արտադրություն',
                'title_en' => 'Sheet Metal Manufacturing',
                'title_ru' => 'Производство изделий из листового металла',
                'description_hy' => 'Լազերային կտրում և ամբողջական արտադրական ցիկլ։',
                'description_en' => 'Laser cutting and full-cycle manufacturing.',
                'description_ru' => 'Лазерная резка и полный производственный цикл.',
                'category' => 'Metalworks',
                'featured' => true,
                'order_index' => 5,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['order_index' => $project['order_index']], $project);
        }

        TeamMember::updateOrCreate(
            ['name_hy' => 'Վահե Պարսամյան'],
            [
                'name_en' => 'Vahe Parsamyan',
                'name_ru' => 'Ваге Парсамян',
                'position_hy' => 'Տնօրեն',
                'position_en' => 'Director',
                'position_ru' => 'Директор',
                'order_index' => 1,
            ]
        );

        TeamMember::updateOrCreate(
            ['name_hy' => 'Վահրամ Կիկոյան'],
            [
                'name_en' => 'Vahram Kikoyan',
                'name_ru' => 'Ваграм Кикоян',
                'position_hy' => 'Գլխավոր հաշվապահ',
                'position_en' => 'Chief Accountant',
                'position_ru' => 'Главный Accountant',
                'order_index' => 2,
            ]
        );

        $home = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => ['hy' => 'Գլխավոր', 'en' => 'Home', 'ru' => 'Главная'],
                'seo_title' => [
                    'hy' => 'ELDESCO — Էներգետիկ և ինժեներական համակարգեր',
                    'en' => 'ELDESCO — Energy & Engineering Systems',
                    'ru' => 'ELDESCO — Энергетические и инженерные системы',
                ],
                'seo_description' => [
                    'hy' => 'Էներգետիկ ենթակառուցվածքների և ինժեներական համակարգերի նախագծում և պատրաստում։',
                    'en' => 'Design and production of energy infrastructure and engineering systems.',
                    'ru' => 'Проектирование и производство энергетической инфраструктуры и инженерных систем.',
                ],
                'is_published' => true,
                'sort_order' => 0,
            ]
        );

        $sections = [
            [
                'key' => 'hero',
                'type' => 'hero',
                'content' => [
                    'eyebrow' => ['hy' => 'ELDESCO LLC • 2011-ից', 'en' => 'ELDESCO LLC • Since 2011', 'ru' => 'ELDESCO LLC • С 2011 года'],
                    'title' => [
                        'hy' => 'Էներգետիկ ենթակառուցվածքներ և ինժեներական համակարգեր',
                        'en' => 'Energy Infrastructure & Engineering Systems',
                        'ru' => 'Энергетическая инфраструктура и инженерные системы',
                    ],
                    'subtitle' => [
                        'hy' => 'Նախագծում, պատրաստում և տեխնիկական լուծումներ արդյունաբերության ու բիզնեսի համար։',
                        'en' => 'Design, manufacturing and technical solutions for industry and business.',
                        'ru' => 'Проектирование, производство и технические решения для промышленности и бизнеса.',
                    ],
                    'cta_label' => ['hy' => 'Մեր ծառայությունները', 'en' => 'Our services', 'ru' => 'Наши услуги'],
                    'cta_url' => '/services',
                ],
            ],
            [
                'key' => 'intro',
                'type' => 'intro',
                'content' => [
                    'title' => ['hy' => 'ELDESCO-ի մասին', 'en' => 'About ELDESCO', 'ru' => 'О ELDESCO'],
                    'paragraphs' => [
                        [
                            'hy' => 'ELDESCO LLC-ն հիմնադրվել է 2011 թվականին։ Ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին և խոշոր տնտեսավարողների հետ։',
                            'en' => 'ELDESCO LLC was established in 2011 and has partnered with hundreds of small, medium and large enterprises.',
                            'ru' => 'ELDESCO LLC основана в 2011 году и сотрудничала с сотнями малых, средних и крупных предприятий.',
                        ],
                        [
                            'hy' => '2023 թվականին ստեղծվել է մետաղամշակման առանձին ուղղություն՝ Metalworks LLC-ն՝ արտադրական հնարավորությունների ընդլայնման համար։',
                            'en' => 'In 2023, Metalworks LLC was established as a dedicated metal-processing division to expand production capabilities.',
                            'ru' => 'В 2023 году было создано отдельное направление металлообработки Metalworks LLC для расширения производственных возможностей.',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'services',
                'type' => 'services',
                'content' => [
                    'eyebrow' => ['hy' => 'Գործունեության ուղղություններ', 'en' => 'What we do', 'ru' => 'Направления деятельности'],
                    'title' => ['hy' => 'Հինգ հիմնական ուղղություն', 'en' => 'Five core directions', 'ru' => 'Пять основных направлений'],
                    'items' => array_map(fn ($service) => [
                        'title' => ['hy' => $service['title_hy'], 'en' => $service['title_en'], 'ru' => $service['title_ru']],
                        'description' => ['hy' => $service['description_hy'], 'en' => $service['description_en'], 'ru' => $service['description_ru']],
                    ], $services),
                ],
            ],
            [
                'key' => 'stats',
                'type' => 'stats',
                'content' => [
                    'items' => [
                        ['value' => '2011', 'label' => ['hy' => 'Հիմնադրման տարի', 'en' => 'Founded', 'ru' => 'Год основания']],
                        ['value' => '5', 'label' => ['hy' => 'Հիմնական ուղղություն', 'en' => 'Core directions', 'ru' => 'Основных направлений']],
                        ['value' => '2023', 'label' => ['hy' => 'Metalworks ուղղության մեկնարկ', 'en' => 'Metalworks launched', 'ru' => 'Запуск Metalworks']],
                        ['value' => '100+', 'label' => ['hy' => 'Համագործակցած ընկերություններ', 'en' => 'Partnered enterprises', 'ru' => 'Компаний-партнеров']],
                    ],
                ],
            ],
            [
                'key' => 'contact',
                'type' => 'contact',
                'content' => [
                    'title' => ['hy' => 'Կապ մեզ հետ', 'en' => 'Contact us', 'ru' => 'Связаться с нами'],
                    'description' => [
                        'hy' => 'Պատրաստ ենք քննարկել ձեր ինժեներական կամ արտադրական նախագիծը։',
                        'en' => 'We are ready to discuss your engineering or production project.',
                        'ru' => 'Готовы обсудить ваш инженерный или производственный проект.',
                    ],
                    'phone' => '+37499694569',
                    'email' => 'eldesco@eldesco.am',
                    'address' => [
                        'hy' => 'ՀՀ, ք․ Երևան, Թբիլիսյան 35/9',
                        'en' => '35/9 Tbilisyan Hwy, Yerevan, Armenia',
                        'ru' => 'Армения, Ереван, Тбилисское шоссе 35/9',
                    ],
                ],
            ],
        ];

        foreach ($sections as $index => $section) {
            $home->sections()->updateOrCreate(
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
}
