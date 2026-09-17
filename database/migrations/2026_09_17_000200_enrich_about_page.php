<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();
        if (! $page) {
            return;
        }

        $sections = [
            [
                'key' => 'about-timeline',
                'type' => 'timeline',
                'content' => [
                    'eyebrow' => [
                        'hy' => 'ՄԵՐ ՊԱՏՄՈՒԹՅՈՒՆԸ',
                        'en' => 'OUR STORY',
                        'ru' => 'НАША ИСТОРИЯ',
                    ],
                    'title' => [
                        'hy' => 'Փորձ, արտադրություն և շարունակական զարգացում',
                        'en' => 'Experience, production and continuous development',
                        'ru' => 'Опыт, производство и постоянное развитие',
                    ],
                    'items' => [
                        [
                            'year' => '2011',
                            'title' => [
                                'hy' => 'ELDESCO-ի հիմնադրում',
                                'en' => 'ELDESCO established',
                                'ru' => 'Основание ELDESCO',
                            ],
                            'description' => [
                                'hy' => 'Էլդեսքո ՍՊԸ-ն հիմնադրվել է 2011 թվականին՝ էներգետիկ ենթակառուցվածքների և ինժեներական համակարգերի նախագծման ու պատրաստման ուղղությամբ։',
                                'en' => 'ELDESCO LLC was established in 2011, focusing on the design and production of energy infrastructure and engineering systems.',
                                'ru' => 'ELDESCO LLC была основана в 2011 году и специализируется на проектировании и производстве энергетической инфраструктуры и инженерных систем.',
                            ],
                        ],
                        [
                            'year' => '2011+',
                            'title' => [
                                'hy' => 'Հարյուրավոր համագործակցություններ',
                                'en' => 'Hundreds of partnerships',
                                'ru' => 'Сотни партнерств',
                            ],
                            'description' => [
                                'hy' => 'Գործունեության զարգացման ընթացքում ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին և խոշոր տնտեսավարողների հետ՝ ձևավորելով շարունակական համագործակցություններ։',
                                'en' => 'Throughout its growth, the company has partnered with hundreds of small, medium and large enterprises, building long-term collaborations.',
                                'ru' => 'В процессе развития компания сотрудничала с сотнями малых, средних и крупных предприятий, формируя долгосрочные отношения.',
                            ],
                        ],
                        [
                            'year' => '2023',
                            'title' => [
                                'hy' => 'Metalworks-ի հիմնադրում',
                                'en' => 'Metalworks established',
                                'ru' => 'Создание Metalworks',
                            ],
                            'description' => [
                                'hy' => 'Արտադրական հնարավորությունների ընդլայնման նպատակով ստեղծվեց մետաղամշակման առանձին ուղղությունը՝ «Մեթալորքս» ՍՊԸ-ն։',
                                'en' => 'To expand production capabilities, the company established a dedicated metal processing division, Metalworks LLC.',
                                'ru' => 'Для расширения производственных возможностей компания создала отдельное направление металлообработки — Metalworks LLC.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'about-metalworks',
                'type' => 'feature_split',
                'content' => [
                    'eyebrow' => [
                        'hy' => 'ԱՐՏԱԴՐԱԿԱՆ ՀՆԱՐԱՎՈՐՈՒԹՅՈՒՆՆԵՐ',
                        'en' => 'PRODUCTION CAPABILITIES',
                        'ru' => 'ПРОИЗВОДСТВЕННЫЕ ВОЗМОЖНОСТИ',
                    ],
                    'title' => [
                        'hy' => 'Բարձր ճշգրտության մետաղամշակում Հայաստանում',
                        'en' => 'High-precision metal processing in Armenia',
                        'ru' => 'Высокоточная металлообработка в Армении',
                    ],
                    'image' => '/images/projects/metalworks.png',
                    'paragraphs' => [
                        [
                            'hy' => '2023 թվականին ELDESCO-ն հիմնեց մետաղամշակման առանձին ուղղություն՝ «Մեթալորքս» ՍՊԸ-ն։ Կարճ ժամանակահատվածում այն դիրքավորվեց որպես ոլորտի առաջատարներից մեկը Հայաստանի Հանրապետությունում։',
                            'en' => 'In 2023 ELDESCO established a dedicated metal processing division, Metalworks LLC. Within a short period it positioned itself among the industry leaders in Armenia.',
                            'ru' => 'В 2023 году ELDESCO создала отдельное направление металлообработки — Metalworks LLC. За короткое время оно вошло в число лидеров отрасли в Армении.',
                        ],
                        [
                            'hy' => 'Միջազգային գործընկերների հետ համագործակցության արդյունքում արտադրությունը համալրվել է ժամանակակից բարձր ճշգրտության սարքավորումներով, որոնց մի մասը եզակի է ՀՀ-ում։',
                            'en' => 'Through cooperation with international partners, the company has been equipped with advanced high-precision metal processing machinery, some of which is unique within Armenia.',
                            'ru' => 'Благодаря сотрудничеству с международными партнерами производство оснащено современным высокоточным оборудованием, часть которого уникальна для Армении.',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'about-research',
                'type' => 'feature_split',
                'content' => [
                    'eyebrow' => [
                        'hy' => 'ԳԻՏԱՀԵՏԱԶՈՏԱԿԱՆ ՈՒՂՂՈՒԹՅՈՒՆ',
                        'en' => 'RESEARCH & DEVELOPMENT',
                        'ru' => 'ИССЛЕДОВАНИЯ И РАЗРАБОТКИ',
                    ],
                    'title' => [
                        'hy' => 'Լազերային բյուրեղներ և արհեստական թանկարժեք քարեր',
                        'en' => 'Laser crystals and synthetic precious stones',
                        'ru' => 'Лазерные кристаллы и синтетические драгоценные камни',
                    ],
                    'image' => '/images/projects/substation.png',
                    'paragraphs' => [
                        [
                            'hy' => 'ELDESCO-ի առանձնակի ուղղություններից մեկն է գիտահետազոտական գործունեությունը, որը ներառում է մեծ չափերի լազերային բյուրեղների և արհեստական թանկարժեք քարերի արտադրություն։',
                            'en' => 'One of ELDESCO’s distinctive directions is its research and development activity, specializing in large-size laser crystals and synthetic precious stones.',
                            'ru' => 'Одним из особых направлений ELDESCO является научно-исследовательская деятельность, включающая производство крупногабаритных лазерных кристаллов и синтетических драгоценных камней.',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'about-cta',
                'type' => 'cta',
                'content' => [
                    'title' => [
                        'hy' => 'Քննարկենք ձեր ինժեներական նախագիծը',
                        'en' => 'Let’s discuss your engineering project',
                        'ru' => 'Обсудим ваш инженерный проект',
                    ],
                    'description' => [
                        'hy' => 'Կապվեք ELDESCO-ի թիմի հետ՝ նախագծման, արտադրության և ենթակառուցվածքային լուծումների վերաբերյալ։',
                        'en' => 'Contact the ELDESCO team for design, production and infrastructure solutions.',
                        'ru' => 'Свяжитесь с командой ELDESCO по вопросам проектирования, производства и инфраструктурных решений.',
                    ],
                    'cta_label' => [
                        'hy' => 'Կապ մեզ հետ',
                        'en' => 'Contact us',
                        'ru' => 'Связаться с нами',
                    ],
                    'cta_url' => '/contact',
                ],
            ],
        ];

        $nextOrder = (int) DB::table('page_sections')->where('page_id', $page->id)->max('sort_order') + 10;

        foreach ($sections as $section) {
            if (DB::table('page_sections')->where('page_id', $page->id)->where('key', $section['key'])->exists()) {
                continue;
            }

            DB::table('page_sections')->insert([
                'page_id' => $page->id,
                'type' => $section['type'],
                'key' => $section['key'],
                'content' => json_encode($section['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'settings' => json_encode([], JSON_UNESCAPED_UNICODE),
                'is_enabled' => true,
                'sort_order' => $nextOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $nextOrder += 10;
        }
    }

    public function down(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();
        if (! $page) {
            return;
        }

        DB::table('page_sections')
            ->where('page_id', $page->id)
            ->whereIn('key', ['about-timeline', 'about-metalworks', 'about-research', 'about-cta'])
            ->delete();
    }
};
