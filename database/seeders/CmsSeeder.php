<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'name' => 'Home',
                'seo_title' => ['hy' => 'ԷԼԴԵՍՔՈ | Էներգետիկ և ինժեներական համակարգեր', 'en' => 'ELDESCO | Energy & Engineering Systems', 'ru' => 'ELDESCO | Энергетические и инженерные системы'],
                'seo_description' => ['hy' => 'Էներգետիկ ենթակառուցվածքների և ինժեներական համակարգերի նախագծում և պատրաստում։', 'en' => 'Design and manufacturing of energy infrastructure and engineering systems.', 'ru' => 'Проектирование и производство энергетической инфраструктуры и инженерных систем.'],
                'sections' => [
                    [
                        'key' => 'hero', 'type' => 'hero', 'sort_order' => 1,
                        'content' => [
                            'eyebrow' => ['hy' => 'ԷԼԴԵՍՔՈ ՍՊԸ', 'en' => 'ELDESCO LLC', 'ru' => 'ELDESCO LLC'],
                            'title' => ['hy' => 'Էներգետիկ ենթակառուցվածքներ և ինժեներական համակարգեր', 'en' => 'Energy infrastructure & engineering systems', 'ru' => 'Энергетическая инфраструктура и инженерные системы'],
                            'text' => ['hy' => 'Նախագծում, արտադրություն, մոնտաժ և ավտոմատացում՝ արդյունաբերական ու էներգետիկ նախագծերի համար։', 'en' => 'Engineering, manufacturing, installation and automation for industrial and energy projects.', 'ru' => 'Проектирование, производство, монтаж и автоматизация промышленных и энергетических проектов.'],
                            'primary_cta' => ['hy' => 'Մեր ուղղությունները', 'en' => 'Explore capabilities', 'ru' => 'Наши направления'],
                            'secondary_cta' => ['hy' => 'Կապ մեզ հետ', 'en' => 'Contact us', 'ru' => 'Связаться'],
                        ],
                    ],
                    [
                        'key' => 'intro', 'type' => 'split', 'sort_order' => 2,
                        'content' => [
                            'title' => ['hy' => 'Ինժեներական փորձ՝ 2011 թվականից', 'en' => 'Engineering experience since 2011', 'ru' => 'Инженерный опыт с 2011 года'],
                            'text' => ['hy' => 'Էլդեսքո ՍՊԸ-ն հիմնադրվել է 2011 թվականին և գործունեության ընթացքում համագործակցել է հարյուրավոր փոքր, միջին և խոշոր կազմակերպությունների հետ։', 'en' => 'ELDESCO LLC was established in 2011 and has partnered with hundreds of small, medium and large enterprises.', 'ru' => 'ELDESCO LLC основана в 2011 году и сотрудничала с сотнями малых, средних и крупных предприятий.'],
                        ],
                    ],
                    [
                        'key' => 'capabilities', 'type' => 'cards', 'sort_order' => 3,
                        'content' => [
                            'title' => ['hy' => 'Գործունեության ոլորտները', 'en' => 'Areas of activity', 'ru' => 'Направления деятельности'],
                            'items' => [
                                ['slug' => 'power-infrastructure', 'title' => ['hy' => 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքներ', 'en' => 'Low- and Medium-Voltage Power Infrastructure', 'ru' => 'Энергетическая инфраструктура низкого и среднего напряжения']],
                                ['slug' => 'industrial-infrastructure', 'title' => ['hy' => 'Արտադրական ենթակառուցվածքներ', 'en' => 'Industrial Production Infrastructure', 'ru' => 'Производственная инфраструктура']],
                                ['slug' => 'led-displays', 'title' => ['hy' => 'Լուսադիոդային էկրաններ', 'en' => 'LED Display Systems', 'ru' => 'Светодиодные экраны']],
                                ['slug' => 'refrigeration', 'title' => ['hy' => 'Սառնարանային սարքավորումներ', 'en' => 'Refrigeration and Cooling Equipment', 'ru' => 'Холодильное и охлаждающее оборудование']],
                                ['slug' => 'metal-processing', 'title' => ['hy' => 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ', 'en' => 'Full-Cycle Sheet Metal Processing', 'ru' => 'Полный цикл обработки листового металла']],
                            ],
                        ],
                    ],
                    [
                        'key' => 'research', 'type' => 'feature', 'sort_order' => 4,
                        'content' => [
                            'title' => ['hy' => 'Գիտահետազոտական ուղղություն', 'en' => 'Research & development', 'ru' => 'Исследования и разработки'],
                            'text' => ['hy' => 'Մեծ չափերի լազերային բյուրեղների և արհեստական թանկարժեք քարերի արտադրություն։', 'en' => 'Production of large-size laser crystals and synthetic precious stones.', 'ru' => 'Производство крупноразмерных лазерных кристаллов и синтетических драгоценных камней.'],
                        ],
                    ],
                    [
                        'key' => 'customers', 'type' => 'logos', 'sort_order' => 5,
                        'content' => [
                            'title' => ['hy' => 'Մեր պատվիրատուները', 'en' => 'Our customers', 'ru' => 'Наши заказчики'],
                            'text' => ['hy' => 'Երկարաժամկետ համագործակցություններ՝ մատուցված ծառայությունների որակի շնորհիվ։', 'en' => 'Long-term collaborations built on consistently high-quality service.', 'ru' => 'Долгосрочное сотрудничество, основанное на стабильном качестве услуг.'],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'about', 'name' => 'About',
                'seo_title' => ['hy' => 'ԷլԴեսՔո-ի մասին', 'en' => 'About ELDESCO', 'ru' => 'О компании ELDESCO'],
                'sections' => [
                    ['key' => 'hero', 'type' => 'page-hero', 'sort_order' => 1, 'content' => ['title' => ['hy' => 'ԷլԴեսՔո-ի մասին', 'en' => 'About ELDESCO', 'ru' => 'О компании ELDESCO']]],
                    ['key' => 'story', 'type' => 'richtext', 'sort_order' => 2, 'content' => [
                        'title' => ['hy' => '2011-ից մինչև այսօր', 'en' => 'From 2011 to today', 'ru' => 'С 2011 года по сегодняшний день'],
                        'text' => ['hy' => 'Էլդեսքո ՍՊԸ-ն հիմնադրվել է 2011թ-ին։ Գործունեության ծավալման ընթացքում ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին և խոշոր տնտեսավարողների հետ։ 2023 թվականին արտադրական հնարավորությունների ընդլայնման նպատակով հիմնադրվել է մետաղամշակման առանձին ուղղություն՝ «Մեթալորքս» ՍՊԸ։ Արտասահմանյան գործընկերների հետ համագործակցության արդյունքում այն համալրվել է ժամանակակից բարձր ճշգրտության սարքավորումներով, որոնց մի մասը եզակի է ՀՀ-ում։', 'en' => 'ELDESCO LLC was established in 2011. Throughout its growth the company has partnered with hundreds of small, medium and large enterprises. In 2023, to expand production capabilities, the company established a dedicated metal-processing division, Metalworks LLC. Cooperation with international partners brought advanced high-precision machinery, some of which is unique in Armenia.', 'ru' => 'ELDESCO LLC основана в 2011 году. За время развития компания сотрудничала с сотнями малых, средних и крупных предприятий. В 2023 году для расширения производственных возможностей было создано отдельное направление металлообработки Metalworks LLC, оснащённое современным высокоточным оборудованием.'],
                    ]],
                ],
            ],
            [
                'slug' => 'power-infrastructure', 'name' => 'Power Infrastructure',
                'sections' => [
                    ['key' => 'hero', 'type' => 'page-hero', 'sort_order' => 1, 'content' => ['title' => ['hy' => 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքներ', 'en' => 'Low- and Medium-Voltage Power Infrastructure', 'ru' => 'Энергетическая инфраструктура низкого и среднего напряжения']]],
                    ['key' => 'services', 'type' => 'feature-list', 'sort_order' => 2, 'content' => ['items' => [
                        ['title' => ['hy' => 'Միջին լարման ենթակայաններ', 'en' => 'Medium-voltage substations', 'ru' => 'Подстанции среднего напряжения']],
                        ['title' => ['hy' => 'Ցածր և միջին լարման բաշխիչ վահանակներ', 'en' => 'Low- and medium-voltage distribution panels', 'ru' => 'Распределительные щиты низкого и среднего напряжения']],
                        ['title' => ['hy' => 'Ցածր լարման էներգիայի տեղափոխում', 'en' => 'Low-voltage power transmission systems — busbars and cable networks', 'ru' => 'Системы передачи электроэнергии низкого напряжения — шинопроводы и кабельные сети']],
                        ['title' => ['hy' => 'Հաճախային փոխակերպիչներ և շարժիչի փափուկ գործարկման համակարգեր', 'en' => 'Frequency converters and motor soft starter systems', 'ru' => 'Частотные преобразователи и системы плавного пуска двигателей']],
                    ]]],
                    ['key' => 'capacity', 'type' => 'stat', 'sort_order' => 3, 'content' => ['value' => '3200 kW', 'label' => ['hy' => 'Ներկայացված համակարգի հզորություն', 'en' => 'Presented system capacity', 'ru' => 'Мощность представленной системы']]],
                ],
            ],
            [
                'slug' => 'industrial-infrastructure', 'name' => 'Industrial Infrastructure',
                'sections' => [
                    ['key' => 'hero', 'type' => 'page-hero', 'sort_order' => 1, 'content' => ['title' => ['hy' => 'Արտադրական ենթակառուցվածքներ', 'en' => 'Industrial Production Infrastructure Solutions', 'ru' => 'Производственная инфраструктура']]],
                    ['key' => 'services', 'type' => 'feature-list', 'sort_order' => 2, 'content' => ['items' => [
                        ['title' => ['hy' => 'Սեղմած օդի մատակարարման կայաններ և խողովակաշարեր', 'en' => 'Compressed air supply stations and pipeline systems', 'ru' => 'Станции подачи сжатого воздуха и трубопроводы']],
                        ['title' => ['hy' => 'Պոմպակայաններ և չժանգոտվող պողպատից խողովակաշարեր', 'en' => 'Pumping stations and stainless steel pipeline systems', 'ru' => 'Насосные станции и трубопроводы из нержавеющей стали']],
                        ['title' => ['hy' => 'SCADA համակարգեր՝ Siemens STEP 7-ի հիման վրա', 'en' => 'SCADA systems based on Siemens STEP 7', 'ru' => 'SCADA-системы на базе Siemens STEP 7']],
                    ]]],
                ],
            ],
            ['slug' => 'refrigeration', 'name' => 'Refrigeration', 'sections' => [[
                'key' => 'hero', 'type' => 'page-hero', 'sort_order' => 1, 'content' => ['title' => ['hy' => 'Սառնարանային սարքավորումներ', 'en' => 'Refrigeration and Cooling Equipment', 'ru' => 'Холодильное и охлаждающее оборудование']]
            ], [
                'key' => 'gallery', 'type' => 'gallery', 'sort_order' => 2, 'content' => ['title' => ['hy' => 'Իրականացված լուծումներ', 'en' => 'Implemented solutions', 'ru' => 'Реализованные решения']]
            ]]],
            ['slug' => 'led-displays', 'name' => 'LED Displays', 'sections' => [[
                'key' => 'hero', 'type' => 'page-hero', 'sort_order' => 1, 'content' => ['title' => ['hy' => 'Լուսադիոդային էկրաններ', 'en' => 'LED Display Systems', 'ru' => 'Светодиодные экраны']]
            ], [
                'key' => 'gallery', 'type' => 'gallery', 'sort_order' => 2, 'content' => ['title' => ['hy' => 'Նախագծեր և տեղադրումներ', 'en' => 'Projects & installations', 'ru' => 'Проекты и установки']]
            ]]],
            ['slug' => 'metal-processing', 'name' => 'Metal Processing', 'sections' => [[
                'key' => 'hero', 'type' => 'page-hero', 'sort_order' => 1, 'content' => ['title' => ['hy' => 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ', 'en' => 'Full-Cycle Sheet Metal Manufacturing', 'ru' => 'Полный цикл обработки листового металла']]
            ], [
                'key' => 'capabilities', 'type' => 'richtext', 'sort_order' => 2, 'content' => ['title' => ['hy' => 'Բարձր ճշգրտության արտադրություն', 'en' => 'High-precision manufacturing', 'ru' => 'Высокоточное производство'], 'text' => ['hy' => 'Ժամանակակից սարքավորումներով ամբողջական արտադրական ցիկլ՝ թիթեղային մետաղի մշակման համար։', 'en' => 'A complete production cycle for sheet-metal processing using modern high-precision equipment.', 'ru' => 'Полный производственный цикл обработки листового металла на современном высокоточном оборудовании.']]
            ]]],
            ['slug' => 'contact', 'name' => 'Contact', 'sections' => [[
                'key' => 'hero', 'type' => 'page-hero', 'sort_order' => 1, 'content' => ['title' => ['hy' => 'Կապ մեզ հետ', 'en' => 'Contact us', 'ru' => 'Связаться с нами']]
            ], [
                'key' => 'contact', 'type' => 'contact', 'sort_order' => 2, 'content' => ['title' => ['hy' => 'Եկեք քննարկենք ձեր նախագիծը', 'en' => 'Let’s discuss your project', 'ru' => 'Обсудим ваш проект']]
            ]]],
        ];

        foreach ($pages as $pageData) {
            $sections = $pageData['sections'];
            unset($pageData['sections']);
            $page = Page::updateOrCreate(['slug' => $pageData['slug']], $pageData);

            foreach ($sections as $section) {
                $page->sections()->updateOrCreate(['key' => $section['key']], $section);
            }
        }

        $settings = [
            'brand' => ['group' => 'general', 'value' => ['name' => 'ELDESCO', 'tagline' => ['hy' => 'Էներգետիկ ենթակառուցվածքներ և ինժեներական համակարգեր', 'en' => 'Energy infrastructure & engineering systems', 'ru' => 'Энергетическая инфраструктура и инженерные системы']]],
            'contact' => ['group' => 'contact', 'value' => ['address' => ['hy' => 'ՀՀ, ք. Երևան, Թբիլիսյան 35/9', 'en' => '35/9 Tbilisyan Hwy, Yerevan, Armenia', 'ru' => 'Армения, Ереван, Тбилисян 35/9'], 'phone' => '+374 99 694 569', 'email' => 'eldesco@eldesco.am']],
            'navigation' => ['group' => 'navigation', 'value' => [
                ['slug' => 'about', 'label' => ['hy' => 'Մեր մասին', 'en' => 'About', 'ru' => 'О нас']],
                ['slug' => 'power-infrastructure', 'label' => ['hy' => 'Էներգետիկա', 'en' => 'Power', 'ru' => 'Энергетика']],
                ['slug' => 'industrial-infrastructure', 'label' => ['hy' => 'Արտադրություն', 'en' => 'Industrial', 'ru' => 'Промышленность']],
                ['slug' => 'metal-processing', 'label' => ['hy' => 'Մետաղամշակում', 'en' => 'Metalworks', 'ru' => 'Металлообработка']],
                ['slug' => 'contact', 'label' => ['hy' => 'Կապ', 'en' => 'Contact', 'ru' => 'Контакты']],
            ]],
            'social' => ['group' => 'contact', 'value' => ['facebook' => '', 'instagram' => '', 'linkedin' => '']],
        ];

        foreach ($settings as $key => $data) {
            SiteSetting::updateOrCreate(['key' => $key], $data);
        }
    }
}
